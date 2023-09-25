<?php

require_once './models/Empleado.php';

class EmpleadoController extends Empleado
{
    public function Login($request, $response, $args) {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        AutentificadorJWT::verificarToken($token);
        $datos = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::obtenerEmpleadoSegunNombre($datos->empleado);
        $empleado->ultimaSesion = date("Y-m-d H:i:s");

        Empleado::modificarEmpleado($empleado);

        $payload = json_encode(array(
            "id" => $empleado->id,
            "nombre" => $empleado->nombre,
            "rol" => $empleado->rol,
            "ultimaSesion" => $empleado->ultimaSesion
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
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

    public function ListarPendiestesYServir($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $rol = $parametros['rol'];

        $empleado = Empleado::obtenerEmpleadoRol($rol);
        $pedido = Pedido::obtenerPedidoMoso($empleado->id);
        $auxPedido =  Pedido::obtenerPedidoTipoDePedido($pedido->tipoDePedido);
        $empleado->estado = "listo para servir";
        $pedido->estado = "entregado";
        Pedido::modificarPedido($pedido);

        Empleado::modificarEmpleado($empleado);
        $pedidoActualizado = Pedido::obtenerPedidoMoso($empleado->id);

        $payload = json_encode(array(
            "pedidos" => $auxPedido,
            "CambioEstado" => $pedidoActualizado
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function CambiaEstadoAMesas($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $estado = $parametros['estado'];
        
        $pedidos = Pedido::obtenerPorEstado($estado);
        $mesa = Mesa::obtenerPorEstado('cliente esperando pedido');

        $mesa->estado = "cliente comiendo";
        Mesa::modificarMesa($mesa);

        $payload = json_encode(array(
            "pedidos" => $pedidos,
            "mesa" => $mesa
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function PedirCuenta($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $idEmpleado = $parametros['empleado'];
        $numMesa = $parametros['numMesa'];

        $mesa = Mesa::obtenerMesaSegunMesa($numMesa);
        $empleado = Empleado::obtenerEmpleado($idEmpleado);
        $mesa->estado = "cliente pagando";
        $empleado->estado = "desocupado";

        Mesa::modificarMesa($mesa);
        Empleado::modificarEmpleado($empleado);
        
        $payload = json_encode(array(
            "mesa" => $mesa,
            "totalAPagar" => $mesa->totalDeLaCuenta
        ));
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