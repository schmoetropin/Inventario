<?php
declare(strict_types=1);

namespace Api\Code\Controllers;

use Api\Code\Requests\ProductCreateRequest;
use Api\Code\Requests\ProductUpdateRequest;
use Api\Code\Models\Product;

class ProductsController
{
    /**
     * @var Product
     */
    private Product $prodModel;

    /**
     * @var ProductCreateRequest
     */
    private ProductCreateRequest $prodCrReq;

    /**
     * @var ProductUpdateRequest
     */
    private ProductUpdateRequest $prodUpReq;

    /**
     * @var HistoryController
     */
    private HistoryController $hisCon;

    public function __construct()
    {
        $this->prodModel = new Product();
        $this->prodCrReq = new ProductCreateRequest();
        $this->prodUpReq = new ProductUpdateRequest();
        $this->hisCon = new HistoryController();
    }

    /**
     * Adiciona um novo produto e adiciona historico do produto no estoque
     * @param array $data
     * @return void
     */
    public function create(array $data): void
    {
        if ($this->prodCrReq->validate($data)) {
            $this->prodModel->insert([
                'name' => $data['nameProd'], 
                'code' => $data['codeProd'], 
                'in_storage' => (int)$data['inStorage'], 
                'price' => (float)$data['prodPrice']
            ]);
            $this->hisCon->updateHistory($data['nameProd'], (int)$data['inStorage'], $data['codeProd']);
            echo 'productCreated';
        } else {
            echo json_encode($this->prodCrReq->getErrors());
        }
    }

    /**
     * Atualiza dados do produto e atualiza o historico de estoque
     * @param array $data
     * @return void 
     */
    public function update(array $data): void
    {
        if ($this->prodUpReq->validate($data)) {
            $prodId = (int)$data['productIdUp'];
            $oldStorage = $this->getInStorage($prodId);
            $code = $this->getCode($prodId);
            $this->prodModel->update([
                'name' => $data['nameUpProd'], 
                'in_storage' => (int)$data['inStorageUp'], 
                'price' => (float)$data['prodUpPrice']
            ])
                ->where(['id' => $prodId])
                ->execute();
            $this->hisCon->updateHistory($data['nameUpProd'], (int)$data['inStorageUp'], $code);
            echo 'productUpdated';
        } else {
            echo json_encode($this->prodUpReq->getErrors());
        }
    }

    /**
     * Deleta produto da lista junto com todo o seu historico
     * @param array $data
     * @return void
     */
    public function delete(array $data): void
    {
        $count = count($data);
        for ($i = 0; $i < $count; $i++) {
            $this->prodModel->delete()
                ->where([
                    'id' => $data[$i]
                ])
                ->execute();
        }
    }

    /**
     * Exibe toda a lista de produtos
     * @return array
     */
    public function displayProducts(): array
    {
        $data = $this->prodModel->select(['*'])
            ->order('id', 'desc')
            ->getData();
        return $data;
    }

    /**
     * Exibe os dados detalhados de um produto especifico
     * @param array $data
     * @return array
     */
    public function displaySpecificProduct(array $data): array
    {
        $data = $this->prodModel->select(['*'])
            ->where(['id' => $data['id']])
            ->order('id', 'desc')
            ->getData()[0];
        return $data; 
    }

    /**
     * Getters
     */
    public function getName(int $id): string
    {
        return $this->prodModel->select(['name'])
            ->where(['id' => $id])
            ->getData()[0]['name'];
    }

    public function getInStorage(int $id): int
    {
        return $this->prodModel->select(['in_storage'])
            ->where(['id' => $id])
            ->getData()[0]['in_storage'];
    }

    public function getCode(int $id): string
    {
        return $this->prodModel->select(['code'])
            ->where(['id' => $id])
            ->getData()[0]['code'];
    }
}