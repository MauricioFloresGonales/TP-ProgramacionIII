<?php
require_once './models/Cliente.php';
require_once './models/Mesa.php';

class ClienteController extends Cliente
{
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $mesa = $parametros['mesa'];

        $cliente = new Cliente();
        $cliente->nombre = $nombre;
        $cliente->mesa = $mesa;
        $idClienteCreado = $cliente->crearCliente();
        $cliente->id = $idClienteCreado;

        $mesa = Mesa::obtenerMesaSegunMesa($mesa);
        $mesa->cliente = $cliente->id;
        $mesa->estado = "cliente esperando atencion";
        $mesa->totalDeLaCuenta = 0;
        $mesa->pedidos = null;
        $mesa->totalDeLaCuenta = 0;
        $mesa->foto = null;
        $mesa->puntuarLaMesa = null;
        $mesa->puntuarLaRestaurante = null;
        $mesa->puntuarLaMozo = null;
        $mesa->puntuarLaCocinero = null;
        $mesa->experiencia = null;
        
        Mesa::modificarMesa($mesa);

        $payload = json_encode(array("cliente" => $cliente, "mesaAsignada" => $mesa));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function CompletarEncuesta($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $numMesa = $parametros['numMesa'];
        $numPedido = $parametros['numPedido'];
        $puntuarLaMesa = $parametros['puntuarLaMesa'];
        $puntuarLaRestaurante = $parametros['puntuarLaRestaurante'];
        $puntuarLaMozo = $parametros['puntuarLaMozo'];
        $puntuarLaCocinero = $parametros['puntuarLaCocinero'];
        $experiencia = $parametros['experiencia'];

        $pedido = Pedido::obtenerPedidoId($numPedido);
        $mesa = Mesa::obtenerMesaSegunMesa($numMesa);

        $mesa->puntuarLaMesa = $puntuarLaMesa;
        $mesa->puntuarLaRestaurante = $puntuarLaRestaurante;
        $mesa->puntuarLaMozo = $puntuarLaMozo;
        $mesa->puntuarLaCocinero = $puntuarLaCocinero;
        $mesa->experiencia = $experiencia;

        Mesa::modificarMesa($mesa);

        $payload = json_encode(array("mesa" => $mesa, "pedido" => $pedido));
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