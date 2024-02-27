<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once './models/Pedido.php';
require_once './models/Platos.php';
class PedidosMiddleware {
    public function ExisteElPlatoPorId(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getQueryParams();
            $numPedido = $parametros['numPedido'];

            $pedido = Pedido::obtenerPedidoId($numPedido);
            $response = new ResponseMW();
            if (!empty($pedido) && $pedido !== false && $pedido !== []) {
                
                $response->getBody()->write(json_encode(array("asdasdas", $pedido)));
                if (($pedido[0]->id === (int)$numPedido)) {
                    $response = $handler->handle($request);
                } else {
                    $response->getBody()->write("No hay un pedido con ese ID");
                }
                
            } else {
                $response->getBody()->write("No hay un pedido con ese ID");
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    public function ExisteElPlato(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getParsedBody();
            $mososPedidos = $parametros['mososPedidos'];
            $response = new ResponseMW();
            $existe = true;

            foreach ($mososPedidos['pedidos'] as $pedido) {
                $plato = Platos::obtenerPlatoNombre($pedido["nombre"]);
                if (empty($plato) || $plato === false || $plato === []) {
                    $existe = false;
                    $response->getBody()->write("No hay un plato con ese nombre: " . $pedido["nombre"]);
                    break;
                }
            }
            if ($existe) {
                $response = $handler->handle($request);
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    public function ValidarQueElUnicoEstadoSeaEnPreparacion(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getQueryParams();
            $estado = $parametros['estado'];
            $response = new ResponseMW();

            if (strcmp($estado, "nuevo") === 0) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("Solo se puede pasar el estado 'nuevo' por parametro en esta request");
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    
}