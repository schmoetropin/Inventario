<?php
declare(strict_types=1);

namespace Api\Core;

class Request
{
    /**
     * passa tanto po post quanto get palos filtros para limpar caracteres especiais
     * @return array
     */
    public function checkRequest(): array
    {
        $array = [];

        foreach ($_POST as $key => $value) {
            $array[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        foreach ($_GET as $key => $value) {
            $array[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        return $array;
    }
}