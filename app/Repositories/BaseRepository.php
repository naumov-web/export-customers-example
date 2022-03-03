<?php

namespace App\Repositories;

use App\DTO\Common\FilterDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class BaseRepository
 * @package App\Repositories
 */
abstract class BaseRepository
{
    /**
     * Get model class name
     *
     * @return string
     */
    protected abstract function getModelClass(): string;

    /**
     * Apply filters to query
     *
     * @param Builder $query
     * @param array $filters
     * @return Builder
     */
    protected function applyFilters(Builder $query, array $filters): Builder
    {
        foreach($filters as $filter) {
            /**
             * @var FilterDTO $filter
             */
            if ($filter->getArgumentsCount() == 2) {

                if ($filter->getOperation() == 'IS NOT NULL') {
                    $query->whereNotNull($filter->getField());
                    continue;
                }

                if ($filter->getOperation() == 'IS NULL') {
                    $query->whereNull($filter->getField());
                    continue;
                }
            }

            if ($filter->getArgumentsCount() == 3) {

                if ($filter->getOperation() == 'IN') {
                    $query->whereIn($filter->getField(), $filter->getValue());
                    continue;
                }

                if ($filter->getOperation() == 'NOT IN') {
                    $query->whereNotIn($filter->getField(), $filter->getValue());
                    continue;
                }

                $query->where($filter->getField(), $filter->getOperation(), $filter->getValue());
            }
        }

        return $query;
    }

    /**
     * Store new model to database
     *
     * @param array $data
     * @return Model
     */
    public function store(array $data): Model
    {
        $model_class = $this->getModelClass();

        /**
         * @var Model $model
         */
        $model = new $model_class();
        $model->fill($data);
        $model->save();

        return $model;
    }

    /**
     * Update model
     *
     * @param Model $model
     * @param array $data
     * @param bool $update_timestamps
     * @return Model
     */
    public function update(Model $model, array $data, bool $update_timestamps = true): Model
    {
        if (!$update_timestamps) {
            $model->timestamps = false;
        }

        $model->update($data);
        $model->refresh();

        return $model;
    }

    /**
     * Get first model by simple filters
     *
     * @param array $filters
     * @return Model|null
     */
    public function getFirstByFilters(array $filters): ?Model
    {
        $model_class = $this->getModelClass();

        /**
         * @var Builder $query
         */
        $query = $model_class::query();

        $query = $this->applyFilters($query, $filters);

        return $query->first();
    }
}
