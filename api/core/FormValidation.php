<?php
declare(strict_types=1);

namespace Api\Core;

abstract class FormValidation extends Connection
{
    use CheckInputTrait;

    /**
     * Constantes, são as regras do formulario
     */
    private const REQUIRED = 'required';
    private const MIN_LEN = 'min';
    private const MAX_LEN = 'max';
    private const UNIQUE = 'unique';
    private const EXISTS = 'exists';

    /**
     * @var array
     */
    private array $data;
    private array $errors;

    /**
     * Função abstrata para todos os formularios preencherem as regras de detarminadas variaveis
     * @return array
     */
    abstract public function rules(): array;

    /**
     * Função abstrata para Exibir nomes amigáveis caso hja erros
     * @return array
     */
    abstract public function propertyNames(): array;

    /**
     * Retorna os erros caso haja apos preencher o formulario
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Valildacao do formulario de acordo com as regras colocadas na função 'rules'
     * @param array $data
     * @return bool
     */
    public function validate(array $data): bool
    {
        $this->data = $data;
        $this->setProperty();

        foreach ($this->rules() as $property => $rules) {
            foreach ($rules as $rule) {
                $value = $this->{$property};
                $array = explode(':', $rule);
                $ruleName = $array[0];
                $ruleValue = null;
                if (isset($array[1])) {
                    $ruleValue = $array[1];
                }

                if ($ruleName === self::REQUIRED && !$this->checkRequired($value)) {
                    $this->setErrors($property, $ruleName, $ruleValue);
                }

                if ($ruleName === self::MIN_LEN && !$this->checkMinLength($value, $ruleValue)) {
                    $this->setErrors($property, $ruleName, $ruleValue);
                }

                if ($ruleName === self::MAX_LEN && !$this->checkMaxLength($value, $ruleValue)) {
                    $this->setErrors($property, $ruleName, $ruleValue);
                }

                if ($ruleName === self::UNIQUE && !$this->checkUnique($value, $ruleValue)) {
                    $this->setErrors($property, $ruleName, $ruleValue);
                }

                if ($ruleName === self::EXISTS && !$this->checkExists($value, $ruleValue)) {
                    $this->setErrors($property, $ruleName, $ruleValue);
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Seta as variaveis com os valores inseridos no formulario
     * @return void
     */
    private function setProperty(): void
    {
        foreach ($this->data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }

    /**
     * Insere os erros na array '$errors'
     * @param string $property
     * @param string $ruleName
     * @param string $value
     * @return void
     */
    private function setErrors(
        string $property, string $ruleName, string $ruleValue = null
    ): void {
        $propertyName = $this->propertyNames()[$property];
        $this->errors[$propertyName] = $this->createError($ruleName, $ruleValue);
    }

    /**
     * Cria o erro de acordo com a regra que foi colocada
     * @param string $ruleName
     * @param string $value
     * @return string
     */
    private function createError(string $ruleName, string $ruleValue = null): string
    {
        $error = $this->errors()[$ruleName];
        $error = str_replace($ruleName, $ruleValue, $error);
        return $error;
    }

    /**
     * Array com todos os erros popssiveis
     * @return array
     */
    private function errors(): array
    {
        return [
            self::REQUIRED => 'Este campo é obrigatório',
            self::MIN_LEN => 'O mínimo de caracteres são '.self::MIN_LEN,
            self::MAX_LEN => 'O máximo de caracteres são '.self::MAX_LEN,
            self::UNIQUE => 'Este valor ja foi registrado',
            self::EXISTS => 'Este produto não existe'
        ];
    }
}