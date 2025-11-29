<?php
namespace GrupoA\Supermercado\Controller;

use GrupoA\Supermercado\Model\Database;
use GrupoA\Supermercado\Model\User;
use GrupoA\Supermercado\Model\UserRepository;

/**
 * Classe Login
 *
 * Responsável por gerenciar as operações de autenticação e logout de usuários.
 */
class Login
{
    /**
     * @var \Twig\Environment $ambiente Instância do ambiente Twig para renderização de templates.
     */
    private \Twig\Environment $ambiente;

    /**
     * @var \Twig\Loader\FilesystemLoader $carregador Instância do carregador de arquivos Twig.
     */
    private \Twig\Loader\FilesystemLoader $carregador;

    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * Construtor da classe Login.
     *
     * Inicializa o ambiente Twig e o repositório de usuários.
     */
    public function __construct()
    {
        $this->carregador = new \Twig\Loader\FilesystemLoader("./src/View/Html");
        $this->ambiente = new \Twig\Environment($this->carregador);
        $this->userRepository = new UserRepository(Database::getConexao());
    }

    public function formularioLogin(array $dados)
    {
        echo $this->ambiente->render("login.html", $dados);
    }

    /**
     * Autentica o usuário
     * @param array $dados
     * @return void
     */
    public function paginaLogin()
    {
        echo $this->ambiente->render("login.html", []);
    }

    /**
     * Autentica o usuário com base no e-mail e senha fornecidos.
     *
     * @param array $dados Array contendo 'email' e 'senha' do usuário.
     * @return void Redireciona para a página principal em caso de sucesso, ou exibe avisos.
     */
    function autenticar(array $dados)
    {
        $email = filter_var(trim($dados["email"]), FILTER_SANITIZE_EMAIL);
        $senha = htmlspecialchars(trim($dados["senha"]), ENT_QUOTES, 'UTF-8');

        $avisos = "";

        if (empty($email) || empty($senha)) {
            $avisos .= "Preencha todos os campos.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $avisos .= "Formato de email inválido.";
        } else {
            $usuario = $this->userRepository->loadUserByEmail($email);

            if ($usuario && password_verify($senha, $usuario["senha"])) {
                $_SESSION["usuario"] = $usuario;
                header("Location: /");
                exit;
            } else {
                $avisos .= "Nome ou senha inválidos.";
            }
        }

        $dados["avisos"] = $avisos;
        // mudar conforme criarem o html
        echo $this->ambiente->render("login.html", $dados);
    }

    /**
     * Salva um novo login (método incompleto).
     *
     * @param array $dados Dados para salvar o login.
     * @return void
     */
    public function salvarLogin(array $dados)
    {
        echo $this->ambiente->render("login.html", []);
    }

    /**
     * Realiza o logout do usuário, destruindo a sessão.
     *
     * @param array $dados Dados (não utilizados neste método, mas mantido para consistência de assinatura).
     * @return void Redireciona para a página principal após o logout.
     */
    public function logout(array $dados)
    {
        unset($_SESSION['user_id']);

        // destrói a sessão
        session_destroy();

        // vai redirecionar para página principal
        header("Location: /");
        exit;
    }
}