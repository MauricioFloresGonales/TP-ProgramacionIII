<?php
require_once './models/Pedido.php';
require_once './models/Platos.php';
require_once './models/Empleado.php';
require_once './models/Mesa.php';
require_once './models/Archivos.php';

class PedidoController extends Pedido
{
    public function CargarUno($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $nombre = $parametros['nombre'];
        $idEmpleado = $parametros['moso'];
        $cliente = $parametros['cliente'];
        $cantidad = (int)$parametros['cantidad'];

        $pedido = new Pedido();
        $pedido->nombre = $nombre;
        $pedido->moso = $idEmpleado;
        $pedido->cliente = $cliente;
        $pedido->estado = "en preparación";
        $plato =  Platos::obtenerPlatoNombre($nombre);
        $pedido->tipoDePedido = $plato->tipo;
        $pedido->cantidad = $cantidad;
        $pedido->precio = (float)$plato->precio;
        $pedido->tiempo = $plato->timepoEstimado;

        
        $mesa = Mesa::obtenerMesaSegunCliente($cliente);
        $pedido->numeroMesa = $mesa->numMesa;
        $idCreado= $pedido->crearPedido();
        $pedido->id = $idCreado;
        
        // Ver porque no suma el valor de un nuevo pedido a la mesa sabiendo que ya hizo un pedido antes
        $empleado = Empleado::obtenerEmpleado($idEmpleado);
        $empleado->estado = "atendiendo";
        $empleado->nombrePedido = $nombre;
        $empleado->cantidad = (int)$cantidad;
        $empleado->pedidoAsignado = $idCreado;
        Empleado::modificarEmpleado($empleado);

        $mesa->estado = "cliente esperando pedido";
        $mesa->pedidos = $mesa->pedidos .  $pedido->nombre;
        $precioTotal = $pedido->precio * $cantidad;
        $suma = $mesa->totalDeLaCuenta + $precioTotal;
        $mesa->totalDeLaCuenta = $suma;

        $payload = json_encode(array(
            "empleado" => $empleado,
            "pedido" => $pedido,
            "plato" => $plato,
            "mesa" => $mesa
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function TraerUno($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $numMesa = $parametros['mesa'];
        $numPedido = $parametros['pedido'];
        $pedidos = Pedido::obtenerPedidoIdYNumMesa($numPedido, $numMesa);

        $payload = json_encode($pedidos);
        
        if ($payload === "false") {
            $response->getBody()->write("No se encotró ningun resultado");
        } else {
            $response->getBody()->write($payload);
        }
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function TraerTodo($request, $response, $args) {
        $pedidos = Pedido::obtenerTodos();

        $payload = json_encode($pedidos);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerListaDeUnPedidoYTiempo($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $nombre = $parametros['nombre'];
        $pedidos = Pedido::obtenerTodosPorNombre($nombre);

        $payload = json_encode($pedidos);
        $response->getBody()->write($payload);
        
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function SacarFoto($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idEmpleado = $parametros['moso'];
        $idCliente = $parametros['cliente'];
        $idPedido = $parametros['pedido'];
        $foto = $_FILES["foto"]["name"];

        //buscar mesa
        $cliente = Cliente::obtenerClienteId($idCliente);
        $mesa = Mesa::obtenerMesaSegunMesa($cliente->mesa);
        $pedido = Pedido::obtenerPedidoId($idPedido);
        $empleado = Empleado::obtenerEmpleado($idEmpleado);
        
        $nombreFoto = 'Foto_mesa-' . $mesa->id . '_cliente-' . $cliente->id . '_pedido-' . $pedido->id;
        $carpeta = "FotoClientes_" . $empleado->nombre . '_' . $empleado->id;
        Archivos::SubirFoto($carpeta, $nombreFoto);
        $cliente->foto = $foto;
        $mesa->foto = $foto;
        $pedido->fotoMesa = $foto;
        $mesa->foto = $foto;

        Cliente::modificarCliente($cliente);
        Mesa::modificarMesa($mesa);
        Pedido::modificarPedido($pedido);
        Empleado::modificarEmpleado($empleado);
        
        $payload = json_encode(array(
            "foto" => $foto,
            "cliente" => $cliente,
            "mesa" => $mesa,
            "pedido" => $pedido,
            "empleado" => $empleado
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}