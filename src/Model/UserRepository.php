<?php

namespace GrupoA\Supermercado\Model;

class UserRepository
{
    private \PDO $conexao;

    public function __construct(\PDO $conexao)
    {
        $this->conexao = $conexao;
    }

    public function newUser(User $user): bool
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
}