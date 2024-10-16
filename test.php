<?php
require 'vendor/autoload.php';

use FastRoute\RouteCollector;
// تعریف مسیرها
$dispatcher = FastRoute\simpleDispatcher(function(RouteCollector $r) {
    $r->addRoute('GET', '/', 'homeHandler');
    $r->addRoute('GET', '/about', 'aboutHandler');
    $r->addRoute('GET', '/user/{id:\d+}', 'userHandler');
});

// دریافت متد و آدرس درخواست
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// حذف query string از آدرس
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo '404 Not Found';
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        // ... 405 Method Not Allowed
        $allowedMethods = $routeInfo[1];
        echo '405 Method Not Allowed';
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
        // ... call $handler with $vars
        call_user_func($handler, $vars);
        break;
}

// تعریف هندلرها
function homeHandler() {
    
    

    function encrypt($plaintext, $key) {
        // Generate a random IV each time the function is called
        $ivSize = openssl_cipher_iv_length('aes-256-ctr');
        $iv = openssl_random_pseudo_bytes($ivSize);
    
        // Encrypt the plaintext
        $ciphertext = openssl_encrypt($plaintext, 'aes-256-ctr', $key, OPENSSL_RAW_DATA, $iv);
    
        // Encode the IV and ciphertext to Base64 to ensure safe storage/transmission
        $ivBase64 = base64_encode($iv);
        $ciphertextBase64 = base64_encode($ciphertext);
    
        // Return IV and ciphertext as a JSON object for easy extraction
        return "$ivBase64:$ciphertextBase64";
    }
    $key = 'IYqSJoHyqHmC8K2jbiGqppR25xjNM2wo';
    $encryptedData = encrypt(json_encode("begzar.xyz"), $key);

    echo $encryptedData;

}

function aboutHandler() {
    echo 'This is the about page.';
}

function userHandler($vars) {
    echo 'User ID: ' . $vars['id'];
}
