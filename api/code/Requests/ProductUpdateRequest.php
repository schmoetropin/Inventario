<?php
declare(strict_types=1);

namespace Api\Code\Requests;

use Api\Core\FormValidation;

class ProductUpdateRequest extends FormValidation
{
    /**
     * @var string
     */
    protected string $productIdUp = '';
    protected string $nameUpProd = '';
    protected string $inStorageUp = '';
    protected string $prodUpPrice = '';

    /**
     * Nome dos inputs do formulario para atualizar produto e suas regras para poderem ser validados
     * @return array
     */
    public function rules(): array
    {
        return [
            'productIdUp' => ['required', 'exists:id-products'],
            'nameUpProd' => ['required', 'min:3', 'max:20'],
            'inStorageUp' => ['required'],
            'prodUpPrice' => ['required']
        ];
    }

    /**
     * Nome amigavel para exibir caso haja erros
     * @return array
     */
    public function propertyNames(): array
    {
        return [
            'productIdUp' => 'Produto',
            'nameUpProd' => 'Nome do produto',
            'inStorageUp' => 'Quantidade no inventário',
            'prodUpPrice' => 'Preço do produto'
        ];
    }
}