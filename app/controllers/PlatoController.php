<?php

require_once './models/Platos.php';

class PlatoController extends Platos
{
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $tipo = $parametros['tipo'];
        $nombre = $parametros['nombre'];
        $precio = $parametros['precio'];
        
        $plato = new Platos();
        $plato->tipo = $tipo;
        $plato->nombre = $nombre;
        $plato->precio = $precio;
        $idCreado= $plato->crearPlato();
        $plato->id = $idCreado;


        $payload = json_encode(array(
            "mensaje" => "Plato creado con exito",
            "dato" => $plato
        ));

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');

    }

    public function TraerTodo($request, $response, $args) {
        $platos = Platos::obtenerTodos();
        $payload = json_encode($platos);

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}