<?php

class Pedido
{
    public $id;
    public $nombre;
    public $tipoDePedido;
    public $cantidad;
    public $precio;
    public $estado;
    public $tiempo;
    public $moso;
    public $cliente;

    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO pedidos (
                nombre,
                tipoDePedido,
                cantidad,
                precio,
                estado, 
                tiempo, 
                moso,
                cliente) 
            VALUES (
                :nombre,
                :tipoDePedido,
                :cantidad,
                :precio,
                :estado,
                :tiempo,
                :moso,
                :cliente)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDePedido', $this->tipoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $this->cantidad, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo', $this->tiempo, PDO::PARAM_STR);
        $consulta->bindValue(':moso', $this->moso, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_INT);
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
                tipoDePedido,
                cantidad,
                precio,
                estado, 
                tiempo, 
                moso,
                cliente
            FROM pedidos");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerTodosPorNombre($nombre)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT *
            FROM pedidos
            WHERE nombre = :nombre");
        $consulta->bindValue(':nombre', $nombre, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerPedidoId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
                id,
                nombre,
                tipoDePedido,
                cantidad,
                precio,
                estado, 
                tiempo, 
                moso,
                cliente
            FROM pedidos
            WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerPorEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
                id,
                nombre,
                tipoDePedido,
                cantidad,
                precio,
                estado, 
                tiempo, 
                moso,
                cliente
            FROM pedidos
            WHERE estado = :estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerPorTipoYEstado($tipoDePedido, $estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
                id,
                nombre,
                tipoDePedido,
                cantidad,
                precio,
                estado, 
                tiempo, 
                moso,
                cliente
            FROM pedidos
            WHERE tipoDePedido = :tipoDePedido
            AND estado = :estado");
        $consulta->bindValue(':tipoDePedido', $tipoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }
    public static function obtenerPedidoMoso($moso)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
            id,
            nombre,
            tipoDePedido,
            cantidad,
            precio,
            estado,  
            tiempo, 
            moso,
            cliente
            FROM pedidos 
            WHERE moso = :moso");
        $consulta->bindValue(':moso', $moso, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }

    public static function obtenerPedidoTipoDePedido($tipoDePedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
            id,
            nombre,
            tipoDePedido,
            cantidad,
            precio,
            estado,  
            tiempo, 
            moso,
            cliente
            FROM pedidos 
            WHERE tipoDePedido = :tipoDePedido");
        $consulta->bindValue(':tipoDePedido', $tipoDePedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Pedido');
    }
    public static function obtenerCantPedidosPorTipoDePedido($tipoDePedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
            id,
            nombre,
            tipoDePedido,
            cantidad,
            precio,
            estado,  
            tiempo, 
            moso,
            cliente
            FROM pedidos 
            WHERE tipoDePedido = :tipoDePedido");
        $consulta->bindValue(':tipoDePedido', $tipoDePedido, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->rowCount();
    }
    public static function obtenerMasVendido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT tipo
            FROM platos 
            JOIN pedidos 
            GROUP BY tipo
            ORDER BY tipo ASC"
            );
        
        $consulta->execute();

        return  $consulta->fetchAll();
    }

    public static function modificarPedido($dataUsuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE pedidos 
            SET 
                nombre = :nombre,
                tipoDePedido = :tipoDePedido,
                cantidad = :cantidad,
                precio = :precio,
                estado = :estado, 
                tiempo = :tiempo, 
                moso = :moso,
                cliente = :cliente
            WHERE id = :id");
        $consulta->bindValue(':nombre', $dataUsuario->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tipoDePedido', $dataUsuario->tipoDePedido, PDO::PARAM_STR);
        $consulta->bindValue(':cantidad', $dataUsuario->cantidad, PDO::PARAM_INT);
        $consulta->bindValue(':precio', $dataUsuario->precio, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $dataUsuario->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempo', $dataUsuario->tiempo, PDO::PARAM_STR);
        $consulta->bindValue(':moso', $dataUsuario->moso, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $dataUsuario->cliente, PDO::PARAM_INT);
        $consulta->bindValue(':id', $dataUsuario->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarPedido($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM pedidos WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function CrearNewPedido($nombre, $idEmpleado, $cliente, $cantidad) {
        $newPedido = new Pedido();
        $newPedido->nombre = $nombre;
        $newPedido->moso = $idEmpleado;
        $newPedido->cliente = $cliente;
        $newPedido->estado = "nuevo";

        $plato =  Platos::obtenerPlatoNombre($nombre);
        $newPedido->tipoDePedido = $plato->tipo;
        $newPedido->cantidad = (int)$cantidad;
        $newPedido->precio = (float)$plato->precio * (int)$cantidad;
        $newPedido->tiempo = $plato->timepoEstimado;
        
        //DB
        $idCreado= $newPedido->crearPedido();
        $newPedido->id = $idCreado;
        return $newPedido;
    }
}