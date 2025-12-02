<?php
namespace GrupoA\Supermercado\Controller;

use GrupoA\Supermercado\Model\Database;
use GrupoA\Supermercado\Model\Pedido;
use GrupoA\Supermercado\Model\PedidoItem;

class PedidoController
{
    public function finalizar()
    {
        session_start();

        if (empty($_SESSION['carrinho'])) {
            echo "Carrinho vazio.";
            return;
        }

        $db = new Database();

        $total = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item["valor"] * $item["quantidade"];
        }

        $pedido = new Pedido();
        $pedido->pddCli = 1;
        $pedido->pddData = date("Y-m-d H:i:s");
        $pedido->pddTipo = 1;
        $pedido->pddVlrTotal = $total;
        $pedido->pddForn = 1;

        $idPedido = $db->savePedido($pedido);

        foreach ($_SESSION['carrinho'] as $item) {
            $i = new PedidoItem();
            $i->itnPdd = $idPedido;
            $i->itnPrd = $item["id"];
            $i->itnVlr = $item["valor"];
            $i->itnQntd = $item["quantidade"];

            $db->savePedidoItem($i);
        }

        // limpa carrinho
        unset($_SESSION['carrinho']);

        echo "Pedido finalizado!";
    }
}