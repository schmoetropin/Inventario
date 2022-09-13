<?php
declare(strict_types=1);

namespace Api\Code\Models;

use Api\Core\Model;

class Product extends Model
{
    /**
     * @var string
     */
    protected string $tableName = 'products';

    /**
     * @var array
     */
    protected array $fillableColumns = ['name', 'code', 'in_storage', 'price'];

    /**
     * Retorna o nome da mesa no banco de dados
     * @return string
     */
    public function table(): string
    {
        return $this->tableName;
    }

    /**
     * Retorna as colunas que podem/devem ser preenchidas ao inseris dados no banco de dados
     * @return array
     */
    public function columns(): array
    {
        return $this->fillableColumns;
    }
}