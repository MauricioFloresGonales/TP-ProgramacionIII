<?php
require_once './models/Pedido.php';
require_once './models/Platos.php';
require_once './models/Empleado.php';
require_once './models/Mesa.php';
require_once './models/Archivos.php';

class EjerciciosController extends Cliente
{
    public function Ejer14($request, $response, $args) {
        $payload = json_encode("Datos insuficientes en la DB para validar los datos");
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function Ejer15($request, $response, $args) {
        $payload = json_encode("Datos insuficientes en la DB para validar los datos");
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function Ejer16($request, $response, $args) {
        Archivos::SubirPDFDelLogo();
        
        $payload = json_encode("Logo entregado");
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
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
        $resultado = [];
        
        foreach ($platosOrden as $plato) {
            $cantidad = Pedido::obtenerCantPedidosPorTipoDePedido($plato["tipo"]);
            array_push($resultado, array(
                "tipo" => $plato["tipo"],
                "catidad" => $cantidad
            ));
        }

        $payload = json_encode($resultado);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function Ejer20($request, $response, $args) {
        $empleados = Empleado::obtenerTodos();
        $resultado = [];
        foreach ($empleados as $empleado) {
            array_push($resultado, array(
                "nombre" => $empleado->nombre,
                "rol" => $empleado->rol,
                "ultimaSesion" => $empleado->ultimaSesion
            ));
        }
        $payload = json_encode($resultado);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function Ejer21($request, $response, $args) {
        $cuentaOrdenadas = Cuenta::ObtenerCuentaConMasGastos();
        $resultado = [];
        foreach ($cuentaOrdenadas as $cuenta) {
            array_push($resultado, array(
                "numMesa" => $cuenta->numMesa,
                "moso" => $cuenta->moso,
                "totalAPagar" => $cuenta->totalAPagar,
            ));
        }
        $payload = json_encode($resultado);
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
    public function Ejer22($request, $response, $args) {
        $parametros = $request->getQueryParams();
        $numMesa = $parametros['numMesa'];
        $fechaDe = $parametros['fechaDe'];
        $fechaHasta = $parametros['fechaHasta'];

        $encuestas = Encuesta::obtenerEncuestaEntreFechas($numMesa, $fechaDe, $fechaHasta);
        $encuestasTotal = Encuesta::obtenerEncuestaEntreFechasTotalGastado($numMesa, $fechaDe, $fechaHasta);

        $auxEncuesta = [];
        foreach ($encuestas as $encuesta) {
           array_push($auxEncuesta, array(
            "numMesa" => $encuesta["numMesa"],
            "fecha" => $encuesta["fecha"],
            "totalDeLaCuenta" => $encuesta["totalDeLaCuenta"],
           ));
        }

        $payload = json_encode(
            array(
            "encuestasTotal" => $encuestasTotal, 
            "encuestas" => $auxEncuesta
        ));
        $response->getBody()->write($payload);
        return $response
        ->withHeader('Content-Type', 'application/json');
    }
}