<?php

namespace App\UseCases\Customers;

use App\DTO\Common\FilterDTO;
use App\DTO\Input\Customers\CustomerInputDTO;
use App\DTO\Input\Customers\ExportCustomersInputDTO;
use App\Repositories\CustomersRepository;
use App\UseCases\BaseUseCase;

/**
 * Class ExportCustomersUseCase
 * @package App\UseCases\Customers
 */
final class ExportCustomersUseCase extends BaseUseCase
{
    /**
     * Customers repository instance
     * @var CustomersRepository
     */
    private CustomersRepository $customers_repository;

    /**
     * ExportCustomersUseCase constructor
     * @param CustomersRepository $customers_repository
     */
    public function __construct(CustomersRepository $customers_repository)
    {
        $this->customers_repository = $customers_repository;
    }

    /**
     * @inheritDoc
     */
    protected function getInputDTOClass(): ?string
    {
        return ExportCustomersInputDTO::class;
    }

    /**
     * @inheritDoc
     */
    public function execute(): void
    {
        /**
         * @var ExportCustomersInputDTO $input_dto
         */
        $input_dto = $this->input_dto;

        foreach ($input_dto->getCustomers() as $customer) {
            $this->saveCustomer($customer);
        }
    }

    /**
     * Save customer to database
     *
     * @param CustomerInputDTO $customer
     * @return void
     */
    private function saveCustomer(CustomerInputDTO $customer): void
    {
        if (!$this->isEmailValid($customer->getEmail())) {
            return;
        }

        if (!$this->isAgeValid($customer->getAge())) {
            return;
        }

        $model_data = [];
        $model = $this->customers_repository->getFirstByFilters(
            [
                new FilterDTO('email', '=', $customer->getEmail())
            ]
        );

        if ($model) {
            $this->customers_repository->update(
                $model,
                $model_data
            );
        } else {
            $this->customers_repository->store($model_data);
        }
    }

    /**
     * Get first name from full name
     *
     * @param string $full_name
     * @return string
     */
    private function getFirstName(string $full_name): string
    {
        return explode(' ', $full_name)[0];
    }

    /**
     * Get surname from full name
     *
     * @param string $full_name
     * @return string
     */
    private function getSurname(string $full_name): string
    {
        return explode(' ', $full_name)[1];
    }

    private function getLocation(string $location): string
    {

    }

    private function isEmailValid(string $email): bool
    {

    }

    private function isAgeValid(string $age): bool
    {

    }

    private function isLocationValid(string $location): bool
    {

    }
}
