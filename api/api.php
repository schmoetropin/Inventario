<?php
declare(strict_types=1);

require_once(__DIR__.DIRECTORY_SEPARATOR.'includes'.
    DIRECTORY_SEPARATOR.'includes.php');

use Api\Core\Request;
use Api\Code\Controllers\ProductsController;
use Api\Code\Controllers\HistoryController;

$req = new Request();
$prod = new ProductsController();
$hist = new HistoryController();

$data = $req->checkRequest();

// Adiciona um novo produto
if (isset($data['nameProd'])) {
    $prod->create($data);
}

// Exibe lista de produtos
if (isset($data['displayProds'])) {
    echo json_encode($prod->displayProducts());
}

// Exibe um produto em expecifico
if (isset($data['id'])) {
    echo json_encode($prod->displaySpecificProduct($data));
}

// Atualliza um produto
if (isset($data['productIdUp'])) {
    $prod->update($data);
}

// Exibe historico completo do inventario
if (isset($data['displayHistory'])) {
    echo json_encode($hist->displayHistory());
}

// Deleta produtos
if (isset($_POST['deleteProducts'])) {
    $data = json_decode($_POST['deleteProducts']);
    $prod->delete($data);
}