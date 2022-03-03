<?php

namespace App\Repositories;

use App\Models\Customer;

/**
 * Class CustomersRepository
 * @package App\Repositories
 */
final class CustomersRepository extends BaseRepository
{

    /**
     * @inheritDoc
     */
    protected function getModelClass(): string
    {
        return Customer::class;
    }
}
