<?php

declare(strict_types=1);

namespace App\DTO\Common;

/**
 * Class FilterDTO
 * @package App\DTO
 */
final class FilterDTO
{
    /**
     * Field name
     * @var string
     */
    private string $field;

    /**
     * Operation value
     * @var string
     */
    private string $operation;

    /**
     * Comparable value
     * @var mixed
     */
    private mixed $value;

    /**
     * FilterDTO constructor
     * @param string $field
     * @param string $operation
     * @param null $value
     */
    public function __construct(string $field, string $operation, $value = null)
    {
        $this->field = $field;
        $this->operation = $operation;
        $this->value = $value;
    }

    /**
     * Get field value
     *
     * @return string
     */
    public function getField(): string
    {
        return $this->field;
    }

    /**
     * Get operation value
     *
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * Get comparable value
     *
     * @return mixed
     */
    public function getValue(): mixed
    {
        return $this->value;
    }

    /**
     * Get filter arguments count
     *
     * @return int
     */
    public function getArgumentsCount(): int
    {
        return 2 + (is_null($this->value) ? 0 : 1);
    }
}
