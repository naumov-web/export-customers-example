<?php

namespace App\DTO\Input\Customers;

use App\DTO\BaseInputDTO;

/**
 * Class ExportCustomersInputDTO
 * @package App\DTO\Input\Customers
 */
final class ExportCustomersInputDTO extends BaseInputDTO
{
    /**
     * Customers list
     * @var array
     */
    private array $customers = [];

    /**
     * ExportCustomersInputDTO constructor
     * @param CustomerInputDTO[] $customers
     */
    public function __construct(array $customers)
    {
        $this->customers = $customers;
    }

    /**
     * Get customers list
     *
     * @return array
     */
    public function getCustomers(): array
    {
        return $this->customers;
    }
}
