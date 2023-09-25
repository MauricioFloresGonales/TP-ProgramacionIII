<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class jwtController {
    public static function crearToken(Request $request, Response $response) {    
        $parametros = $request->getParsedBody();
    
        $empleado = $parametros['empleado'];
    
        $datos = array('empleado' => $empleado);
    
        $token = AutentificadorJWT::CrearToken($datos);
        $payload = json_encode(array('jwt' => $token));
    
        $response->getBody()->write($payload);
        return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public static function devolverPayLoad(Request $request, Response $response) {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
    
        try {
          $payload = json_encode(array('payload' => AutentificadorJWT::ObtenerPayLoad($token)));
        } catch (Exception $e) {
          $payload = json_encode(array('error' => $e->getMessage()));
        }
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public static function devolverDatos(Request $request, Response $response) {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
    
        try {
          $payload = json_encode(array('datos' => AutentificadorJWT::ObtenerData($token)));
        } catch (Exception $e) {
          $payload = json_encode(array('error' => $e->getMessage()));
        }
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public static function verificarToken(Request $request, Response $response) {
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $esValido = false;
    
        try {
          AutentificadorJWT::verificarToken($token);
          $esValido = true;
        } catch (Exception $e) {
          $payload = json_encode(array('error' => $e->getMessage()));
        }
    
        if ($esValido) {
          $payload = json_encode(array('valid' => $esValido));
        }
    
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
}