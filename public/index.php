<?php

require __DIR__ . '/../vendor/autoload.php';

$_ENV['DEBUG_MODE'] = true;
$_ENV['ROOT_PROJECT'] = __DIR__ . '/../';

use EasyAPI\Router;

Router::get('/hello-world', function () {
    return view('json', 'Hello world');
});

Router::get('/hello/(?<name>.*?)', function (string $name) {
    return view('json', 'Hello ' . $name);
});

Router::get('/API/CNE/(?<cedula>.*?)', function (string $cedula) {
    $nacionalidad = substr($cedula, 0, 1);
    $cedula = substr($cedula, 1);

    $res = file_get_contents("http://www.cne.gov.ve/web/registro_electoral/ce.php?nacionalidad=$nacionalidad&cedula=$cedula");
    $res = str_replace(">", "<>", $res);
    $splitCode = explode("<", $res);

    $noRegistrada = $splitCode[124];

    if ($noRegistrada == ">Esta cédula de identidad no se encuentra inscrito en el Registro Electoral.") {
        $msg = "Esta cedula de identidad no se encuentra inscrito en el Registro Electoral.";
        return view('json', $msg);
    } else {
        $cedula = $splitCode[110];
        $nombre = $splitCode[132];
        $estado = $splitCode[154];
        $municipio = $splitCode[174];
        $parroquia = $splitCode[194];
        $centro = $splitCode[216];
        $direccion = $splitCode[240];

        $contribuyente = array(
            'cedula' => $cedula,
            'nombre' => $nombre,
            'estado' => $estado,
            'municipio' => $municipio,
            'parroquia' => $parroquia,
            'centro' => $centro,
            'direccion' => $direccion
        );
        return view('json', $contribuyente);
    }

});

$app = new EasyAPI\App();
$app->send();