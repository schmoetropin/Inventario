<?php
declare(strict_types=1);

namespace Api\Code\Requests;

use Api\Core\FormValidation;

class ProductCreateRequest extends FormValidation
{
    /**
     * @var string
     */
    protected string $nameProd = '';
    protected string $codeProd = '';
    protected string $inStorage = '';
    protected string $prodPrice = '';

    /**
     * Nome dos inputs do formulario para criar produto e suas regras para poderem ser validados
     * @return array
     */
    public function rules(): array
    {
        return [
            'nameProd' => ['required', 'min:3', 'max:20'],
            'codeProd' => ['required', 'unique:code-products', 'min:4'],
            'inStorage' => ['required'],
            'prodPrice' => ['required']
        ];
    }

    /**
     * Nome amigavel para exibir caso haja erros
     * @return array
     */
    public function propertyNames(): array
    {
        return [
            'nameProd' => 'Nome do produto',
            'codeProd' => 'Código do produto',
            'inStorage' => 'Quantidade no inventário',
            'prodPrice' => 'Preço do produto'
        ];
    }
}