<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response as ResponseMW;

require_once './models/Archivos.php';
require_once './models/Armamento.php';
require_once './models/Usuario.php';

class LogsMiddleware {
    public function EscribirLog(Request $request, RequestHandler $handler) : ResponseMW 
    {
        try {
            $parametros = $request->getParsedBody();
            $idArma = $parametros['idArma'];
            $header = $request->getHeaderLine('Authorization');
            $token = trim(explode("Bearer", $header)[1]);
            AutentificadorJWT::verificarToken($token);
            $datos = AutentificadorJWT::ObtenerData($token);
            $usuario = Usuario::obtenerUsuario($datos->usuario);
            $armamento = Armamento::obtenerArmamentoId($idArma);
            $titulo = "Logs_venta_" . $datos->usuario . "_" . date("d-m-Y");
            $datoArchivo = array(
                'dato' => [
                    'usuarioId' => $usuario->id, 
                    'usuario' => $usuario->mail, 
                    'armamento' =>$armamento
                ]);

            Archivos::Escribir($titulo, $datoArchivo);
            $response = new ResponseMW();
            $response->getBody()->write("Armamento Borrado - " . $armamento->nombre);
        } catch (Exception $e) {
            $response = new ResponseMW();
            $response->getBody()->write("Error al borrar intentar borrar el Armamento</br>", $e);
        }
        
        return $response;
    }
    
}