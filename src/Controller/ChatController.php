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
        // if (empty($apiKey)) {
        //     $apiKey = env('API_KEY');
        // }

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
        You are a helpful assistant for SustainChain's website. 
        Answer questions about our products, delivery, orders, and policies. 
        Be friendly, concise, and professional. 
        SustainChain, led by Olivia Anderson, is an innovative platform promoting sustainable commerce by connecting buyers, sellers, manufacturers, and farmers. Focused on eco-friendly products, SustainChain facilitates both B2C transactions and B2B relationships, with a dedicated 'Discover Innovators' section for manufacturers. Accessible on web browsers and mobile devices, the platform envisions a dynamic marketplace fostering responsible consumption and a greener future. SustainChain's goal is to create a vibrant community committed to sustainable living and ethical commerce.
        If a question is outside your knowledge, ask the user to contact support@sustainchain.com.
        EOT;
    }
}