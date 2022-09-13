<?php
declare(strict_types=1);

namespace Api\Core;;

class Query extends Model
{
    /**
     * @var string
     */
    public string $tableName = '';

    /**
     * retorna um nome de uma mesa caso nao precise criar um Model
     * @return string
     */
    public function table(): string
    {
        return $this->tableName;
    }

    public function columns(): array
    {
        return [];
    }
}