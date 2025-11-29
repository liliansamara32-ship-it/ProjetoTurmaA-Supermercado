<?php
namespace GrupoA\Supermercado\Controller;

use GrupoA\Supermercado\Service\Util;

class Checkout
{
    private \Twig\Environment $ambiente;
    private \Twig\Loader\FilesystemLoader $carregador;

    public function __construct()
    {
        Util::averigua();
        // Construtor da classe
        $this->carregador =
            new \Twig\Loader\FilesystemLoader("./src/View/Html");

        // Combina os dados com o template
        $this->ambiente =
            new \Twig\Environment($this->carregador);
    }

    public function paginaCheckout(array $dados)
    {
        $dados["titulo"] = "Finalizar Compra";
        $dados["conteudo"] = "Revise seu pedido e informe os dados para entrega e pagamento";

        // Adiciona placeholders aos dados
        $dados["nome_completo"] = "Nome Sobrenome";
        $dados["email_usuario"] = "nomesobrenome@gmail.com";
        $dados["telefone_usuario"] = "12 34567-8901";

        $dados["endereco_entrega"] = "Rua Alafebto, 123";
        $dados["cidade_entrega"] = "Cidade Exemplo";
        $dados["estado_entrega"] = "Estado Exemplo";
        $dados["cep_entrega"] = "12345-678";

        $dados["numero_cartao"] = "1234 5678 9012 3456";
        $dados["nome_cartao"] = "Nome Sobrenome";
        $dados["validade_cartao"] = "MM/AA";
        $dados["cvv_cartao"] = "123";

        // Simula os produtos no carrinho
        //Essa variável é estática, apenas para simulação, futuramente deve ser substituída pelos produtos do carrinho do usuário, já que não existre carrinho.php
        $produtos = [
            ['preco' => 39.90, 'quantidade' => 2],
            ['preco' => 15.50, 'quantidade' => 1]
        ];

        $taxa = 0;
        foreach ($produtos as $produto) {
            $taxa += $produto['preco'] * $produto['quantidade'];
        }

        $frete = 10.00;
        $desconto = 5.00;
        $total = $taxa + $frete - $desconto;

        $dados["taxa.checkout"] = $taxa;
        $dados["frete.checkout"] = $frete;
        $dados["desconto.checkout"] = $desconto;
        $dados["total.checkout"] = $total;       

        // Renderiza a view de checkout
        echo $this->ambiente->render("checkout.html", $dados);
    }
    
    // 
    public function finalizarPedido()
    {
        if ($_SERVER["REQUEST_METHOD"] !== "POST") {
            echo "Método inválido.";
            return;
        }

        // dados enviados pelo usuário
        $nome = $_POST["nome"] ?? "";
        $destinatario = $_POST["email"] ?? "";
        $endereco = $_POST["endereco"] ?? "";
        $cidade = $_POST["cidade"] ?? "";
        $estado = $_POST["estado"] ?? "";
        $cep = $_POST["cep"] ?? "";
        $metodo = $_POST["metodo"] ?? "";

        //  enviar de email email após finalizar pagamento
        $mensagemHTML  = "
            <h2>Pedido Confirmado</h2>
            <p><strong>Nome:</strong> $nome</p>
            <p><strong>Email:</strong> $destinatario</p>
            <p><strong>Endereço:</strong> $endereco, $cidade - $estado</p>
            <p><strong>CEP:</strong> $cep</p>
            <p><strong>Pagamento:</strong> $metodo</p>
            <p>Obrigado por comprar conosco!</p>
        ";

        $enviado = enviarEmailCliente($destinatario, $nome, $mensagemHTML);

        if ($enviado) {
            echo "Pedido finalizado e email enviado!";
        } else {
            echo "Erro ao enviar email.";
        }
    }
}