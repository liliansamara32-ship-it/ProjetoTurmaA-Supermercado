<?php
namespace GrupoA\Supermercado\Model;

use GrupoA\Supermercado\Config\Config;

/**
 * Classe Database
 *
 * Responsável por gerenciar a conexão com o banco de dados.
 */
class Database
{
    /**
     * @var \PDO $conexao Objeto PDO para a conexão com o banco de dados.
     */
    private static ?\PDO $conexao = null;

    /**
     * Retorna uma instância da conexão com o banco de dados.
     *
     * @return \PDO
     * @throws \PDOException
     */
    public static function getConexao(): \PDO
    {
        if (self::$conexao === null) {
            $dbConfig = Config::getDatabaseConfig();

            self::$conexao = new \PDO(
                "mysql:host={$dbConfig['host']};dbname={$dbConfig['name']}",
                $dbConfig['user'],
                $dbConfig['pass']
            );
            self::$conexao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }
    }

    function newUser(User $user): bool
    {
        // Acessa a propriedade "conexao" do objeto
        // ($this->conexao) e prepara a instrução SQL
        // para ser executada
        $stmt = $this->conexao->prepare("INSERT INTO usuarios (nome, email, senha, endereco, cpf) VALUES (:nome, :email, :senha, :endereco, :cpf)");

        // Substitui os "placeholders" pelos seus
        // respectivos valores
        $stmt->bindValue(":nome", $user->nome);
        $stmt->bindValue(":email", $user->email);
        $stmt->bindValue(":senha", $user->senha);
        $stmt->bindValue(":endereco", $user->endereco);
        $stmt->bindValue(":cpf", $user->cpf);

        // Executa o sql no banco
        return $stmt->execute();
    }

    /**
     * Carrega um usuário do banco de dados pelo seu endereço de e-mail.
     *
     * @param string $email O endereço de e-mail do usuário a ser carregado.
     * @return array|null Um array associativo contendo os dados do usuário, ou null se não encontrado.
     */
    public function loadUserByEmail(string $email): ?array
    {
        $stmt = $this->conexao->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->bindParam(":email", $email);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $user ?: null;
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
                SET nome = :nome, 
                    valor = :valor, 
                    categoria = :categoria, 
                    descricao = :descricao, 
                    quantidade = :quantidade
                WHERE id = :id";

        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":nome", $produto->nome);
        $stmt->bindValue(":valor", $produto->valor);
        $stmt->bindValue(":categoria", $produto->categoria);
        $stmt->bindValue(":descricao", $produto->descricao);
        $stmt->bindValue(":quantidade", $produto->quantidade);
        $stmt->bindValue(":id", $produto->id);

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
        $sql = "SELECT * FROM produtos WHERE id = :id";
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
     
        $sql = "DELETE FROM PRODUTOS WHERE PRDCODIGO = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $produto = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $produto ? $produto : false;


        return self::$conexao;
    }

    

    public function salvarPedidoItem(PedidoItem $item): int 
    {
        $sql = "INSERT INTO pedido_itens (itnPdd, itnPrd, itnVlr, itnQntd)
            VALUES (:pdd, :prd, :vlr, :qtd)";
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":pdd", $item->itnPdd);
        $stmt->bindValue(":prd", $item->itnPrd);
        $stmt->bindValue(":vlr", $item->itnVlr);
        $stmt->bindValue(":qtd", $item->itnQntd);

    $stmt->execute();
    return $this->conexao->lastInsertId();


    }

    public function salvarPedido(Pedido $pedido): int
    {
        $sql = "INSERT INTO pedidos (pddCli, pddData, pddTipo, pddVlrTotal, pddForn)
            VALUES (:cli, :data, :tipo, :total, :forn)";
        $stmt = $this->conexao->prepare($sql);

        $stmt->bindValue(":cli", $pedido->pddCli);
        $stmt->bindValue(":data", $pedido->pddData);
        $stmt->bindValue(":tipo", $pedido->pddTipo);
        $stmt->bindValue(":total", $pedido->pddVlrTotal);
        $stmt->bindValue(":forn", $pedido->pddForn);

        $stmt->execute();
        return $this->conexao->lastInsertId();
    }
}

