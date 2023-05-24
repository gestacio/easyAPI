<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/BuscarCNE.php';

$_ENV['DEBUG_MODE'] = true;
$_ENV['ROOT_PROJECT'] = __DIR__ . '/../';

use EasyAPI\Router;

// Router::get('/hello-world', function () {
//     return view('json', 'Hello world');
// });

// Router::get('/hello/(?<name>.*?)', function (string $name) {
//     return view('json', 'Hello ' . $name);
// });

Router::get('/API/CNE/(?<nacionalidad>.*?)/(?<cedula>.*?)', function (string $nacionalidad, string $cedula) {
    $contribuyente = new BuscarCNE;
    $json = $contribuyente->obtenerElector($nacionalidad, $cedula);
    return view('json', $json);
});

$app = new EasyAPI\App();
$app->send();