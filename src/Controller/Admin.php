<?php
namespace GrupoA\Supermercado\Controller;

use GrupoA\Supermercado\User; // Importação da classe User (atualmente não utilizada neste arquivo).
use GrupoA\Supermercado\Produto; // Importação da classe Produto (assumindo que seja um DTO ou classe de modelo).
use GrupoA\Supermercado\Database; // Importação da classe Database para interação com o banco de dados.
require "lib/redireciona.php";
/**
 * Classe Admin
 *
 * Responsável por gerenciar as funcionalidades administrativas da aplicação,
 * como a edição e listagem de produtos. Requer autenticação de usuário.
 */
class Admin
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
     * Construtor da classe Admin.
     *
     * Inicia a sessão e verifica se o usuário está autenticado.
     * Caso não esteja, redireciona para a página de login.
     * Inicializa o ambiente Twig para carregar os templates.
     */
    public function __construct()
    {
        averigua();
        // Configura o carregador de templates Twig para buscar arquivos na pasta "src/View".
        $this->carregador = new \Twig\Loader\FilesystemLoader("./src/View");
        // Inicializa o ambiente Twig.
        $this->ambiente = new \Twig\Environment($this->carregador);
    }

    /**
     * Exibe o formulário para editar um produto específico, buscando-o pelo ID.
     *
     * @param array $dados Array contendo o ID do produto a ser editado.
     * @return void Renderiza o formulário de atualização de produto ou uma mensagem de erro.
     */
    public function formularioEditarProduto(array $dados)
    {
        $id = intval($dados["id"] ?? 0); // Obtém o ID do produto dos dados e garante que seja um inteiro.
        $bd = new Database(); // Instancia a classe Database (deve ser refatorado para usar um repositório).
        $produto = $bd->buscarProdutoPorId($id); // Busca o produto no banco de dados.

        if (!$produto) {
            // Se o produto não for encontrado, renderiza o formulário com uma mensagem de aviso.
            echo $this->ambiente->render(
                "AtualizarProduto.html",
                [
                    "avisos" => "Produto não encontrado."
                ]
            );
            return;
        }
        // Renderiza o formulário de atualização de produto com os dados do produto encontrado.
        // (Observação: o template "AtualizarProduto.html" ainda não foi criado ou está incompleto).
        echo $this->ambiente->render("AtualizarProduto.html", [
            "produto" => $produto
        ]);
    }


    /**
     * Lista todos os produtos disponíveis no banco de dados.
     *
     * @return void Renderiza a página com a lista de produtos.
     */
    public function listarProdutos(): void
    {
        $bd = new Database(); // Instancia a classe Database (deve ser refatorado para usar um repositório).
        $produtos = $bd->buscarProdutos(); // Busca todos os produtos no banco de dados.

        // Renderiza o template "ListarProdutos.html" com a lista de produtos.
        echo $this->ambiente->render("ListarProdutos.html", [
            "produtos" => $produtos
        ]);
    }


    /**
     * Processa a requisição de atualização de um produto.
     *
     * @param array $dados Array contendo os dados do produto a serem atualizados.
     * @return void Renderiza o formulário de atualização com uma mensagem de sucesso ou erro.
     */
    public function editarProduto(array $dados)
    {
        // Sanitiza e valida os dados recebidos do formulário.
        $id = trim($dados["id"]);
        $nome = trim($dados["nome"]);
        $valor = floatval($dados["valor"]);
        $categoria = trim($dados["categoria"]);
        $descricao = trim($dados["descricao"]);
        $quantidade = intval($dados["quantidade"]);

        $avisos = "";

        // Verifica se os campos obrigatórios foram preenchidos corretamente.
        if ($id != "" && $nome != "" && $valor > 0) {
            $produto = new Produto(); // Cria uma nova instância de Produto.
            // Atribui os dados recebidos ao objeto Produto.
            $produto->id = $id;
            $produto->nome = $nome;
            $produto->valor = $valor;
            $produto->categoria = $categoria;
            $produto->descricao = $descricao;
            $produto->quantidade = $quantidade;

            $bd = new Database(); // Instancia a classe Database (deve ser refatorado para usar um repositório).
            // Tenta atualizar o produto no banco de dados.
            if ($bd->atualizaProduto($produto)) {
                $avisos = "Produto atualizado com sucesso!";
            } else {
                $avisos = "Erro ao atualizar produto.";
            }
        } else {
            $avisos = "Preencha todos os campos corretamente.";
        }

        // Renderiza o formulário de atualização com a mensagem de aviso.
        echo $this->ambiente->render("AtualizarProduto.html", ["avisos" => $avisos]);
    }
}
