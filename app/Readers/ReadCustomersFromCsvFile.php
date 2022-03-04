<?php

namespace App\Readers;

use App\DTO\Input\Customers\CustomerInputDTO;
use App\Enums\CustomerColumnNamesEnum;

/**
 * Class ReadCustomersFromCsvFile
 * @package App\Readers
 */
final class ReadCustomersFromCsvFile
{
    /**
     * Line delimiter value
     * @var string
     */
    const LINE_DELIMITER = "\r\n";

    /**
     * Cell delimiter value
     * @var string
     */
    const CELL_DELIMITER = ',';

    /**
     * File path value
     * @var string
     */
    private string $file_path;

    /**
     * Customers mapping schema
     * @var int[]
     */
    private array $customers_mapping = [
        CustomerColumnNamesEnum::FULL_NAME => 1,
        CustomerColumnNamesEnum::EMAIL => 2,
        CustomerColumnNamesEnum::AGE => 3,
        CustomerColumnNamesEnum::LOCATION => 4
    ];

    /**
     * Set file path value
     *
     * @param string $file_path
     * @return ReadCustomersFromCsvFile
     */
    public function setFilePath(string $file_path): ReadCustomersFromCsvFile
    {
        $this->file_path = $file_path;

        return $this;
    }

    /**
     * Get customers list
     *
     * @return array
     */
    public function getCustomers(): array
    {
        $result = [];
        $content = file_get_contents($this->file_path);
        $rows = explode(self::LINE_DELIMITER, $content);

        foreach ($rows as $i => $row) {
            if ($i === 0) {
                continue;
            }

            $customer = $this->getCustomerDTO($row);

            if ($customer) {
                $result[] = $customer;
            }
        }

        return $result;
    }

    /**
     * Get customer DTO from string row
     *
     * @param string $row
     * @return CustomerInputDTO|null
     */
    private function getCustomerDTO(string $row): ?CustomerInputDTO
    {
        $cells = explode(self::CELL_DELIMITER, $row);

        if (count($cells) - 1 != count($this->customers_mapping)) {
            return null;
        }

        return (new CustomerInputDTO(
            $cells[$this->customers_mapping[CustomerColumnNamesEnum::FULL_NAME]],
            $cells[$this->customers_mapping[CustomerColumnNamesEnum::EMAIL]],
            $cells[$this->customers_mapping[CustomerColumnNamesEnum::AGE]],
            $cells[$this->customers_mapping[CustomerColumnNamesEnum::LOCATION]]
        ))->setRawRow($row);
    }
}
