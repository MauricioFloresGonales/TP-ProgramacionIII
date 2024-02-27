<?php

class Encuesta
{
    public $id;
    public $numMesa;
    public $cliente;
    public $pedidos;
    public $totalDeLaCuenta;
    public $puntuarLaMesa;
    public $puntuarElRestaurante;
    public $puntuarLaMozo;
    public $puntuarLaCocinero;
    public $experiencia;
    public $fecha;

    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO encuestas (
                numMesa,
                cliente,
                pedidos,
                totalDeLaCuenta,
                puntuarLaMesa,
                puntuarElRestaurante,
                puntuarLaMozo,
                puntuarLaCocinero,
                experiencia,
                fecha) 
            VALUES (
                :numMesa,
                :cliente,
                :pedidos,
                :totalDeLaCuenta,
                :puntuarLaMesa,
                :puntuarElRestaurante,
                :puntuarLaMozo,
                :puntuarLaCocinero,
                :experiencia,
                :fecha)");
        $consulta->bindValue(':numMesa', $this->numMesa, PDO::PARAM_INT);
        $consulta->bindValue(':cliente', $this->cliente, PDO::PARAM_INT);
        $consulta->bindValue(':pedidos', $this->pedidos, PDO::PARAM_STR);
        $consulta->bindValue(':totalDeLaCuenta', $this->totalDeLaCuenta, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaMesa', $this->puntuarLaMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarElRestaurante', $this->puntuarElRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaMozo', $this->puntuarLaMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaCocinero', $this->puntuarLaCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':experiencia', $this->experiencia, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $this->fecha, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM encuestas");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
    public static function obtenerEncuetaId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
                id,
                numMesa,
                cliente,
                pedidos,
                totalDeLaCuenta,
                puntuarLaMesa,
                puntuarElRestaurante,
                puntuarLaMozo,
                puntuarLaCocinero,
                experiencia,
                fecha
            FROM encuestas
            WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }
    public static function OrdenarEncuestasPorComentarios($ordenamiento)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        if (strcmp($ordenamiento, "asc")) {
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT 
                    id,
                    numMesa,
                    cliente,
                    pedidos,
                    totalDeLaCuenta,
                    puntuarLaMesa,
                    puntuarElRestaurante,
                    puntuarLaMozo,
                    puntuarLaCocinero,
                    experiencia,
                    fecha
                FROM encuestas
                ORDER BY puntuarLaMesa, puntuarElRestaurante, puntuarLaMozo, puntuarLaCocinero");
                $consulta->execute();
        } else {
            $consulta = $objAccesoDatos->prepararConsulta(
                "SELECT 
                    id,
                    numMesa,
                    cliente,
                    pedidos,
                    totalDeLaCuenta,
                    puntuarLaMesa,
                    puntuarElRestaurante,
                    puntuarLaMozo,
                    puntuarLaCocinero,
                    experiencia,
                    fecha
                FROM encuestas
                ORDER BY puntuarLaMesa DESC, puntuarElRestaurante DESC, puntuarLaMozo DESC, puntuarLaCocinero DESC");
            $consulta->execute();
        }

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
    public static function OrdenarMesaMasUsada()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT numMesa, COUNT(*) AS vecesUsada
            FROM encuestas
            GROUP BY numMesa
            ORDER BY COUNT(*) DESC;");
        $consulta->execute();
            

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }
    public static function obtenerEncuestaEntreFechas($numMesa, $fechaDe, $fechaHasta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT numMesa, fecha, totalDeLaCuenta 
            FROM encuestas
            WHERE numMesa = :numMesa
            AND fecha
            BETWEEN :fechaDe and :fechaHasta"
        );
        $consulta->bindValue(':numMesa', $numMesa, PDO::PARAM_INT);
        $consulta->bindValue(':fechaDe', $fechaDe, PDO::PARAM_STR);
        $consulta->bindValue(':fechaHasta', $fechaHasta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchAll();
    }
    public static function obtenerEncuestaEntreFechasTotalGastado($numMesa, $fechaDe, $fechaHasta) {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT SUM(totalDeLaCuenta) FROM encuestas 
            WHERE numMesa = :numMesa
            AND fecha
            BETWEEN :fechaDe and :fechaHasta "
        );
        $consulta->bindValue(':numMesa', $numMesa, PDO::PARAM_INT);
        $consulta->bindValue(':fechaDe', $fechaDe, PDO::PARAM_STR);
        $consulta->bindValue(':fechaHasta', $fechaHasta, PDO::PARAM_STR);
        $consulta->execute();

        return $consulta->fetchObject();
    }
    public static function modificarEncuesta($encuesta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE encuestas
            SET
                numMesa = :numMesa
                cliente = :cliente
                pedidos = :pedidos
                totalDeLaCuenta = :totalDeLaCuenta
                puntuarLaMesa = :puntuarLaMesa
                puntuarElRestaurante = :puntuarElRestaurante
                puntuarLaMozo = :puntuarLaMozo
                puntuarLaCocinero = :puntuarLaCocinero
                experiencia = :experiencia
                fecha = :fecha
            WHERE id = :id");
        $consulta->bindValue(':numMesa', $encuesta->numMesa, PDO::PARAM_STR);
        $consulta->bindValue(':cliente', $encuesta->cliente, PDO::PARAM_STR);
        $consulta->bindValue(':pedidos', $encuesta->pedidos, PDO::PARAM_INT);
        $consulta->bindValue(':totalDeLaCuenta', $encuesta->totalDeLaCuenta, PDO::PARAM_STR);
        $consulta->bindValue(':puntuarLaMesa', $encuesta->puntuarLaMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarElRestaurante', $encuesta->puntuarElRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaMozo', $encuesta->puntuarLaMozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuarLaCocinero', $encuesta->puntuarLaCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':experiencia', $encuesta->experiencia, PDO::PARAM_STR);
        $consulta->bindValue(':fecha', $encuesta->fecha, PDO::PARAM_STR);
        $consulta->bindValue(':id', $encuesta->id, PDO::PARAM_INT);
        $consulta->execute();
    }
    public static function borrarEncuesta($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM encuestas WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
}
