<?php
require_once 'model/cliente.php';
require_once 'model/persona.php'; 
require_once 'model/empresa.php'; 

class ClienteController
{
    private $modelCliente;
    private $modelPersona;
    private $modelEmpresa;

    public function __construct()
    {
        $this->modelCliente = new Cliente();
        $this->modelPersona = new Persona();
        $this->modelEmpresa = new Empresa();
    }

    // --- CORRECCIÓN DEFINITIVA: El método se llama Index() para la carga inicial de la página. ---
    public function Index()
    {
        require_once 'view/header.php';
        require_once 'view/frmcliente.php';
        require_once 'view/footer.php';
    }

    // Este método es para cuando se especifica una acción, por ejemplo ?c=cliente&a=IndexPage
    public function IndexPage()
    {
        $this->Index();
    }


    public function InsEditar()
    {
        $cli = new Cliente();
        
        $cli->id_cliente = $_POST['id_cliente'] ?? null;
        $cli->codigo_cliente = $_POST['codigo_cliente'] ?? null;
        $cli->tipo_cliente = $_POST['tipo_cliente'] ?? '';
        $cli->direccion = $_POST['direccion'] ?? null;
        $cli->telefono = $_POST['telefono'] ?? null;
        $cli->correo = $_POST['correo'] ?? null;
        $cli->notas = $_POST['notas'] ?? null;

        $accion = $_REQUEST['ac'] ?? '';

        if ($accion === 'nuevo') {
            $result = $this->modelCliente->Registrar($cli);
            $nuevo_id_cliente = $result->nuevo_id;

            if ($cli->tipo_cliente == 'Persona') {
                $per = new Persona();
                $per->id_cliente = $nuevo_id_cliente;
                $per->nombres = $_POST['nombres'] ?? '';
                $per->apellido_paterno = $_POST['apellido_paterno'] ?? '';
                $per->apellido_materno = $_POST['apellido_materno'] ?? null;
                $per->ci = $_POST['ci'] ?? null;
                $this->modelPersona->Registrar($per);

            } elseif ($cli->tipo_cliente == 'Empresa') {
                $emp = new Empresa();
                $emp->id_cliente = $nuevo_id_cliente;
                $emp->razon_social = $_POST['razon_social'] ?? '';
                $emp->nombre_comercial = $_POST['nombre_comercial'] ?? null;
                $emp->identificador_fiscal = $_POST['identificador_fiscal'] ?? '';
                $this->modelEmpresa->Registrar($emp);
            }
        } elseif ($accion === 'editar') {
            $this->modelCliente->Actualizar($cli);

            if ($cli->tipo_cliente == 'Persona') {
                $per = new Persona();
                $per->id_persona = $_POST['id_persona'] ?? 0;
                $per->id_cliente = $cli->id_cliente;
                $per->nombres = $_POST['nombres'] ?? '';
                $per->apellido_paterno = $_POST['apellido_paterno'] ?? '';
                $per->apellido_materno = $_POST['apellido_materno'] ?? null;
                $per->ci = $_POST['ci'] ?? null;
                $this->modelPersona->Actualizar($per);

            } elseif ($cli->tipo_cliente == 'Empresa') {
                $emp = new Empresa();
                $emp->id_empresa = $_POST['id_empresa'] ?? 0;
                $emp->id_cliente = $cli->id_cliente;
                $emp->razon_social = $_POST['razon_social'] ?? '';
                $emp->nombre_comercial = $_POST['nombre_comercial'] ?? null;
                $emp->identificador_fiscal = $_POST['identificador_fiscal'] ?? '';
                $this->modelEmpresa->Actualizar($emp);
            }
        }

        header('Location: index.php?c=cliente');
        exit;
    }

    public function Eliminar()
    {
        $id = $_REQUEST['id_cliente'] ?? 0;
        if ($id > 0) {
            $this->modelCliente->Eliminar($id);
        }
        header('Location: index.php?c=cliente');
        exit;
    }
}