<?php
require_once './models/Cliente.php';
require_once './models/Encuesta.php';

class ClienteController extends Cliente
{
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $mesa = $parametros['mesa'];

        $cliente = new Cliente();
        $cliente->nombre = $nombre;
        $cliente->mesa = $mesa;
        //DB
        $idClienteCreado = $cliente->crearCliente();
        $cliente->id = $idClienteCreado;

        $mesa = Mesa::obtenerMesaSegunMesa($mesa);
        $mesa->cliente = $cliente->id;
        $mesa->estado = "cliente esperando atencion";
        $mesa->totalDeLaCuenta = 0;
        $mesa->pedidos = null;
        $mesa->totalDeLaCuenta = 0;
        $mesa->foto = null;
        
        //DB
        Mesa::modificarMesa($mesa);

        $payload = json_encode(array("cliente" => $cliente, "mesaAsignada" => $mesa));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function CompletarEncuesta($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idCliente = $parametros['cliente'];
        $puntuarLaMesa = $parametros['puntuarLaMesa'];
        $puntuarElRestaurante = $parametros['puntuarElRestaurante'];
        $puntuarLaMozo = $parametros['puntuarLaMozo'];
        $puntuarLaCocinero = $parametros['puntuarLaCocinero'];
        $experiencia = $parametros['experiencia'];

        $cliente = Cliente::obtenerClienteId($idCliente);
        $cuenta = Cuenta::obtenerCuentaId($cliente->relacionPedido);

        $encuesta = new Encuesta();
        $encuesta->numMesa = (int)$cliente->mesa;
        $encuesta->cliente = $idCliente;
        $encuesta->pedidos = $cliente->pedidosPendientes;
        $encuesta->totalDeLaCuenta = $cuenta->totalAPagar;
        $encuesta->puntuarLaMesa = (int)$puntuarLaMesa;
        $encuesta->puntuarElRestaurante = (int)$puntuarElRestaurante;
        $encuesta->puntuarLaMozo = (int)$puntuarLaMozo;
        $encuesta->puntuarLaCocinero = (int)$puntuarLaCocinero;
        $encuesta->experiencia = $experiencia;
        $encuesta->fecha = date("Y-m-d");
        
        //DB
        $encuesta->crearEncuesta();

        $payload = json_encode(array("Encuesta" => $encuesta));
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