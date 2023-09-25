<?php
require_once './models/Pedido.php';
require_once './models/Platos.php';
require_once './models/Empleado.php';
require_once './models/Mesa.php';
require_once './models/Archivos.php';

class EjerciciosController extends Cliente
{
    public function Ejer17($request, $response, $args) {
        $candyBar = Pedido::obtenerCantPedidosPorTipoDePedido('candyBar');
        $cocina = Pedido::obtenerCantPedidosPorTipoDePedido('cocina');
        $choperas = Pedido::obtenerCantPedidosPorTipoDePedido('chopera');
        $barra = Pedido::obtenerCantPedidosPorTipoDePedido('barra');
        $payload = json_encode(
            array(
            "candyBar" => $candyBar, 
            "cocina" => $cocina, 
            "choperas" => $choperas, 
            "barra" => $barra
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function Ejer18($request, $response, $args) {
        $candyBar = Pedido::obtenerCantPedidosPorTipoDePedido('candyBar');
        $cocina = Pedido::obtenerCantPedidosPorTipoDePedido('cocina');
        $choperas = Pedido::obtenerCantPedidosPorTipoDePedido('chopera');
        $barra = Pedido::obtenerCantPedidosPorTipoDePedido('barra');

        $bartender = Empleado::obtenerEmpleadoRol('bartender');
        $cervecero = Empleado::obtenerEmpleadoRol('cervecero');
        $mozo = Empleado::obtenerEmpleadoRol('mozo');
        $cocinero = Empleado::obtenerEmpleadoRol('cocinero');

        $sectorCandyBar = array("empleados" => $mozo, "pedidos" => $candyBar);
        $sectorBarra = array("empleados" => $bartender, "pedidos" => $barra);
        $sectorChopera = array("empleados" => $cervecero, "pedidos" => $choperas);
        $sectorCocina = array("empleados" => $cocinero, "pedidos" => $cocina);

        $payload = json_encode(
            array(
            "candyBar" => $sectorCandyBar, 
            "cocina" => $sectorCocina, 
            "choperas" => $sectorChopera, 
            "barra" => $sectorBarra
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function Ejer19($request, $response, $args) {
        $platosOrden = Pedido::obtenerMasVendido();
        // poner montos
        $payload = json_encode($platosOrden);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function Ejer20($request, $response, $args) {
        // hacel login usuario;
        $payload = "ocurrio un proble consulte en 24hs";
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function Ejer21($request, $response, $args) {
        $mesas = Mesa::obtenerMesasConMasGastos();
        $payload = json_encode($mesas);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function Ejer22($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $fechaDe = $parametros['fechaDe'];
        $fechaHasta = $parametros['fechaHasta'];

        $mesas = Mesa::obtenerMesasEntreFechas($fechaDe, $fechaHasta);
        $mesasTotal = Mesa::obtenerMesasEntreFechasTotalGastado($fechaDe, $fechaHasta);

        $payload = json_encode(
            array(
            "mesasTotal" => $mesasTotal, 
            "mesas" => $mesas
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}