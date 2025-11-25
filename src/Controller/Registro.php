<?php
namespace GrupoA\Supermercado\Controller;

use GrupoA\Supermercado\Model\Database;
use GrupoA\Supermercado\Model\User;

class Registro{
        private \Twig\Environment $ambiente;
    private \Twig\Loader\FilesystemLoader $carregador;

    public function __construct()
    {
        // Configura o carregador e o ambiente do Twig
        $this->carregador = new \Twig\Loader\FilesystemLoader("./src/View/Html");
        $this->ambiente = new \Twig\Environment($this->carregador);
    }
    public function paginaRegistro(array $dados)
    {
        echo $this->ambiente->render("registrocli.html", $dados);
    }

    /**
     * Salva um novo login
     * @param array $dados
     * @return void
     */
    public function novoUsuario(array $dados)
    {
        $nome = trim($dados["nome"]);
        $email = trim($dados["email"]);
        $senha = $dados["senha"];
        $endereco = $dados["endereco"];
        $cpf = $dados["cpf"];
        $senha2 = $dados["senha2"];

        $avisos = "";
        $bd = new Database();

        if ($nome == "" || $email == "" || $senha == "" || $endereco == "" || $cpf == "") {
            $avisos .= "Preencha todos os campos.";
        }
        
        if ($bd->loadUserByEmail($email)) {
            $avisos .= "Este email já está cadastrado.";
        }
        
        if ($senha !== $senha2) {
            $avisos .= "As senhas não coincidem.";
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $avisos .= "Email inválido.";
        }
        
        if (strlen($senha) < 8 || !preg_match('/[0-9]/', $senha)) {
            $avisos .= "A senha deve ter pelo menos 8 caracteres e 1 número.";
        }
         
        if($avisos == "") {
            $usuario = new User();
            $usuario->nome = $nome;
            $usuario->email = $email;
            $usuario->senha = password_hash($senha, PASSWORD_DEFAULT);
            $usuario->endereco = $endereco;
            $usuario->cpf = $cpf;

            if ($bd->newUser($usuario)) {
                // Avisa que deu certo
                $avisos .= "Usuário cadastrado com sucesso.";
            } else {
                // Avisa que deu errado
                $avisos .= "Erro ao cadastrar usuário.";
            }
        }

        $dados["avisos"] = $avisos;
        echo $this->ambiente->render("registrocli.html", $dados);
    }
}
