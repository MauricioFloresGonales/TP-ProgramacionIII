<?php

class Empleado
{
    public $id;
    public $nombre;
    public $rol;
    public $estado;
    public $pedidoAsignado;
    public $ultimaSesion;
    public $contrato;
    public $contrasenia;
    public $token;

    public function crearEmpleado()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO empleados (nombre, rol, estado, pedidoAsignado, ultimaSesion, contrato, contrasenia) VALUES (:nombre, :rol, :estado, :pedidoAsignado, :ultimaSesion, :contrato, :contrasenia)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $this->rol, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':pedidoAsignado', $this->pedidoAsignado, PDO::PARAM_STR);
        $consulta->bindValue(':ultimaSesion', $this->ultimaSesion, PDO::PARAM_STR);
        $consulta->bindValue(':contrato', $this->contrato);
        $consulta->bindValue(':contrasenia', $this->contrasenia);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
                id,
                nombre,
                rol,
                estado,
                pedidoAsignado,
                ultimaSesion,
                contrato,
                contrasenia,
                token 
            FROM empleados");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }
    public static function obtenerEmpleado($idEmpleado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, rol, estado, pedidoAsignado, ultimaSesion, contrato FROM empleados WHERE id = :idEmpleado");
        $consulta->bindValue(':idEmpleado', $idEmpleado, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Empleado');
    }
    public static function obtenerEmpleadoSegunNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, rol, estado, pedidoAsignado, ultimaSesion, contrato, contrasenia, token FROM empleados WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Empleado');
    }
    public static function obtenerEmpleadoRol($rol)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, rol, estado, pedidoAsignado, ultimaSesion, contrato FROM empleados WHERE rol = :rol");
        $consulta->bindValue(':rol', $rol, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Empleado');
    }
    public static function modificarEmpleado($dataUsuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE empleados SET nombre = :nombre, rol = :rol, estado = :estado, pedidoAsignado = :pedidoAsignado, ultimaSesion = :ultimaSesion, contrato = :contrato, contrasenia = :contrasenia, token = :token WHERE id = :id");
        $consulta->bindValue(':nombre', $dataUsuario->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':rol', $dataUsuario->rol, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $dataUsuario->estado, PDO::PARAM_STR);
        $consulta->bindValue(':pedidoAsignado', $dataUsuario->pedidoAsignado, PDO::PARAM_STR);
        $consulta->bindValue(':ultimaSesion', $dataUsuario->ultimaSesion, PDO::PARAM_STR);
        $consulta->bindValue(':contrato', $dataUsuario->contrato, PDO::PARAM_STR);
        $consulta->bindValue(':contrasenia', $dataUsuario->contrasenia, PDO::PARAM_STR);
        $consulta->bindValue(':token', $dataUsuario->token, PDO::PARAM_STR);
        $consulta->bindValue(':id', $dataUsuario->id, PDO::PARAM_INT);
        $consulta->execute();
    }
    public static function borrarEmpleado($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM empleados WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
    public static function TipoDeRolEegunEmpleado($rol) {
        switch ($rol) {
            case 'bartender':
                return 'barra';
            case 'cervecero':
                return 'chopera';
            case 'mozo':
                return 'candyBar';
            default:
                return 'cocina';
        }
    }
    public static function RolSegunTipoDeSectorDelEmpleado($sector) {
        switch ($sector) {
            case 'barra':
                return 'bartender';
            case 'chopera':
                return 'cervecero';
            case 'candyBar':
                return 'mozo';
            default:
                return 'cocinero';
        }
    }
}