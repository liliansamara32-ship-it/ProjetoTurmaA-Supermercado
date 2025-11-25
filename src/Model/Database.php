<?php
namespace GrupoA\Supermercado\Model;

/**
 * Classe Database
 *
 * Responsável por gerenciar a conexão com o banco de dados
 * e fornecer métodos para interagir com as tabelas.
 */
class Database
{
    /**
     * @var \PDO $conexao Objeto PDO para a conexão com o banco de dados.
     */
    private \PDO $conexao;

    /**
     * Construtor da classe Database.
     *
     * Inicializa a conexão com o banco de dados utilizando variáveis de ambiente.
     * Em caso de falha na conexão, encerra a aplicação.
     */
    public function __construct()
    {
        // Configurações do banco de dados, crie 
        // variáveis de ambiente para que a conexão 
        // com o banco de dados seja feita.
        $dbHost = getenv('DB_HOST') ?: '127.0.0.1';
        $dbName = getenv('DB_NAME') ?: 'PRJ2DSA';
        $dbUser = getenv('DB_USER') ?: 'root';
        $dbPass = getenv('DB_PASS') ?: '';

        try {
            $this->conexao = new \PDO(
                "mysql:host={$dbHost};dbname={$dbName}",
                $dbUser,
                $dbPass
            );
            $this->conexao->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            die("Falha na conexão com o banco de dados: " . $e->getMessage());
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

    public function loadUserByName(string $nome): ?array
{
    $stmt = $this->conexao->prepare("SELECT * FROM usuarios WHERE nome = :nome");
    $stmt->bindParam(":nome", $nome);
    $stmt->execute();

    $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

    return $user ?: null;
}

    // bla bla bla

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
    public function deletarProduto($id): bool{
     
        $sql = "DELETE FROM PRODUTOS WHERE PRDCODIGO = :id";
        $stmt = $this->conexao->prepare($sql);
        $stmt->bindValue(":id", $id);
        $stmt->execute();

        $produto = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $produto ? $produto : false;


    }
}
