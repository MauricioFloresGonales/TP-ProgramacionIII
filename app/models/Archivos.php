<?php

class Archivos {
    public static function Leer ($nombreArchivo) {
        $jsonData = [];
        if (file_exists($nombreArchivo) === false) {
            $archivo = fopen($nombreArchivo, "x");
            fclose($archivo);
        } else {
            $jsonString = file_get_contents($nombreArchivo);
            $jsonData = json_decode($jsonString, true);
        }
        return $jsonData;
    }
    
    public static function Escribir($nombreArchivo, $arrayObjetos) {
        $archivo = fopen($nombreArchivo,"w");
        $json = json_encode($arrayObjetos, JSON_PRETTY_PRINT);
        fwrite($archivo, $json);
        fclose($archivo);
    }

    public static function LeerCSV ($nombreArchivo) {
        $arrayDeObejtos = [];
        $archivo = fopen($nombreArchivo, "r");

        while(!feof($archivo)){
            $respuesta = fgetcsv($archivo);
            
            if (is_array($respuesta)) {
                array_push($arrayDeObejtos, $respuesta);  
            }  
        }
        fclose($archivo);
        return $arrayDeObejtos;
    }

    public static function EscribirCSV($nombreArchivo, $arrayObjetos) {
        $fp = fopen($nombreArchivo, 'w');
        foreach ($arrayObjetos as $objeto) {
            fputcsv($fp, json_decode(json_encode($objeto), true));
        }
        fclose($fp);
    }

    public static function SubirFoto($path, $textName) {
        $title = "/" . $textName . ".jpg";

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $destino = $path . $title;
        move_uploaded_file($_FILES["foto"]["tmp_name"], $destino);
    }

    public static function SubirPDFDelLogo() {

        $carpetaDestino = 'Ejercicio16-Logo/';
        $nombreArchivo = 'Logo' . date("H-i-s") . '.pdf';

        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        if (copy('../recursos/LogoDeLaEmpresa.pdf', $carpetaDestino . $nombreArchivo)) {
            echo 'El PDF se ha guardado exitosamente a ' . $carpetaDestino;
        } else {
            echo 'Hubo un error al guardar el PDF.';
        }
    }
}