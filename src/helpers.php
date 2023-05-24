<?php

if(!function_exists('view')) {
    function view(
        string $type,
        $data = null,
        int $status_code = 200,
        array $headers = []
    ): EasyAPI\Response
    {
        return new EasyAPI\Response($type, $data, $status_code, $headers);
    }
}

if(!function_exists('format_response')) {
    function format_response(string $parameter) {
        return $parameter = ucwords(strtolower(str_replace(">", "", $parameter)));
    }
}