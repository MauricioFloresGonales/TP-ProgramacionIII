<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once './models/Mesa.php';
class ClientesMiddleware {
    public function ClienteExiste(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getParsedBody();
            $idCliente = $parametros['cliente'];
            $cliente = Cliente::obtenerClienteId($idCliente);
            $response = new ResponseMW();
            if (!empty($cliente) && $cliente !== false && $cliente !== []) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("El Cliente NO existe - corregir los parametros del Body");
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
}