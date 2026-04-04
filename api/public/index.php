<?php

use Config\Bootstrap;
use Neoan\Helper\Env;
use Neoan\NeoanApp;
use Neoan\Routing\AttributeRouting;

require_once '../vendor/autoload.php';

header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$appPath = dirname(__DIR__) . '/src';
$publicPath = __DIR__;
$cliPath = dirname(__DIR__);

$setup = new \Neoan\Helper\Setup();
$setup->setPublicPath($publicPath)->setLibraryPath($appPath);

$app = new NeoanApp($setup, $cliPath);
$app->invoke(new AttributeRouting('App'));
new Bootstrap($app);

$app->run();