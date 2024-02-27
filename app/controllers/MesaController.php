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
        $resultado = [];
        foreach ($mesas as $mesa) {
            array_push($resultado, array(
                "id" => $mesa->id,
                "numMesa" => $mesa->numMesa,
                "estado" => $mesa->estado,
                "totalDeLaCuenta" => $mesa->totalDeLaCuenta
            ));
        }

        $payload = json_encode($resultado);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function MostrarTiempoDelPedido($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $mesa = $parametros['mesa'];
        $numPedido = $parametros['numPedido'];

        $mesa = Mesa::obtenerMesaSegunMesa($mesa);
        $cliente = Cliente::obtenerClienteId($mesa->cliente);
        $cuenta = Cuenta::obtenerCuentaId($cliente->relacionPedido);
        $idPedidos = json_decode($cuenta->idPedidos);
        
        $pedidoARetornar = null;
        foreach ($idPedidos as $id) {
            if ($id === $numPedido) {
                $pedidoARetornar = Pedido::obtenerPedidoId($id);
            }
        }
        if (is_null($pedidoARetornar)) {
            $payload  = 'no se encontró el pedido';
        } else {
            $payload = json_encode(array(
                "id" => $pedidoARetornar[0]->id,
                "nombre" => $pedidoARetornar[0]->nombre,
                "tiempo" => $pedidoARetornar[0]->tiempo . " min"
            ));
        }
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function Cerrar($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $numMesa = $parametros['numMesa'];

        $mesa = Mesa::obtenerMesaSegunNumero($numMesa);
        //DB
        $mesa = Mesa::CerrarPorNumMesa($numMesa);

        $payload = json_encode($mesa);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function EditEstado($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idMesa = $parametros['idMesa'];
        $estado = $parametros['estado'];

        $mesas = Mesa::EditarEstadoMesa($idMesa, $estado);
        $payload = json_encode($mesas);

        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}