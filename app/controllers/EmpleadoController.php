<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
require_once './models/Empleado.php';

class EmpleadoController extends Empleado
{
    public function LoginOld($request, $response, $args) {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        AutentificadorJWT::verificarToken($token);
        $datos = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::obtenerEmpleadoSegunNombre($datos->empleado);
        $empleado->ultimaSesion = date("Y-m-d H:i:s");

        //Empleado::modificarEmpleado($empleado);

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
    public function Login($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $empleado = $parametros['empleado'];
        $contraseña = $parametros['contraseña'];

        $empleadoEncontrado = Empleado::obtenerEmpleadoSegunNombre($empleado);

        if (!$empleadoEncontrado === false) {
            if ($empleadoEncontrado->contrasenia === $contraseña) {
                $empleadoEncontrado->ultimaSesion = date("Y-m-d H:i:s");
                $empleadoEncontrado->token = AutentificadorJWT::CrearToken($empleadoEncontrado->nombre);
                Empleado::modificarEmpleado($empleadoEncontrado);
                $payload = json_encode($empleadoEncontrado);
            } else {
                $payload = json_encode("La contraseña es incorrecta");
            }
        } else {
            $payload = json_encode("No se encotró el usuario");
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $rol = $parametros['rol'];
        $contrato = $parametros['contrato'];
        $contrasenia = $parametros['contrasenia'];

        $empleado = new Empleado();
        $empleado->nombre = $nombre;
        $empleado->rol = $rol;
        $empleado->contrato = $contrato;
        $empleado->estado = "desocupado";
        $empleado->contrasenia = $contrasenia;
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
        $idPedidos = json_decode($empleado[0]->pedidoAsignado);
        $empleado[0]->estado = "atendiendo";

        $pedidosModificados = [];
        foreach ($idPedidos as $id) {
            $auxPedido = Pedido::obtenerPedidoId($id);
            $auxPedido[0]->estado = "listo para servir";
            array_push($pedidosModificados, $auxPedido[0]);
            //DB
            Pedido::modificarPedido($auxPedido[0]);
        }
        //DB
        Empleado::modificarEmpleado($empleado);

        $payload = json_encode(array(
            "empleado" => $empleado,
            "pedidosModificados" => $pedidosModificados
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function CambiaEstadoAMesas($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $estado = $parametros['estado'];
        
        $pedidos = Pedido::obtenerPorEstado($estado);

        $pedidosModificados = [];
        $mesasModificados = [];
        foreach ($pedidos as $pedido) {
            $pedido->estado = "entregado";
            $cliente = Cliente::obtenerClienteId($pedido->cliente);
            $mesa = Mesa::obtenerMesaSegunNumero($cliente->mesa);
            $mesa->estado = "cliente comiendo";
            //DB
            Pedido::modificarPedido($pedido);
            Mesa::modificarMesa($mesa);
            array_push($pedidosModificados, $pedido);
            array_push($mesasModificados, $mesa);
        }

        $payload = json_encode(array(
            "pedidosModificados" => $pedidosModificados,
            "mesasModificados" => $mesasModificados
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

        //DB
        Mesa::modificarMesa($mesa);
        Empleado::modificarEmpleado($empleado);
        
        $payload = json_encode(array(
            "totalAPagar" => $mesa->totalDeLaCuenta,
            "mesa" => $mesa,
            "empleado" => $empleado
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