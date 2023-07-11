<?php

require_once './models/Empleado.php';
//crear ultima secion con middleware

class EmpleadoController extends Empleado
{
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $rol = $parametros['rol'];
        $contrato = $parametros['contrato'];

        $empleado = new Empleado();
        $empleado->nombre = $nombre;
        $empleado->rol = $rol;
        $empleado->contrato = $contrato;
        $empleado->estado = "desocupado";
        $idCreado = $empleado->crearEmpleado();
        $empleado->id = $idCreado;
        
        $payload = json_encode($empleado);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodo($request, $response, $args) {
        $empleados = Empleado::obtenerTodos();
        $payload = json_encode($empleados);

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function Desocupar($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idEmpleado = $parametros['empleado'];

        $empleado = Empleado::obtenerEmpleado($idEmpleado);
        $empleado->estado = "desocupado";

        $payload = json_encode($empleado);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

}