<?php

namespace App\Repositories\Eloquent\Common;

use App\Enum\SettingsEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * The **Repository Pattern** is an addition to the MVC pattern.
 * It fits right between the Controller and the Model so the controller never interacts directly with the Model.
 *
 * The aim is to:
 * - Lighten controllers by moving the query building and logic into the repositories.
 * - Improve readability and maintainability.
 * - Reduce code redundancy as the super-class `ResourceRepository` contains most frequent queries.
 *
 * @author Aushraful Alam
 */
abstract class BaseRepository
{
    /**
     * Item per page.
     *
     * @var int
     */
    protected $perPage;

    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * Create a new repository instance.
     */
    public function __construct(Model $model)
    {
        $this->perPage = SettingsEnum::PER_PAGE->value;
        $this->model = $model;
    }

    /**
     * Get all records.
     */
    public function all(array $relations = []): Collection
    {
        if (count($relations) > 0) {

            return $this->model->with($relations)->get();
        }

        return $this->model->all();
    }

    /**
     * Get paginated records.
     */
    public function paginate(array $relations = []): LengthAwarePaginator
    {
        if (count($relations) > 0) {

            return $this->model->with($relations)->paginate($this->perPage);
        }

        return $this->model->paginate($this->perPage);
    }

    /**
     * Create a new record.
     */
    public function create(array $request): Model
    {
        return $this->model->create($request);
    }

    /**
     * Update an existing record.
     */
    public function update(int $id, array $attributes): Model|ModelNotFoundException|string
    {
        $record = $this->model->findOrFail($id);

        $record->update($attributes);

        return $record;
    }

    /**
     * Get a record by its ID.
     */
    public function show(int $id, array $relations = []): Model|ModelNotFoundException
    {
        if (count($relations) > 0) {

            return $this->model->with($relations)->findOrFail($id);
        }

        return $this->model->findOrFail($id);
    }

    /**
     * Delete a record.
     */
    public function delete(Model $model): void
    {
        $model->delete();
    }

    public function deleteWithRelations(Model $model, array $relations = [], array $relationParams = []): void
    {
        DB::beginTransaction();

        try {
            foreach ($relations as $key => $relation) {
                if (method_exists($model, $relation)) {
                    // Assuming $relationParams is optional for each relation
                    $relatedQuery = $model->{$relation}(...($relationParams[$key] ?? []));

                    if (is_callable([$relatedQuery, 'forceDelete'])) {
                        $relatedQuery->forceDelete();
                    } else {
                        throw new \Exception("The relation '{$relation}' does not support forceDelete.");
                    }
                } else {
                    throw new \Exception("The relation '{$relation}' does not exist on the model.");
                }
            }

            $model->forceDelete();

            DB::commit(); // Commit after successful deletion
        } catch (\Exception $exception) {
            DB::rollback(); // Rollback if something goes wrong

            // Consider logging the exception for debugging purposes
            throw $exception; // Rethrow the exception
        }
    }
}
