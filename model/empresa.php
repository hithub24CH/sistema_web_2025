<?php
class Empresa
{
    private $conex;

    public $id_empresa;
    public $id_cliente;
    public $razon_social;
    public $nombre_comercial;
    public $identificador_fiscal;
    public $estado;

    public function __CONSTRUCT()
    {
        try {
            require_once 'conexion.php';
            $this->conex = new Conexion();
            $this->conex = $this->conex->conectar();
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }

    public function Registrar(Empresa $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_empresa_crear(:id_cliente, :razon_social, :nombre_comercial, :id_fiscal)");
            
            $stmt->bindParam(':id_cliente', $data->id_cliente);
            $stmt->bindParam(':razon_social', $data->razon_social);
            $stmt->bindParam(':nombre_comercial', $data->nombre_comercial);
            $stmt->bindParam(':id_fiscal', $data->identificador_fiscal);
            
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar empresa: " . $e->getMessage());
        }
    }

    public function Actualizar(Empresa $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_empresa_actualizar(:id, :id_cliente, :razon_social, :nombre_comercial, :id_fiscal, 1)");

            $stmt->bindParam(':id', $data->id_empresa);
            $stmt->bindParam(':id_cliente', $data->id_cliente);
            $stmt->bindParam(':razon_social', $data->razon_social);
            $stmt->bindParam(':nombre_comercial', $data->nombre_comercial);
            $stmt->bindParam(':id_fiscal', $data->identificador_fiscal);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar empresa: " . $e->getMessage());
        }
    }
}