<?php
require_once './models/Encuesta.php';

class EncuestaController extends Cliente
{
    public function OrdenarComenarios($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $ordenamiento = $parametros['ordenamiento'];

        $encuestasOrdenadas = Encuesta::OrdenarEncuestasPorComentarios($ordenamiento); 
        $payload = json_encode($encuestasOrdenadas);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function MesaMasUsada($request, $response, $args) {
        $datos = Encuesta::OrdenarMesaMasUsada();
        $resultado = [];

        foreach ($datos as $dato) {
            array_push($resultado, array(
                "numMesa" => $dato->numMesa,
                "vecesUsada" => $dato->vecesUsada
            ));
        }

        $payload = json_encode($resultado);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodo($request, $response, $args) {
        $clientes = Cliente::obtenerTodos();
        $payload = json_encode($clientes);

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}