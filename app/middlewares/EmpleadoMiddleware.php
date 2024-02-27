<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once './models/Empleado.php';
require_once './models/Platos.php';

class EmpleadoMiddleware {
    public function ValidarAcceso(Request $request, RequestHandler $handler) : ResponseMW 
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $esValido = false;
    
        try {
            AutentificadorJWT::verificarToken($token);
            $dato = AutentificadorJWT::ObtenerData($token);
            $empleado = Empleado::obtenerEmpleadoSegunNombre($dato);

            if (!empty($empleado) && $empleado !== false) {
                $esValido = true;
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("JWV NO Valido (validar Bearer Token)" . $e);
        }
    
        if ($esValido) {
            $response = $handler->handle($request);
        } else { 
            $response = new ResponseMW();
            $response->getBody()->write("User NO Valido (validar Bear)");
        }
        
        return $response;
    }
    public function ValidarSoloAdmin(Request $request, RequestHandler $handler) : ResponseMW 
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $response = new ResponseMW();
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::obtenerEmpleadoSegunNombre($data);

        if (!empty($empleado) || $empleado !== false)  {
            if (strcmp($empleado->rol, 'admin') === 0) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("Acceso no valido NO es ADMIN");
            }
        } else {
            $response->getBody()->write("No se encotró este usuario");
        }
        
        return $response;
    }
    public function ValidarSoloMoso(Request $request, RequestHandler $handler) : ResponseMW 
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $response = new ResponseMW();
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::obtenerEmpleadoSegunNombre($data);

        if (!empty($empleado) && $empleado !== false) {
            if (strcmp($empleado->rol, 'mozo') === 0) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("Acceso no valido NO es puede ser MOZO");
            }
        } else {
            $response->getBody()->write("No se encotró este usuario");
        }

        return $response;
    }
    public function ValidarQueSeaDelMismoSectorQueSeQuiereModificar(Request $request, RequestHandler $handler) : ResponseMW 
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $response = new ResponseMW();
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::obtenerEmpleadoSegunNombre($data);

        if (!empty($empleado) && $empleado !== false) {
            $parametros = $request->getQueryParams();
            $tipo = $parametros['tipo'];
            $rol = Empleado::RolSegunTipoDeSectorDelEmpleado($tipo);

            if (strcmp($rol, $empleado->rol) === 0) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("No puedes modificar " . $tipo . " si no tienes sus permisos. (modificar Bearer Token al correcto)");
            }
        } else {
            $response->getBody()->write("No se encotró este usuario");
        }
        return $response;
    }
    public function ValidarQueSeaDelMismoRolQueSeQuiereModificar(Request $request, RequestHandler $handler) : ResponseMW 
    {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $response = new ResponseMW();
        $data = AutentificadorJWT::ObtenerData($token);
        $empleado = Empleado::obtenerEmpleadoSegunNombre($data);

        if (!empty($empleado) && $empleado !== false) {
            $parametros = $request->getQueryParams();
            $rol = $parametros['rol'];

            if (strcmp($rol, $empleado->rol) === 0) {
                $response = $handler->handle($request);
            } else {
                $response->getBody()->write("No puedes modificar " . $rol . " si no tienes sus permisos. (modificar Bearer Token al correcto)");
            }
        } else {
            $response->getBody()->write("No se encotró este usuario");
        }
        return $response;
    }
    public function EmpleadoDisponible(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getParsedBody();
            $idEmpelado = $parametros['moso'];

            $empleado = Empleado::obtenerEmpleado($idEmpelado);
            $response = new ResponseMW();

            if (!empty($empleado) && $empleado !== false && $empleado !== []) {
                if (strcmp($empleado->estado, "desocupado") === 0) {
                    $response = $handler->handle($request);
                } else {
                    $response->getBody()->write("El empleado NO está desocupado");
                }
            } else {
                $response->getBody()->write("No se encontró el empleado con el ID: " . json_encode($idEmpelado));
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    public function EmpleadoEsDelTipoDePedido(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getParsedBody();
            $idEmpelado = $parametros['moso'];
            $nombreDelPlato = $parametros['nombre'];

            $plato = Platos::obtenerPlatoNombre($nombreDelPlato);
            $moso = Empleado::obtenerEmpleado($idEmpelado);

            $response = new ResponseMW();
            if (!empty($plato) && !empty($moso)) {
                $tipo = Empleado::TipoDeRolEegunEmpleado($moso->rol);
                if (strcmp($plato->tipo, $tipo) === 0) {
                    $response = $handler->handle($request);
                }
            } else {
                $response->getBody()->write("El moso " . json_encode($idEmpelado) . " no puede traerte ese plato porque no està en su Area</br>*Elija otro moso*");
            }
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Algo Falló: " .  json_encode($e));
        }
        return $response;
    }
    
}