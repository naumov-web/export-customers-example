<?php

namespace App\UseCases\Customers;

use App\DTO\Common\FilterDTO;
use App\DTO\Customers\InvalidCustomerDTO;
use App\DTO\Input\Customers\CustomerInputDTO;
use App\DTO\Input\Customers\ExportCustomersInputDTO;
use App\Enums\CustomerColumnNamesEnum;
use App\Reports\InvalidCustomersReportBuilder;
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
     * Report file name
     * @var string
     */
    const REPORT_FILE_NAME = 'invalid-customers.xlsx';

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
     * Flatten countries list
     * @var array
     */
    private array $flatten_countries = [];

    /**
     * Invalid customers list
     * @var InvalidCustomerDTO[]
     */
    private array $invalid_customers = [];

    /**
     * Is invalid customers report was created
     * @var bool
     */
    private bool $is_invalid_customers_report_created = false;

    /**
     * Path to file with report
     * @var string
     */
    private string $file_path;

    /**
     * ExportCustomersUseCase constructor
     * @param CustomersRepository $customers_repository
     */
    public function __construct(CustomersRepository $customers_repository)
    {
        $this->customers_repository = $customers_repository;
    }

    /**
     * Get value flag, which enabled, if report was created
     *
     * @return bool
     */
    public function isInvalidCustomersReportCreated(): bool
    {
        return $this->is_invalid_customers_report_created;
    }

    /**
     * Get file path value
     *
     * @return string
     */
    public function getFilePath(): string
    {
        return $this->file_path;
    }

    /**
     * @inheritDoc
     */
    protected function getInputDTOClass(): ?string
    {
        return ExportCustomersInputDTO::class;
    }

    /**
     * Prepare data before export customers
     *
     * @return void
     */
    private function prepareData()
    {
        $this->countries = config('countries.available_countries');

        foreach ($this->countries as $country) {
            $this->flatten_countries[$country['name']] = $country['alpha_3'];
        }
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
        $this->prepareData();

        foreach ($input_dto->getCustomers() as $customer) {
            $this->saveCustomer($customer);
        }

        if (count($this->invalid_customers)) {
            $this->file_path = base_path(self::REPORT_FILE_NAME);
            $builder = new InvalidCustomersReportBuilder($this->file_path, $this->invalid_customers);
            $builder->build();
            $this->is_invalid_customers_report_created = true;
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
            $this->addInvalidCustomer($customer, CustomerColumnNamesEnum::EMAIL);
            return;
        }

        if (!$this->isAgeValid($customer->getAge())) {
            $this->addInvalidCustomer($customer, CustomerColumnNamesEnum::AGE);
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
        return $this->flatten_countries[$location] ?? null;
    }

    /**
     * Check is email valid
     *
     * @param string $email
     * @return bool
     */
    private function isEmailValid(string $email): bool
    {
        return is_email($email);
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

    /**
     * Check is location valid
     *
     * @param string $location
     * @return bool
     */
    private function isLocationValid(string $location): bool
    {
        return isset($this->flatten_countries[$location]);
    }

    /**
     * Add new invalid customer
     *
     * @param CustomerInputDTO $customer
     * @param string $invalid_field_name
     * @return void
     */
    private function addInvalidCustomer(CustomerInputDTO $customer, string $invalid_field_name)
    {
        $this->invalid_customers[] = new InvalidCustomerDTO($customer, $invalid_field_name);
    }
}
