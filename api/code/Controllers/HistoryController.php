<?php
declare(strict_types=1);

namespace Api\Code\Controllers;

use Api\Code\Models\History;

class HistoryController
{
    /**
     * @var History
     */
    private History $histModel;

    public function __construct()
    {
        $this->histModel = new History();
    }

    /**
     * Atualiza historico do estoque
     * @param string $name
     * @param int $newStorage
     * @param string $code
     * @return void
     */
    public function updateHistory(string $name, int $newStorage, string $code): void
    {
        if (
            $this->checkIfProductHistoporyCodeExists($code) === 0 ||
            ($name !== $this->getName($code) || $newStorage !== $this->getNewStorage($code))
        ) {
            $date = date('Y-m-d H:i:s');
            $this->histModel->insert([
                'code' => $code,
                'name' => $name,
                'new_storage' => $newStorage,
                'updated_at' => $date
            ]);
        }
    }

    /**
     * Exibe todo o historico do estoque
     * @return array
     */
    public function displayHistory(): array
    {
        $data = $this->histModel->select(['*'])
            ->order('id', 'desc')
            ->getData();
        return $data;
    }

    /**
     * Checa se o codigo ja existe no historico caso necessite adicionar um novo produto
     * @param string $code
     * @return int
     */
    public function checkIfProductHistoporyCodeExists(string $code): int
    {
        return $this->histModel->select(['*'])
            ->where(['code' => $code])
            ->rowCount();
    }

    /**
     * Getters
     */
    public function getName(string $code): string
    {
        return $this->histModel->select(['name'])
            ->where(['code' => $code])
            ->getData()[0]['name'];
    }

    public function getNewStorage(string $code): int
    {
        return $this->histModel->select(['new_storage'])
            ->where(['code' => $code])
            ->getData()[0]['new_storage'];
    }
}