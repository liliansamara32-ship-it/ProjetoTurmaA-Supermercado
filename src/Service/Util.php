<?php

namespace GrupoA\Supermercado\Service;

class Util
{
    public static function checkAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            header("Location: /login");
            exit;
        }
    }
}

class CarrinhoUtil
{
    public static function iniciarCarrinho()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public static function adicionarProduto($id, $nome, $valor, $QTDE = 1, $foto1 = "")
    {
        CarrinhoUtil::iniciarCarrinho();

        if (!isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id] = [
                'id' => $id, 
                'nome' => $nome,
                'valor' => $valor,
                'QTDE' => $QTDE,
                'foto1' => $foto1
            ];
        } else {
            $_SESSION['cart'][$id]['QTDE'] += $QTDE;
        }
    }

    
   public static function calcularTempoEntrega($cepOrigem, $cepDestino)
    {
        if (!$cepOrigem || !$cepDestino) {
            return "Indefinido";
        }

        $orig = intval(preg_replace('/\D/', '', $cepOrigem));
        $dest = intval(preg_replace('/\D/', '', $cepDestino));

        $dist = abs($orig - $dest) / 10000;

        if ($dist <= 5) return "1 dia útil";
        if ($dist <= 10) return "2 dias úteis";
        return "3 dias úteis";
    }

     public static function calcularTaxaEntrega($cepOrigem, $cepDestino)
    {
        if (!$cepDestino) return 0;

        $cep = intval(preg_replace('/\D/', '', $cepDestino));

        if ($cep >= 1000000 && $cep <= 2999999) {
            return 8.90;
        }
        if ($cep >= 3000000 && $cep <= 4999999) {
            return 12.90;
        }
        if ($cep >= 5000000 && $cep <= 7999999) {
            return 16.90;
        }

        return 20.00; 
    } 

     public static function calcularResumo(array $carrinho, $txEntrega): array
    {
        $subtotal = 0;
        $total = 0;

        foreach ($carrinho as $p) {
            $qtde = $p["QTDE"] ?? 1;
            $valor = $p["valor"] ?? 0;

            $subtotal += $valor * $qtde;
        }

        $total = $subtotal + $txEntrega;

        return [
            "subtotal" => $subtotal,
            "txEntrega" => $txEntrega,
            "total" => $total,
            "desconto" => 0
        ];
    }

    public static function removerProduto($id)
    {
        if (isset($_SESSION['cart'][$id])) {
            unset($_SESSION['cart'][$id]);
        }
    }
}
