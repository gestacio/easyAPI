<?php

require __DIR__ . '/../vendor/autoload.php';

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
    $res = file_get_contents("http://www.cne.gov.ve/web/registro_electoral/ce.php?nacionalidad=$nacionalidad&cedula=$cedula");
    $res = str_replace(">", "<>", $res);
    $splitCode = explode("<", $res);

    $registrada = $splitCode[80];

    if (!($registrada === ">DATOS DEL ELECTOR")) {
        $msg = "Esta cedula de identidad no se encuentra inscrito en el Registro Electoral.";
        return view('json', $msg);
    } else {
        $registrado = array();

        $cedula = format_response($splitCode[110]);
        $nombre = format_response($splitCode[132]);
        $estado = format_response($splitCode[154]);
        $municipio = format_response($splitCode[174]);
        $parroquia = format_response($splitCode[194]);
        $centro = format_response($splitCode[216]);
        $direccion = format_response($splitCode[240]);

        $contribuyente = array(
            'Cedula' => $cedula,
            'Nombre' => $nombre,
            'Estado' => $estado,
            'Municipio' => $municipio,
            'Parroquia' => $parroquia,
            'Centro' => $centro,
            'Direccion' => $direccion,
        );
    
        return view('json', $contribuyente);
    }
   

});

$app = new EasyAPI\App();
$app->send();