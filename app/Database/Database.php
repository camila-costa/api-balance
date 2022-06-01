<?php

namespace App\Database;

use PDO;

class Database
{
    private $conexao;

    public function __construct(PDO $pdo)
    {
        $this->conexao = $pdo;
    }

    public function executar(string $sql, array $params = [])
    {
        $stmt = $this->conexao->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public function consultar(string $sql, array $params = [])
    {
        $result = $this->executar($sql, $params);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function clearAll(string $table)
    {
        $this->executar('truncate table ' . $table);
    }

    public function beginTransaction()
    {
        return $this->conexao->beginTransaction();
    }

    public function commit()
    {
        return $this->conexao->commit();
    }

    public function rollBack()
    {
        return $this->conexao->rollBack();
    }
}