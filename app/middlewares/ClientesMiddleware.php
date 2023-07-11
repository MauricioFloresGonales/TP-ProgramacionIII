<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once './models/Mesa.php';
class ClientesMiddleware {
    public function ClienteExiste(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $mesas = Mesa::obtenerMesasVacias();
            $response = new ResponseMW();
            if (!empty($mesas)) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("No hay mesas Vacias disponibles");
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    
}