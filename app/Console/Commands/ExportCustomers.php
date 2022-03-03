<?php

namespace App\Console\Commands;

use App\DTO\Input\Customers\ExportCustomersInputDTO;
use App\Enums\UseCaseSystemNamesEnum;
use App\Exceptions\InvalidInputDTOException;
use App\Exceptions\UseCaseNotFoundException;
use App\Readers\ReadCustomersFromCsvFile;
use App\UseCases\UseCaseFactory;
use Illuminate\Console\Command;
use Illuminate\Contracts\Container\BindingResolutionException;

/**
 * Class ExportCustomers
 * @package App\Console\Commands
 */
final class ExportCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'export:customers {file_path}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export customers from file';

    /**
     * Use case factory instance
     * @var UseCaseFactory
     */
    private UseCaseFactory $use_case_factory;

    /**
     * Reader for reading customers from CSV-file
     * @var ReadCustomersFromCsvFile
     */
    private ReadCustomersFromCsvFile $reader;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UseCaseFactory $use_case_factory, ReadCustomersFromCsvFile $reader)
    {
        $this->use_case_factory = $use_case_factory;
        $this->reader = $reader;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws BindingResolutionException
     * @throws UseCaseNotFoundException
     * @throws InvalidInputDTOException
     */
    public function handle(): int
    {
        $this->reader->setFilePath(base_path($this->argument('file_path')));
        $customers = $this->reader->getCustomers();

        $use_case = $this->use_case_factory->createUseCase(UseCaseSystemNamesEnum::EXPORT_CUSTOMERS);
        $use_case->setInputDTO(new ExportCustomersInputDTO($customers));
        $use_case->execute();

        return Command::SUCCESS;
    }
}
