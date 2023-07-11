<?php

require_once './models/Mesa.php';

class MesaController extends Mesa
{
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $numMesa = $parametros['numMesa'];

        $mesa = new Mesa();
        $mesa->numMesa = $numMesa;
        $mesa->estado = "vacia";
        $mesa->totalDeLaCuenta = 0;
        $idMesaCreado = $mesa->crearMesa();
        $mesa->id = $idMesaCreado;

        $payload = json_encode($mesa);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');

    }

    public function TraerTodo($request, $response, $args) {
        $mesas = Mesa::obtenerTodos();
        $payload = json_encode($mesas);

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}