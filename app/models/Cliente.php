<?php

class Cliente
{
    public $id;
    public $nombre;
    public $pedidosPendientes;
    public $foto;
    public $relacionPedido;
    public $mesa;

    public function crearCliente()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO clientes (nombre, pedidosPendientes, foto, relacionPedido, mesa) VALUES (:nombre, :pedidosPendientes, :foto, :relacionPedido, :mesa)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pedidosPendientes', $this->pedidosPendientes, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':relacionPedido', $this->relacionPedido, PDO::PARAM_INT);
        $consulta->bindValue(':mesa', $this->mesa, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, pedidosPendientes, foto, relacionPedido, mesa FROM clientes");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cliente');
    }
    public static function obtenerClienteId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, pedidosPendientes, foto, relacionPedido, mesa FROM clientes WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Cliente');
    }
    public static function obtenerClienteNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id, nombre, pedidosPendientes, foto, relacionPedido, mesa FROM clientes WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Cliente');
    }

    public static function modificarCliente($dataUsuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE clientes SET nombre = :nombre, pedidosPendientes = :pedidosPendientes, foto = :foto, relacionPedido = :relacionPedido, mesa = :mesa WHERE id = :id");
        $consulta->bindValue(':nombre', $dataUsuario->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':pedidosPendientes', $dataUsuario->pedidosPendientes, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $dataUsuario->foto, PDO::PARAM_STR);
        $consulta->bindValue(':relacionPedido', $dataUsuario->relacionPedido, PDO::PARAM_INT);
        $consulta->bindValue(':mesa', $dataUsuario->mesa, PDO::PARAM_STR);
        $consulta->bindValue(':id', $dataUsuario->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarCliente($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM clientes WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}