<?php
require_once './models/Pedido.php';
require_once './models/Platos.php';
require_once './models/Empleado.php';
require_once './models/Mesa.php';
require_once './models/Archivos.php';

class EjerciciosController extends Cliente
{
    public function Ejer17($request, $response, $args) {
        $candyBar = Pedido::obtenerPedidoTipoDePedido('candyBar');
        $cocina = Pedido::obtenerPedidoTipoDePedido('cocina');
        $choperas = Pedido::obtenerPedidoTipoDePedido('chopera');
        $barra = Pedido::obtenerPedidoTipoDePedido('barra');
        $contarCandyBar = !empty($candyBar) ? count($candyBar) : 0;
        $contarCocina = !empty($cocina) ? count($cocina) : 0;
        $contarChoperas = !empty($choperas) ? count($choperas) : 0;
        $contarBarra = !empty($barra) ? count($barra) : 0;
        $payload = json_encode(
            array(
            "candyBar" => $contarCandyBar, 
            "cocina" => $contarCocina, 
            "choperas" => $contarChoperas, 
            "barra" => $contarBarra
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }

    public function Ejer18($request, $response, $args) {
        $candyBar = Pedido::obtenerPedidoTipoDePedido('candyBar');
        $cocina = Pedido::obtenerPedidoTipoDePedido('cocina');
        $choperas = Pedido::obtenerPedidoTipoDePedido('chopera');
        $barra = Pedido::obtenerPedidoTipoDePedido('barra');
        $objetosCandyBar = !empty($candyBar) ? $candyBar : 0;
        $objetosCocina = !empty($cocina) ? $cocina : 0;
        $objetosChoperas = !empty($choperas) ? $choperas : 0;
        $objetosBarra = !empty($barra) ? $barra : 0;

        $bartender = Empleado::obtenerEmpleadoRol('bartender');
        $cervecero = Empleado::obtenerEmpleadoRol('cervecero');
        $mozo = Empleado::obtenerEmpleadoRol('mozo');
        $cocinero = Empleado::obtenerEmpleadoRol('cocinero');

        $sectorCandyBar = array("empleados" => $mozo, "pedidos" => $objetosCandyBar);
        $sectorBarra = array("empleados" => $bartender, "pedidos" => $objetosBarra);
        $sectorChopera = array("empleados" => $cervecero, "pedidos" => $objetosChoperas);
        $sectorCocina = array("empleados" => $cocinero, "pedidos" => $objetosCocina);

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