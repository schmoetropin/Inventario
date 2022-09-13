<?php
declare(strict_types=1);

namespace Api\Core;

trait CheckInputTrait
{
    /**
     * Checa se o input foi preenchido caso o mesmo seja requerido
     * @param string $value
     * @return bool
     */
    public function checkRequired(string $value): bool
    {
        if ($value !== '') {
            return true;
        }
        return false;
    }

    /**
     * Checa se o input possui o minimo de caracteres necessarios
     * @param string $value
     * @param string $ruleValue
     * @return bool
     */
    public function checkMinLength(string $value, string $ruleValue): bool
    {
        if (strlen($value) >= (int)$ruleValue) {
            return true;
        }
        return false;
    }

    /**
     * Checa se o input passou do maximo de caracteres necessarios
     * @param string $value
     * @param string $ruleValue
     * @return bool
     */
    public function checkMaxLength(string $value, string $ruleValue): bool
    {
        if (strlen($value) <= (int)$ruleValue) {
            return true;
        }
        return false;
    }

    /**
     * Checa se o valor é único ou se ja existe um banco de dados
     * @param string $value
     * @param string $ruleValue
     * @return bool
     */
    public function checkUnique(string $value, string $ruleValue): bool
    {
        $query = new Query();
        $array = explode('-', $ruleValue);
        $query->tableName = $array[1];
        $column = $array[0];
        $query->select([$column])
            ->where([$column => $value]);
        if ($query->rowCount() === 0) {
            return true;
        }
        return false;
    }

    /**
     * Checa se o determinado item existe na mesa do banco de dados
     * @param string $value
     * @param string $ruleValue
     * @return bool
     */
    public function checkExists(string $value, string $ruleValue): bool
    {
        $query = new Query();
        $array = explode('-', $ruleValue);
        $query->tableName = $array[1];
        $column = $array[0];
        $query->select([$column])
            ->where([$column => $value]);
        if ($query->rowCount() > 0) {
            return true;
        }
        return false;
    }
}