<?php
declare(strict_types=1);

// Exibe os erros em modo de desenvolvimento
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Constantes com as pastas dos arquivos
define('ROOT', dirname(__DIR__).DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
define('API', ROOT.'api'.DIRECTORY_SEPARATOR);
define('CODE', API.'code'.DIRECTORY_SEPARATOR);
define('CORE', API.'core'.DIRECTORY_SEPARATOR);
define('CONTROLLERS', CODE.'Controllers'.DIRECTORY_SEPARATOR);
define('MODELS', CODE.'Models'.DIRECTORY_SEPARATOR);
define('REQUESTS', CODE.'Requests'.DIRECTORY_SEPARATOR);

// Requerimentos
//Core
require_once(CORE.'Connection.php');
require_once(CORE.'Model.php');
require_once(CORE.'Query.php');
require_once(CORE.'CheckInputTrait.php');
require_once(CORE.'FormValidation.php');
require_once(CORE.'Request.php');

// Models
require_once(MODELS.'Product.php');
require_once(MODELS.'History.php');

// Requests
require_once(REQUESTS.'ProductCreateRequest.php');
require_once(REQUESTS.'ProductUpdateRequest.php');

// Controllers
require_once(CONTROLLERS.'HistoryController.php');
require_once(CONTROLLERS.'ProductsController.php');