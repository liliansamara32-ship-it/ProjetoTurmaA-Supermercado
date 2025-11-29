<?php

namespace GrupoA\Supermercado\Model;

class ProductRepository
{
    private \PDO $conexao;

    public function __construct(\PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    /**
     * Atualiza as informações de um produto no banco de dados.
     *
     * @param Produto $produto O objeto Produto com as informações atualizadas.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function atualizaProduto(Produto $produto): bool
    {
        $sql = "UPDATE produtos 
                SET prdTitulo = :prdTitulo, 
                    prdVlrUnit = :prdVlrUnit, 
                    prdCateg = :prdCateg, 
                    prdDescr = :prdDescr, 
                    quantidade = :quantidade
                WHERE prdCod = :prdCod";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":prdTitulo", $produto->prdTitulo);
        $stmt->bindValue(":prdVlrUnit", $produto->prdVlrUnit);
        $stmt->bindValue(":prdCateg", $produto->prdCateg);
        $stmt->bindValue(":prdDescr", $produto->prdDescr);
        $stmt->bindValue(":prdCod", $produto->prdCod);

        return $stmt->execute();
    }

    /**
     * Busca um produto no banco de dados pelo seu ID.
     *
     * @param int $id O ID do produto a ser buscado.
     * @return array|false Um array associativo contendo os dados do produto, ou false se não encontrado.
     */
    public function buscarProdutoPorId($id)
    {
        $sql = "SELECT * FROM produtos WHERE prdCod = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $produto = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $produto ? $produto : false;
    }

    /**
     * Busca todos os produtos no banco de dados.
     *
     * @return array Um array de arrays associativos, onde cada array interno representa um produto.
     */
    public function buscarProdutos(): array
    {
        $stmt = $this->conexao->prepare("SELECT * FROM produtos");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deletarProduto($id): bool
    {
        $sql = "DELETE FROM produtos WHERE prdCod = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }
}
