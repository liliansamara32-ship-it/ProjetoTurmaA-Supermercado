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

        return self::$conexao;
    }
}
