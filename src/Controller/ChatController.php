<?php
// src/Controller/ChatController.php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Client;
use function Cake\Core\env;

class ChatController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();

        $this->Authentication->allowUnauthenticated(['ask']);
    }

   public function ask()
    {
        $this->autoRender = false;
        $this->request->allowMethod(['post']);
        $this->response = $this->response->withType('application/json');

        $userMessage = $this->request->getData('message');

        if (empty($userMessage)) {
            return $this->response->withStringBody(json_encode([
                'error' => 'No message provided', 'response' => null
            ]));
        }

        $apiKey = Configure::read('Gemini.apiKey');

        if (empty($apiKey)) {
            return $this->response->withStringBody(json_encode([
                'error' => 'API key not configured', 'response' => null
            ]));
        }

        $http = new Client();
        $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

        try {
            $result = $http->post($url, json_encode([
                'contents' => [[
                    'role' => 'user',
                    'parts' => [['text' => $this->getSystemPrompt() . "\n\nUser Question: " . $userMessage]]
                ]],
                'generationConfig' => ['maxOutputTokens' => 1024, 'temperature' => 0.7],
            ]), [
                'type' => 'json',
                'headers' => ['Content-Type' => 'application/json'],
            ]);

            if ($result->isOk()) {
                $data = $result->getJson();
                $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? 'No response.';
                return $this->response->withStringBody(json_encode([
                    'response' => $text, 'error' => null
                ]));
            }

            return $this->response->withStringBody(json_encode([
                'error' => 'Gemini API error: ' . $result->getStatusCode(), 'response' => null
            ]));

        } catch (\Exception $e) {
            return $this->response->withStringBody(json_encode([
                'error' => 'Server error: ' . $e->getMessage(), 'response' => null
            ]));
        }
    }

    private function getSystemPrompt(): string
    {
        return <<<EOT
        You are a helpful AI assistant for SustainChain's website.

        SustainChain, led by Olivia Anderson, is a sustainable commerce platform connecting buyers, sellers, manufacturers, and farmers. The platform promotes eco-friendly products, supports both B2C and B2B relationships, and includes a "Discover Innovators" section for manufacturers. SustainChain is available on web and mobile and focuses on responsible consumption and ethical commerce.

        Instructions:

        * Keep answers short, clear, and professional.
        * Use a friendly and helpful tone.
        * Limit responses to 1–3 sentences whenever possible.
        * Do not give long explanations unless the user specifically asks for details.
        * Answer only questions related to SustainChain, including products, orders, delivery, manufacturers, sustainability initiatives, and policies.
        * If information is unavailable or outside your knowledge, say:
        "Please contact [support@sustainchain.com](mailto:support@sustainchain.com) for further assistance."
        * Avoid repeating information.
        * Do not use bullet points unless necessary.
        * Prioritize concise, direct answers over detailed descriptions.

        EOT;
    }
}