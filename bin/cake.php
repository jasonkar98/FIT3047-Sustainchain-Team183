<?php
use App\Application;
use Cake\Console\CommandRunner;

// Bootstrap the application.
require dirname(__DIR__) . '/vendor/autoload.php';

$app = new Application(dirname(__DIR__) . '/config');
$runner = new CommandRunner($app, 'cake');
exit($runner->run($argv));
