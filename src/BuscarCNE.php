<?php

class BuscarCNE {
    public function obtenerElector($nacionalidad = "", $cedula = "") {
        $url = "http://www.cne.gob.ve/web/registro_electoral/ce.php?nacionalidad=".$nacionalidad."&cedula=".$cedula;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_REFERER,'http://www.cne.gob.ve/');
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (X11; Linux i686; rv:32.0) Gecko/20100101 Firefox/32.0');
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE);
        curl_setopt($ch,CURLOPT_FRESH_CONNECT,TRUE);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,5);
        curl_setopt($ch,CURLOPT_TIMEOUT,10);
        $html=curl_exec($ch);
        if($html==false){
            $m=curl_error(($ch));
            error_log($m);
            curl_close($ch);
            $json['error'] = true;
            $json['descripcion'] = $m;
            print json_encode($json);
            return;
        } else {
            curl_close($ch);
            if (strpos($html, '<b>DATOS DEL ELECTOR</b>') > 0) {
                $modo = 1; # Puede Votar
            // } else if (strpos($html, '<strong>DATOS PERSONALES</strong>') > 0) {
            //     $modo = 2; # No Puede Votar
            } else {
                $modo = -1;
                $json['error'] = true;
                $json["descripcion"] = "El usuario no se encuentra inscrito en el registro electoral";
                return $json;
            }
            $json['error'] = false;
            $json["descripcion"] = "/cne/elector";
            $json['modo'] = $modo;
            
            // Datos para un elector que puede votar
            if ($json['modo'] == 1) {
                $html = str_replace(">", "<>", $html);
                $splitCode = explode("<", $html);

                $cedula = self::formatear_respuesta($splitCode[110]);
                $nombre = self::formatear_respuesta($splitCode[132]);
                $estado = self::formatear_respuesta($splitCode[154]);
                $municipio = self::formatear_respuesta($splitCode[174]);
                $parroquia = self::formatear_respuesta($splitCode[194]);
                $centro = self::formatear_respuesta($splitCode[216]);
                $direccion = self::formatear_respuesta($splitCode[240]);

                $contribuyente = array(
                    'Cedula' => $cedula,
                    'Nombre' => $nombre,
                    'Estado' => $estado,
                    'Municipio' => $municipio,
                    'Parroquia' => $parroquia,
                    'Centro' => $centro,
                    'Direccion' => $direccion,
                );

            }
            return $contribuyente;
        }
    }

    public function formatear_respuesta(string $parameter) {
        return $parameter = ucwords(strtolower(str_replace(">", "", $parameter)));
    }
}