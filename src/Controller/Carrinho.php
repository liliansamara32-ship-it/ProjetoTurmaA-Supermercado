<?php

class Carrinho
{
    public function index()
    {
        session_start();

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $ids = $_SESSION['carrinho']; 

        $produtoModel = new ProdutoModel();
        $produtos = $produtoModel->buscarPorIds($ids);

        $subtotal = 0;

        foreach ($produtos as $p) {
            $subtotal += $p['preco'];
        }

        $total = $subtotal;

        require 'views/carrinho.php';
    }
}
?>
