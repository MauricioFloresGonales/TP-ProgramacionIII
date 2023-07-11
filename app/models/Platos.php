<?php

class Platos
{
    public $id;
    public $tipo;
    public $nombre;
    public $precio;
    public $timepoEstimado;

    public function crearPlato()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO platos (tipo, nombre, precio, timepoEstimado) VALUES (:tipo, :nombre, :precio, :timepoEstimado)");
        $consulta->bindValue(':tipo', $this->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, nombre, precio, timepoEstimado FROM platos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Platos');
    }
    public static function obtenerPlatoId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, nombre, precio, timepoEstimado FROM platos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Platps');
    }
    public static function obtenerPlatoNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, tipo, nombre, precio, timepoEstimado FROM platos WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Platos');
    }

    public static function modificarPlatos($dataPlato)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE platos SET tipo = :tipo, nombre = :nombre, precio = :precio, timepoEstimado = :timepoEstimado WHERE id = :id");
        $consulta->bindValue(':tipo', $dataPlato->tipo, PDO::PARAM_STR);
        $consulta->bindValue(':nombre', $dataPlato->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $dataPlato->precio, PDO::PARAM_STR);
        $consulta->bindValue(':timepoEstimado', $dataPlato->timepoEstimado, PDO::PARAM_STR);
        $consulta->bindValue(':id', $dataPlato->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPlato($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM platos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}