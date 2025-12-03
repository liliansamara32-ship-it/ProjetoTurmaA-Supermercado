<?php

namespace GrupoA\Supermercado\Model;

class PedidoRepositorio
{
    private \PDO $conexao;

    public function __construct(\PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    /**
     * Atualiza as informações de um Pedido no banco de dados.
     *
     * @param Pedido $pedido O objeto Pedido com as informações atualizadas.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function atualizaPedido(Pedido $pedido): bool
    {
        $sql = "UPDATE Pedido 
                SET pddCli = :pddCli, 
                    pddData = :pddData, 
                    pddTipo = :pddTipo, 
                    pddVlrTotal = :pddVlrTotal, 
                    pddForn = :pddForn
                WHERE pddCod = :pddCod";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":pddCli", $pedido->pddCli);
        $stmt->bindValue(":pddData", $pedido->pddData);
        $stmt->bindValue(":pddTipo", $pedido->pddTipo);
        $stmt->bindValue(":pddVlrTotal", $pedido->pddVlrTotal);
        $stmt->bindValue(":pddCod", $pedido->pddCod);

        return $stmt->execute();
    }

    /**
     * Busca um produto no banco de dados pelo seu ID.
     *
     * @param int $id O ID do produto a ser buscado.
     * @return array|false Um array associativo contendo os dados do produto, ou false se não encontrado.
     */
    public function buscarPedidoPorId($id)
    {
        $sql = "SELECT * FROM Pedido WHERE pddCod = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $pedido = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $pedido ? $pedido : false;
    }

    /**
     * Busca todos os produtos no banco de dados.
     *
     * @return array Um array de arrays associativos, onde cada array interno representa um produto.
     */
    public function buscarPedido(): array
    {
        $stmt = $this->conexao->prepare("SELECT * FROM Pedido");
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deletarPedido($id): bool
    {
        $sql = "DELETE FROM Pedido WHERE pddCod = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id);
        return $stmt->execute();
    }
}
