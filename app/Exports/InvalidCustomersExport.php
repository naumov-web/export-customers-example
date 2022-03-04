<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;

/**
 * Class InvalidCustomersExport
 * @package App\Business\ExportAdapters
 */
final class InvalidCustomersExport implements FromCollection
{
    /**
     * Blanks collection instance
     * @var Collection
     */
    private Collection $customers;

    /**
     * InvalidCustomersExport constructor
     * @param Collection $customers
     */
    public function __construct(Collection $customers)
    {
        $this->customers = $customers;
    }

    /**
     * @inheritDoc
     */
    public function collection(): Collection
    {
        return collect(
            array_merge(
                [
                    [
                        'Line',
                        'Error',
                    ]
                ],
                $this->customers->toArray()
            )
        );
    }
}
