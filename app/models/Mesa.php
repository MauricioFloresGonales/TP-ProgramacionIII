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
    public $puntuarLaMesa;
    public $puntuarLaRestaurante;
    public $puntuarLaMozo;
    public $puntuarLaCocinero;
    public $experiencia;

    public function crearMesa() {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (
                numMesa,
                cliente, 
                pedidos, 
                estado,
                totalDeLaCuenta,
                foto,
                puntuarLaMesa, 
                puntuarLaRestaurante, 
                puntuarLaMozo, 
                puntuarLaCocinero, 
                experiencia
            ) VALUES (
                :numMesa,
                :cliente, 
                :pedidos, 
                :estado,
                :totalDeLaCuenta,
                :foto,
                :puntuarLaMesa, 
                :puntuarLaRestaurante, 
                :puntuarLaMozo, 
                :puntuarLaCocinero, 
                :experiencia)");
        $consulta->bindValue(':numMesa', $this->numMesa, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':pedidos', $this->pedidos, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':totalDeLaCuenta', $this->totalDeLaCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $this->foto, PDO::PARAM_STR);
        $consulta->bindValue(':puntuarLaMesa', $this->puntuarLaMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaRestaurante', $this->puntuarLaRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaMozo', $this->puntuarLaMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaCocinero', $this->puntuarLaCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':experiencia', $this->experiencia, PDO::PARAM_STR);
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
                foto,
                puntuarLaMesa, 
                puntuarLaRestaurante, 
                puntuarLaMozo, 
                puntuarLaCocinero, 
                experiencia
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
                foto,
                puntuarLaMesa, 
                puntuarLaRestaurante, 
                puntuarLaMozo, 
                puntuarLaCocinero, 
                experiencia
            FROM mesas 
            WHERE numMesa = :numMesa");
        $consulta->bindValue(':numMesa', $numMesa, PDO::PARAM_STR);
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
                foto,
                puntuarLaMesa, 
                puntuarLaRestaurante, 
                puntuarLaMozo, 
                puntuarLaCocinero, 
                experiencia
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
                foto,
                puntuarLaMesa, 
                puntuarLaRestaurante, 
                puntuarLaMozo, 
                puntuarLaCocinero, 
                experiencia
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
                foto,
                puntuarLaMesa, 
                puntuarLaRestaurante, 
                puntuarLaMozo, 
                puntuarLaCocinero, 
                experiencia
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
                foto = :foto,
                puntuarLaMesa = :puntuarLaMesa, 
                puntuarLaRestaurante = :puntuarLaRestaurante, 
                puntuarLaMozo = :puntuarLaMozo, 
                puntuarLaCocinero = :puntuarLaCocinero, 
                experiencia = :experiencia
            WHERE id = :id");
        $consulta->bindValue(':numMesa', $dataMesa->numMesa, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $dataMesa->cliente, PDO::PARAM_INT);
        $consulta->bindValue(':pedidos', $dataMesa->pedidos, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $dataMesa->estado, PDO::PARAM_STR);
        $consulta->bindValue(':totalDeLaCuenta', $dataMesa->totalDeLaCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $dataMesa->foto, PDO::PARAM_STR);
        $consulta->bindValue(':puntuarLaMesa', $dataMesa->puntuarLaMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaRestaurante', $dataMesa->puntuarLaRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaMozo', $dataMesa->puntuarLaMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaCocinero', $dataMesa->puntuarLaCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':experiencia', $dataMesa->experiencia, PDO::PARAM_STR);
        $consulta->bindValue(':id', $dataMesa->id, PDO::PARAM_INT);
        $consulta->execute();
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
                foto = null,
                puntuarLaMesa = null, 
                puntuarLaRestaurante = null, 
                puntuarLaMozo = null, 
                puntuarLaCocinero = null, 
                experiencia = null
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