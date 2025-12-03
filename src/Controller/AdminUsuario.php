<?php
namespace Controller;

use Model\Usuario;

class AdminUsuarios extends Admin
{
    private $con;

    public function __construct($con)
    {
        parent::__construct();
        $this->con = $con;
    }

    public function index()
    {
        $usuarios = Usuario::listarTodos($this->con);

        include __DIR__ . "/../../views/admin/usuarios.php";
    }
}


{
    private Usuario $usuarioModel;

    public function __construct(Usuario $usuarioModel)
    {
        $this->usuarioModel = $usuarioModel;
    }

    public function index()
    {
        $usuarios = $this->usuarioModel->listarTodos();
        require "views/admin/usuarios/index.php";
    }

    public function editarForm($id)
    {
        $usuario = $this->usuarioModel->buscarPorId($id);
        require "views/admin/usuarios/editar.php";
    }

    public function salvarEdicao()
    {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $this->usuarioModel->editar($id, $nome, $email, $role);

        header("Location: /admin/usuarios");
        exit;
    }

    public function excluir($id)
    {
        $this->usuarioModel->excluir($id);
        header("Location: /admin/usuarios");
        exit;
    }
}