<?php

namespace App\DTO\Input\Customers;

use App\DTO\BaseDTO;

/**
 * Class CustomerInputDTO
 * Customer
 * @package App\DTO\Input\Customers
 */
final class CustomerInputDTO extends BaseDTO
{
    /**
     * Full name value
     * @var string
     */
    private string $full_name;

    /**
     * Email value
     * @var string
     */
    private string $email;

    /**
     * Age value
     * @var string
     */
    private string $age;

    /**
     * Location value
     * @var string
     */
    private string $location;

    /**
     * Raw row value
     * @var string
     */
    private string $raw_row;

    /**
     * CustomerInputDTO constructor
     * @param string $full_name
     * @param string $email
     * @param string $age
     * @param string $location
     */
    public function __construct(string $full_name, string $email, string $age, string $location = '')
    {
        $this->full_name = $full_name;
        $this->email = $email;
        $this->age = $age;
        $this->location = $location;
    }

    /**
     * Set raw row value
     *
     * @param string $raw_row
     * @return $this
     */
    public function setRawRow(string $raw_row): self
    {
        $this->raw_row = $raw_row;

        return $this;
    }

    /**
     * Get full name value
     *
     * @return string
     */
    public function getFullName(): string
    {
        return $this->full_name;
    }

    /**
     * Get email value
     *
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Get age value
     *
     * @return string
     */
    public function getAge(): string
    {
        return $this->age;
    }

    /**
     * Get location value
     *
     * @return string
     */
    public function getLocation(): string
    {
        return $this->location;
    }

    /**
     * Get raw row value
     *
     * @return string
     */
    public function getRawRow(): string
    {
        return $this->raw_row;
    }
}
