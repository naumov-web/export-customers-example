<?php

namespace App\DTO\Customers;

use App\DTO\BaseDTO;
use App\DTO\Input\Customers\CustomerInputDTO;

/**
 * Class InvalidCustomerDTO
 * @package App\DTO\Customers
 */
final class InvalidCustomerDTO extends BaseDTO
{
    /**
     * Customer instance DTO
     * @var CustomerInputDTO
     */
    private CustomerInputDTO $customer;

    /**
     * Invalid field name
     * @var string
     */
    private string $invalid_field_name;

    /**
     * InvalidCustomerDTO constructor
     * @param CustomerInputDTO $customer
     * @param string $invalid_field_name
     */
    public function __construct(CustomerInputDTO $customer, string $invalid_field_name)
    {
        $this->customer = $customer;
        $this->invalid_field_name = $invalid_field_name;
    }

    /**
     * Get customer instance DTO
     *
     * @return CustomerInputDTO
     */
    public function getCustomer(): CustomerInputDTO
    {
        return $this->customer;
    }

    /**
     * Get invalid field name value
     *
     * @return string
     */
    public function getInvalidFieldName(): string
    {
        return $this->invalid_field_name;
    }
}
