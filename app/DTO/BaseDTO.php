<?php

namespace App\DTO;

/**
 * Class BaseDTO
 * @package App\DTO
 */
abstract class BaseDTO
{
    /**
     * Mass assignment of object fields
     *
     * @param array $fields
     * @return BaseInputDTO
     */
    public function fill(array $fields): self
    {
        foreach ($fields as $field => $value) {
            $this->{$field} = $value;
        }

        return $this;
    }
}
