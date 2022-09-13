<?php
declare(strict_types=1);

namespace Api\Core;

use PDO;
use PDOException;

abstract class Connection
{
    /**
     * @var string
     */
    private string $host = '127.0.0.1';
    private string $db = 'inventory';
    private string $user = 'root';
    private string $pass = '';

    /**
     * @var PDO
     */
    private PDO $connection;

    /**
     * Conecte no banco de dados
     * @return PDO|null
     */
    public function con(): ?PDO
    {
        try{
            $mysql = 'mysql:host='.$this->host.';dbname='.$this->db;
            $this->connection = new PDO($mysql, $this->user, $this->pass);
            $this->connection->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            return $this->connection;
        } catch (PDOException $e) {
            // echo $e->getMessage();
            return null;
        }
    }
}