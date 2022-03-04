<?php

namespace App\Reports;

use App\DTO\Customers\InvalidCustomerDTO;
use App\Exports\InvalidCustomersExport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class InvalidCustomersReportBuilder
 * @package App\Reports
 */
final class InvalidCustomersReportBuilder
{
    /**
     * Path to director for store of report
     * @var string
     */
    const STORAGE_APP_DIRECTORY = 'app';

    /**
     * Path to file
     * @var string
     */
    private string $file_path;

    /**
     * Invalid customers list
     * @var InvalidCustomerDTO[]
     */
    private array $invalid_customers;

    /**
     * InvalidCustomersReportBuilder constructor
     * @param string $file_path
     * @param array $invalid_customers
     */
    public function __construct(string $file_path, array $invalid_customers)
    {
        $this->file_path = $file_path;
        $this->invalid_customers = $invalid_customers;
    }

    /**
     * Build report
     *
     * @return void
     */
    public function build(): void
    {
        $this->saveToFile($this->prepareRows());
    }

    /**
     * Prepare row for building
     *
     * @return array
     */
    private function prepareRows(): array
    {
        $result = [];

        foreach ($this->invalid_customers as $invalid_customer) {
            $result[] = $this->getOneRowCells($invalid_customer);
        }

        return $result;
    }

    /**
     * Get one row cells from invalid customer
     *
     * @param InvalidCustomerDTO $invalid_customer
     * @return array
     */
    private function getOneRowCells(InvalidCustomerDTO $invalid_customer): array
    {
        return [
            $invalid_customer->getCustomer()->getRawRow(),
            $invalid_customer->getInvalidFieldName()
        ];
    }

    /**
     * Save report rows to file
     *
     * @param array $rows
     * @return void
     */
    private function saveToFile(array $rows): void
    {
        Excel::store(new InvalidCustomersExport(collect($rows)), basename($this->file_path));
    }
}
