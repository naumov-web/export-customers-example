<?php

declare(strict_types=1);

namespace App\UseCases;

use App\Enums\UseCaseSystemNamesEnum;
use App\Exceptions\UseCaseNotFoundException;
use App\UseCases\Customers\ExportCustomersUseCase;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class UseCaseFactory
 * @package App\UseCases
 */
final class UseCaseFactory
{
    /**
     * Use cases mapping
     * @var array
     */
    private array $use_cases_mapping = [
        UseCaseSystemNamesEnum::EXPORT_CUSTOMERS => ExportCustomersUseCase::class,
    ];

    /**
     * Create use case instance
     *
     * @param string $system_name
     * @return BaseUseCase
     * @throws BindingResolutionException
     * @throws UseCaseNotFoundException
     */
    public function createUseCase(string $system_name): BaseUseCase
    {
        $class_name = $this->use_cases_mapping[$system_name] ?? null;

        if (!$class_name) {
            throw new UseCaseNotFoundException();
        }

        return app()->make($class_name);
    }
}
