<?php

use function PHPSTORM_META\map;

require_once './models/Pedido.php';
require_once './models/Cuenta.php';
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
        //$pedido->numeroMesa = $mesa->numMesa;
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
        $numPedido = $parametros['pedido'];
        $pedidos = Pedido::obtenerPedidoId($numPedido);

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

    public function TraerTodoSegunTipoDePedidoYEstado($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $tipo = $parametros['tipo'];
        $estado = $parametros['estado'];
        $pedidos = Pedido::obtenerPorTipoYEstado($tipo, $estado);
        
        if (!is_null($pedidos) && $pedidos !== false) {
            
            $empleadosModificados = [];
            $aux = [];
            foreach ($pedidos as $pedido) {
                $sector = $pedido->tipoDePedido;//chopera || cerveceros
                $rol = Empleado::RolSegunTipoDeSectorDelEmpleado($sector);
                $empleado = Empleado::obtenerEmpleadoRol($rol);
                
                if ($empleado->pedidoAsignado !== "null") {
                    $aux = json_decode($empleado->pedidoAsignado);
                }
                array_push($aux, $pedido->id);
                $empleado->pedidoAsignado = json_encode($aux);
                $pedido->estado = "en preparacion";
                array_push($empleadosModificados, $empleado);
                //DB
                Pedido::modificarPedido($pedido);
                Empleado::modificarEmpleado($empleado);
            }
        }

        $payload = json_encode(array(
            "empleadosModificados" => $empleadosModificados,
            "pedidos" => $pedidos
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerListaDeUnPedidoYTiempo($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $pedidos = [];
        if ($parametros === [] || is_null($parametros['nombre'])) {
            $pedidos = Pedido::obtenerTodos();
        } else {
            $nombre = $parametros['nombre'];
            $pedidos = Pedido::obtenerTodosPorNombre($nombre);
        }
        
        $resultado = [];
        foreach ($pedidos as $pedido) {
            $aux = array(
                "nombre" => $pedido->nombre,
                "tiempo" => $pedido->tiempo . " min",
                "estado" => $pedido->estado,
                "cliente" => $pedido->cliente
            );
            array_push($resultado, $aux);
        }
        $payload = json_encode($resultado);
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function SacarFoto($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $idEmpleado = $parametros['moso'];
        $idCliente = $parametros['cliente'];
        $foto = $_FILES["foto"]["name"];

        //buscar
        $cliente = Cliente::obtenerClienteId($idCliente);
        $mesa = Mesa::obtenerMesaSegunMesa($cliente->mesa);
        $cuenta = Cuenta::obtenerCuentaId($cliente->relacionPedido);
        $empleado = Empleado::obtenerEmpleado($idEmpleado);
        
        $nombreFoto = 'Foto_mesa-' . $mesa->id . '_cliente-' . $cliente->id . '_pedido-' . $cuenta->id;
        $carpeta = "FotoClientes_" . $empleado->nombre . '_' . $empleado->id;
        Archivos::SubirFoto($carpeta, $nombreFoto);
        $cliente->foto = $foto;
        $mesa->foto = $foto;
        $cuenta->foto = $foto;

        Cliente::modificarCliente($cliente);
        Mesa::modificarMesa($mesa);
        Cuenta::modificarCuenta($cuenta);
        
        $payload = json_encode(array(
            "foto" => $foto,
            "cliente" => $cliente,
            "mesa" => $mesa,
            "cuenta" => $cuenta,
            "empleado" => array(
                "nombre" => $empleado->nombre,
                "rol" => $empleado->rol,
            )
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function TomarPedido($request, $response, $args) {
        $parametros = $request->getParsedBody();
        $mososPedidos = $parametros['mososPedidos'];
        $pedidosCreados = [];
        $nombresDePedidos = [];
        $listaDePedidos = [];

        foreach ($mososPedidos['pedidos'] as $pedido) {
            $pedidoCrado = Pedido::CrearNewPedido(
                $pedido['nombre'],
                $mososPedidos['moso'],
                $mososPedidos['cliente'],
                $pedido['cantidad']
            );
            array_push($pedidosCreados, $pedidoCrado);
            array_push($nombresDePedidos, $pedidoCrado->nombre);
            array_push($listaDePedidos, array(
                "nombre" => $pedidoCrado->nombre,
                "cantidad" => $pedidoCrado->cantidad,
                "precioUnitario" => $pedidoCrado->precio / $pedidoCrado->cantidad,
                "precioTotal" => $pedidoCrado->precio
            ));
        }
        $cuentaCreada = Cuenta::CrearNewCuenta($mososPedidos['cliente'], $mososPedidos['moso'], $pedidosCreados);

        $empleado = Empleado::obtenerEmpleado($mososPedidos['moso']);
        $empleado->estado = "atendiendo";
        $empleado->pedidoAsignado = $cuentaCreada->id;
        //DB
        Empleado::modificarEmpleado($empleado);

        $mesa = Mesa::obtenerMesaSegunCliente($mososPedidos['cliente']);
        $mesa->estado = "cliente esperando pedido";
        $mesa->totalDeLaCuenta = $cuentaCreada->totalAPagar;
        $mesa->pedidos = json_encode($nombresDePedidos);
        Mesa::modificarMesa($mesa);

        $cliente = Cliente::obtenerClienteId($mososPedidos['cliente']);
        $cliente->pedidosPendientes = $mesa->pedidos;
        $cliente->relacionPedido = (int)$cuentaCreada->id;
        Cliente::modificarCliente($cliente);

        $cliente->pedidosPendientes = $nombresDePedidos;
        $mesa->pedidos = $nombresDePedidos;

        $payload = json_encode(array(
            "empleado" => $empleado,
            "pedido" => $listaDePedidos,
            "mesa" => $mesa,
            "cliente" => $cliente
        ));
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }
}