<?php

class Cuenta
{
    public $id;
    public $numMesa;
    public $moso;
    public $idPedidos;
    public $totalAPagar;
    public $foto;

    public function crearCuenta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "INSERT INTO cuenta (
                numMesa,
                moso,
                idPedidos,
                totalAPagar) 
            VALUES (
                :numMesa,
                :moso,
                :idPedidos,
                :totalAPagar)");
        $consulta->bindValue(':numMesa', $this->numMesa, PDO::PARAM_INT);
        $consulta->bindValue(':moso', $this->moso, PDO::PARAM_STR);
        $consulta->bindValue(':idPedidos', $this->idPedidos, PDO::PARAM_STR);
        $consulta->bindValue(':totalAPagar', $this->totalAPagar, PDO::PARAM_STR);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuenta");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cuenta');
    }
    public static function obtenerCuentaId($id)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
                id,
                numMesa,
                moso,
                idPedidos,
                totalAPagar,
                foto
            FROM cuenta
            WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }
    public static function obtenerCuentaIdPedido($idPedidos)// sirve?
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
                id,
                numMesa,
                moso,
                idPedidos,
                totalAPagar,
                foto
            FROM cuenta
            WHERE idPedidos = :idPedidos");
        $consulta->bindValue(':idPedidos', $idPedidos, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }
    public static function obtenerPorMoso($moso)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta(
            "SELECT 
                id,
                numMesa,
                moso,
                idPedidos,
                totalAPagar,
                foto
            FROM cuenta
            WHERE moso = :moso");
        $consulta->bindValue(':moso', $moso, PDO::PARAM_INT);
        $consulta->execute();

        return $consulta->fetchObject('Cuenta');
    }
    public static function ObtenerCuentaConMasGastos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT * FROM cuenta ORDER BY totalAPagar DESC");
        $consulta->execute();

        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Cuenta');
    }
    public static function modificarCuenta($cuenta)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta(
            "UPDATE cuenta 
            SET
                numMesa = :numMesa,
                moso = :moso,
                idPedidos = :idPedidos,
                totalAPagar = :totalAPagar,
                foto = :foto
            WHERE id = :id");
        $consulta->bindValue(':numMesa', $cuenta->numMesa, PDO::PARAM_STR);
        $consulta->bindValue(':moso', $cuenta->moso, PDO::PARAM_STR);
        $consulta->bindValue(':idPedidos', $cuenta->idPedidos, PDO::PARAM_INT);
        $consulta->bindValue(':totalAPagar', $cuenta->totalAPagar, PDO::PARAM_STR);
        $consulta->bindValue(':foto', $cuenta->foto, PDO::PARAM_STR);
        $consulta->bindValue(':id', $cuenta->id, PDO::PARAM_INT);
        $consulta->execute();
    }

    public static function borrarCuenta($id)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("DELETE FROM cuenta WHERE id = :id");
        $consulta->bindValue(':id', $id, PDO::PARAM_INT);
        $consulta->execute();
    }
    public static function CrearNewCuenta($idCliente, $moso, $pedidosCreados)
    {
        $cuenta = new Cuenta();
        $cuenta->moso = $moso;
        $idPedidos = [];
        $totalAPagar = 0;
        foreach ($pedidosCreados as $pedido) {
            array_push($idPedidos, $pedido->id);
            $totalAPagar = $totalAPagar + $pedido->precio;
        }
        $cuenta->idPedidos = json_encode($idPedidos);
        $cuenta->totalAPagar = $totalAPagar;

        $cliente = Cliente::obtenerClienteId($idCliente);
        $mesa = Mesa::obtenerMesaSegunNumero($cliente->mesa);
        $cuenta->numMesa = $mesa->numMesa;

        //DB
        $idCreado= $cuenta->crearCuenta();
        $cuenta->id = $idCreado;
        return $cuenta;
    }
}