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
     * Default location value
     * @var string
     */
    const DEFAULT_LOCATION = 'Unknown';

    /**
     * Min age value
     * @var int
     */
    const MIN_AGE = 18;

    /**
     * Max age value
     * @var int
     */
    const MAX_AGE = 99;

    /**
     * Customers repository instance
     * @var CustomersRepository
     */
    private CustomersRepository $customers_repository;

    /**
     * Available countries list
     * @var array
     */
    private array $countries;

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
        $this->countries = config('countries.available_countries');

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

        $model = $this->customers_repository->getFirstByFilters(
            [
                new FilterDTO('email', '=', $customer->getEmail())
            ]
        );
        $model_data = [
            'name' => $this->getFirstName($customer->getFullName()),
            'surname' => $this->getSurname($customer->getFullName()),
            'email' => $customer->getEmail(),
            'age' => (int)$customer->getAge(),
            'location' => $this->getLocation($customer->getLocation())
        ];
        $model_data['country_code'] = $this->getCountryCode($model_data['location']);

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

    /**
     * Get location value from raw location
     *
     * @param string $location
     * @return string
     */
    private function getLocation(string $location): string
    {
        if (!$this->isLocationValid($location)) {
            return self::DEFAULT_LOCATION;
        }

        return $location;
    }

    /**
     * Get country code from location
     *
     * @param string $location
     * @return string|null
     */
    private function getCountryCode(string $location): ?string
    {

    }

    private function isEmailValid(string $email): bool
    {

    }

    /**
     * Check is age valid
     *
     * @param string $age
     * @return bool
     */
    private function isAgeValid(string $age): bool
    {
        if (!check_is_integer($age)) {
            return false;
        }

        $age_int = (int)$age;

        return self::MAX_AGE >= $age_int && self::MIN_AGE <= $age_int;
    }

    private function isLocationValid(string $location): bool
    {

    }
}
