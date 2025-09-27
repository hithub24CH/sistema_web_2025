<?php
class Persona
{
    private $conex;

    public $id_persona;
    public $id_cliente;
    public $nombres;
    public $apellido_paterno;
    public $apellido_materno;
    public $ci;
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

    public function Registrar(Persona $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_persona_crear(:id_cliente, :nombres, :paterno, :materno, :ci)");
            
            $stmt->bindParam(':id_cliente', $data->id_cliente);
            $stmt->bindParam(':nombres', $data->nombres);
            $stmt->bindParam(':paterno', $data->apellido_paterno);
            $stmt->bindParam(':materno', $data->apellido_materno);
            $stmt->bindParam(':ci', $data->ci);
            
            $stmt->execute();
        } catch (Exception $e) {
            die("Error al registrar persona: " . $e->getMessage());
        }
    }

    public function Actualizar(Persona $data)
    {
        try {
            $stmt = $this->conex->prepare("CALL sp_persona_actualizar(:id, :id_cliente, :nombres, :paterno, :materno, :ci, 1)");

            $stmt->bindParam(':id', $data->id_persona);
            $stmt->bindParam(':id_cliente', $data->id_cliente);
            $stmt->bindParam(':nombres', $data->nombres);
            $stmt->bindParam(':paterno', $data->apellido_paterno);
            $stmt->bindParam(':materno', $data->apellido_materno);
            $stmt->bindParam(':ci', $data->ci);

            $stmt->execute();
        } catch (Exception $e) {
            die("Error al actualizar persona: " . $e->getMessage());
        }
    }
}