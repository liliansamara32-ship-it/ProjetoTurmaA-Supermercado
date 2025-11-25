<?php
namespace GrupoA\Supermercado\Controller;
require "lib/redireciona.php";
/**
 * Classe Principal
 *
 * Responsável por gerenciar a página principal da aplicação.
 */
class Principal
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
     * Construtor da classe Principal.
     *
     * Inicializa o ambiente Twig para carregar os templates HTML.
     */
    public function __construct()
    {
        averigua();
        // Construtor da classe
        $this->carregador =
            new \Twig\Loader\FilesystemLoader("./src/View/Html");

        // Combina os dados com o template
        $this->ambiente =
            new \Twig\Environment($this->carregador);
    }

    /**
     * Exibe a página principal da aplicação.
     *
     * @param array $dados Array de dados a serem passados para o template.
     * @return void
     */
    public function paginaPrincipal(array $dados)
    {
        $dados["titulo"] = "Página Principal";
        $dados["conteudo"] = "Bem-vindo à página principal!";

        // Renderiza a view principal
        echo $this->ambiente->render("principal.html", $dados);
    }
}

