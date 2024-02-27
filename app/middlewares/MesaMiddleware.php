<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once './models/Mesa.php';
class MesaMiddleware {
    public function MesasVacias(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $mesas = Mesa::obtenerMesasVacias();
            $response = new ResponseMW();
            if (!empty($mesas) && $mesas!== false && $mesas!== []) {
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
    public function MesaExistente(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getParsedBody();
            $numMesa = $parametros['mesa'];
            $mesa = Mesa::obtenerMesaSegunMesa($numMesa);
            $response = new ResponseMW();

            if (!empty($mesa) && $mesa!== false && $mesa!== []) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("No se encontró la mesa numero: " . json_encode($numMesa));
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    public function MesaLibre(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getParsedBody();
            $numMesa = $parametros['mesa'];
            $mesa = Mesa::obtenerMesaSegunMesa($numMesa);
            $response = new ResponseMW();

            if (!empty($mesa) && $mesa!== false && $mesa!== []) {
                if (strcmp($mesa->estado, "vacia") === 0) {
                    $response = $handler->handle($request);
                }
                $response->getBody()->write("La mesa numero " . json_encode($numMesa) . " no se encuetra libre");
            } else {
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    public function ValidarQueLaMesaExistaSegunNumero(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getQueryParams();
            $numMesa = $parametros['numMesa'];
            $mesa = Mesa::obtenerMesaSegunMesa($numMesa);
            $response = new ResponseMW();

            if (!empty($mesa)) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("No se encontró la mesa numero: " . json_encode($numMesa));
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    public function MesasParaAtender(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $mesas = Mesa::obtenerTodos();
            $response = new ResponseMW();

            if (!empty($mesas)) {
                $loEncontro = false;
                foreach ($mesas as $mesa) {
                    if (strcmp($mesa->estado, "cliente esperando atencion") === 0) {
                        $response = $handler->handle($request);
                        $loEncontro = true;
                    }
                }
                if ($loEncontro == false) {
                    $response->getBody()->write("No hay mesa para ser atendidas");
                }
            } else {
                $response->getBody()->write("No se encontraron mesas");
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    public function MesaNecesitaAtencion(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getParsedBody();
            $idCliente = $parametros['cliente'];
            $mesa = Mesa::obtenerMesaSegunCliente($idCliente);
            $response = new ResponseMW();
            if (!empty($mesa)) {
                
                if (strcmp($mesa->estado, "cliente esperando atencion") === 0) {
                    $response = $handler->handle($request);
                } else {
                    $response->getBody()->write("La mesa numero " . json_encode($mesa->numMesa) . " no necesita ser atendida");
                }
            } else {
                $response->getBody()->write("No se encontraron mesas");
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    
}