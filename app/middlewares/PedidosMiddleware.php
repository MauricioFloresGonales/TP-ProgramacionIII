<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once './models/Pedido.php';
require_once './models/Platos.php';
class PedidosMiddleware {
    public function ExisteElPlato(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getParsedBody();
            $nombre = $parametros['nombre'];

            $plato = Platos::obtenerPlatoNombre($nombre);
            $response = new ResponseMW();
            if (!empty($plato)) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("No hay un plato con ese nombre");
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    
}