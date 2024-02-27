<?php

class Mesa
{
    public $id;
    public $numMesa;
    public $cliente;
    public $pedidos;
    public $estado;
    public $totalDeLaCuenta;
    public $foto;

    public function crearMesa() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (
                numMesa,
                cliente, 
                pedidos, 
                estado,
                totalDeLaCuenta,
                foto
            ) VALUES (
                :numMesa,
                :cliente, 
                :pedidos, 
                :estado,
                :totalDeLaCuenta,
                :foto)");
        $consulta->bindValue(':numMesa', $this->numMesa, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':pedidos', $this->pedidos, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':totalDeLaCuenta', $this->totalDeLaCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    public static function obtenerTodos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT
                id,
                numMesa,
                cliente, 
                pedidos, 
                estado,
                totalDeLaCuenta,
                foto
            FROM mesas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesaSegunMesa($numMesa) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT
                id,
                numMesa,
                cliente, 
                pedidos, 
                estado,
                totalDeLaCuenta,
                foto
            FROM mesas 
            WHERE numMesa = :numMesa");
        $consulta->bindValue(':numMesa', $numMesa, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
    public static function obtenerMesaSegunNumero($numMesa) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT
                id,
                numMesa,
                cliente, 
                pedidos, 
                estado,
                totalDeLaCuenta,
                foto
            FROM mesas 
            WHERE numMesa = :numMesa");
        $consulta->bindValue(':numMesa', $numMesa, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
    public static function obtenerMesaSegunCliente($idCliente) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT
                id,
                numMesa,
                cliente, 
                pedidos, 
                estado,
                totalDeLaCuenta,
                foto
            FROM mesas 
            WHERE cliente = :cliente");
        $consulta->bindValue(':cliente', $idCliente, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
    public static function obtenerMesasVacias() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT
                id,
                numMesa,
                cliente, 
                pedidos, 
                estado,
                totalDeLaCuenta,
                foto
            FROM mesas 
            WHERE estado = 'vacia'");
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
    public static function obtenerPorEstado($estado) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT
                id,
                numMesa,
                cliente, 
                pedidos, 
                estado,
                totalDeLaCuenta,
                foto
            FROM mesas 
            WHERE estado = :estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }

    public static function obtenerMesasConMasGastos() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM mesas ORDER BY totalDeLaCuenta DESC");
        $consulta->execute();

        return $consulta->fetchObject('Mesa');
    }
    public static function obtenerMesasEntreFechas($fechaDe, $fechaHasta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT numMesa, fecha, totalDeLaCuenta FROM mesas 
            WHERE fecha 
            BETWEEN :fechaDe and :fechaHasta "
        );
        $consulta->bindValue(':fechaDe', $fechaDe, PDO::PARAM_STR);
        $consulta->bindValue(':fechaHasta', $fechaHasta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function obtenerMesasEntreFechasTotalGastado($fechaDe, $fechaHasta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT SUM(totalDeLaCuenta) FROM mesas 
            WHERE fecha 
            BETWEEN :fechaDe and :fechaHasta "
        );
        $consulta->bindValue(':fechaDe', $fechaDe, PDO::PARAM_STR);
        $consulta->bindValue(':fechaHasta', $fechaHasta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }

    public static function modificarMesa($dataMesa) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE mesas 
            SET
                numMesa = :numMesa,
                cliente = :cliente, 
                pedidos = :pedidos, 
                estado = :estado,
                totalDeLaCuenta = :totalDeLaCuenta,
                foto = :foto
            WHERE id = :id");
        $consulta->bindValue(':numMesa', $dataMesa->numMesa, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $dataMesa->cliente, PDO::PARAM_INT);
        $consulta->bindValue(':pedidos', $dataMesa->pedidos, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $dataMesa->estado, PDO::PARAM_STR);
        $consulta->bindValue(':totalDeLaCuenta', $dataMesa->totalDeLaCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $dataMesa->foto, PDO::PARAM_STR);
        $consulta->bindValue(':id', $dataMesa->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function EditarEstadoMesa($idMesa, $estado) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET estado = :estado WHERE id = :id");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->bindValue(':id', $idMesa, PDO::PARAM_INT);
        $consulta->execute();
        return Mesa::obtenerMesaSegunMesa($idMesa);
    }

    public static function CerrarPorNumMesa($numMesa) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE mesas 
            SET
                cliente = null, 
                pedidos = null, 
                estado = 'cerrada',
                totalDeLaCuenta = 0,
                foto = null
            WHERE numMesa = :numMesa");
        $consulta->bindValue(':numMesa', $numMesa, PDO::PARAM_INT);
        $consulta->execute();

        return Mesa::obtenerMesaSegunMesa($numMesa);
    }

    public static function borrarMesa($id) {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM mesas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}