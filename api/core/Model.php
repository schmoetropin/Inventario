<?php
declare(strict_types=1);

namespace Api\Core;

use PDO;

abstract class Model extends Connection
{
    /**
     * Constantes
     */
    private const UPDATE = 'update';
    private const WHERE = 'where';

    /**
     * @var string
     */
    private string $query;

    /**
     * @var array
     */
    private array $data;

    /**
     * Função abstrata para retornar determinada mesa do banco de dados
     * @return string
     */
    abstract public function table(): string;

    /**
     * Função abstrata para retornar determinadas colunas de uma mesa
     * @return array
     */
    abstract public function columns(): array;

    /**
     * Insere dados no banco de dados
     * @param array
     * @return void
     */
    public function insert(array $data): void
    {
        $this->data = $data;

        $this->query = 'INSERT INTO '.$this->table().'('.implode(',', $this->columns()).')'.
            ' VALUES('.implode(',', $this->params()).')';
        
        $this->execute();
    }

    /**
     * Seleciona colunas de uma mesa
     * @param $columns
     * @return self
     */
    public function select(array $columns): self
    {
        $this->query = 'SELECT '.implode(',', $columns).' FROM '.$this->table();
        return $this;
    }

    /**
     * Atualiza determinado item
     * @param array $data
     * @return self
     */
    public function update(array $data): self
    {
        $this->data = $data;
        $colAndPar = $this->columnsAndParams($data, self::UPDATE);
        $this->query = 'UPDATE '.$this->table().' SET '.$colAndPar;
        return $this;
    }

    /**
     * Delete determinado item
     * @return self
     */
    public function delete(): self
    {
        $this->query = 'DELETE FROM '.$this->table();
        return $this;
    }

    /**
     * Condição 'where' para arualizar, deletar ou selecionar determinado item
     * @param array $data
     * @return self
     */
    public function where(array $data): self
    {
        if (empty($this->data)) {
            $this->data = $data;
        } else {
            $this->data = array_merge($this->data, $data);
        }
        $colAndPar = $this->columnsAndParams($data, self::WHERE);
        $this->query .= ' WHERE '.$colAndPar;
        return $this;
    }

    /**
     * Ordenamento da lista
     * @return self
     */
    public function order(string $column, string $order): self
    {
        $this->query .= ' ORDER BY '.$column.' '.$order;
        return $this;
    }

    /**
     * Cria params simples em uma querey 'insert'
     * @return array
     */
    private function params(): array
    {
        $array = [];
        foreach ($this->columns() as $col) {
            $array[] = ':'.$col;
        }
        return $array;
    }

    /**
     * Criam params para casos de 'update', e de seleção 'where'
     * @param array $data
     * @param string $type
     * @return string
     */
    private function columnsAndParams(array $data, string $type): string
    {
        $i = 1;
        $str = '';
        $count = count($data);
        foreach ($data as $key => $value) {
            $str .= $key.'=:'.$key;
            if ($i < $count) {
                if ($type === self::UPDATE) {
                    $str .= ' , ';
                } else {
                    $str .= ' AND ';
                }
            }
            $i++;
        }
        return $str;
    }

    /**
     * Retorna o numero de itens em determinada mesa
     * @return int
     */
    public function rowCount(): int
    {
        $prepare = $this->con()->prepare($this->query);
        if (!empty($this->data)) {
            foreach ($this->data as $key => $value) {
                $prepare->bindValue(':'.$key, $value);
            }
        }
        $prepare->execute();
        $this->data = [];
        return $prepare->rowCount();
    }

    /**
     * Executa query
     * @return void
     */
    public function execute(): void
    {
        $prepare = $this->con()->prepare($this->query);
        if (!empty($this->data)) {
            foreach ($this->data as $key => $value) {
                $prepare->bindValue(':'.$key, $value);
            }
        }
        $prepare->execute();
        $this->data = [];
    }

    /**
     * retorna dados do banco de dados atravez do pdo fetch
     * @return array
     */
    public function getData(): array
    {
        $prepare = $this->con()->prepare($this->query);
        if (!empty($this->data)) {
            foreach ($this->data as $key => $value) {
                $prepare->bindValue(':'.$key, $value);
            }
        }
        $prepare->execute();

        $array = [];
        $i = 0;
        while ($row = $prepare->fetch(PDO::FETCH_ASSOC)) {
            $array[$i] = $row;
            $i++;
        }
        $this->data = [];
        return $array;
    }
}