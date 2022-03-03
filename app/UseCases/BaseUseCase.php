<?php

declare(strict_types=1);

namespace App\UseCases;

use App\DTO\BaseDTO;
use App\Exceptions\InvalidInputDTOException;

/**
 * Class BaseUseCase
 * @package App\UseCases
 */
abstract class BaseUseCase
{
    /**
     * DTO with data for use case
     * @var BaseDTO
     */
    protected BaseDTO $input_dto;

    /**
     * Set input data for use case
     *
     * @param BaseDTO $input_dto
     * @return $this
     * @throws InvalidInputDTOException
     */
    public function setInputDTO(BaseDTO $input_dto): self
    {
        $input_dto_class = $this->getInputDTOClass();

        if ($input_dto_class && $input_dto_class != get_class($input_dto)) {
            throw new InvalidInputDTOException();
        }

        $this->input_dto = $input_dto;

        return $this;
    }

    /**
     * Get available input DTO class name
     *
     * @return string|null
     */
    abstract protected function getInputDTOClass(): ?string;

    /**
     * Execute use case
     *
     * @return void
     */
    abstract public function execute(): void;
}
