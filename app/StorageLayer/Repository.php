<?php

namespace App\StorageLayer;

use Illuminate\Container\Container as App;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class Repository {

    /**
     * @var App
     */
    private $app;

    /**
     * Query builder for this model
     * @var
     */
    protected $modelInstance;

    /**
     * @param App $app
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    public function __construct(App $app) {
        $this->app = $app;
        $this->makeModelInstance();
    }

    /**
     * @return Model
     * @throws BindingResolutionException
     * @throws RepositoryException
     */
    private function makeModelInstance(): Model {
        $tryToCreateModel = $this->app->make($this->getModelClassName());

        if (!$tryToCreateModel instanceof Model)
            throw new RepositoryException("Class {$this->getModelClassName()} must be an instance of Illuminate\\Database\\Eloquent\\Model");

        return $this->modelInstance = $tryToCreateModel;
    }

    /**
     * Specify Model class name
     *
     * @return string
     */
    abstract function getModelClassName(): string;

    protected function onlyFillable(array $items): array {
        if (sizeof($this->modelInstance->getFillable()) === 0)
            return $items;

        $qualified = array();
        foreach ($items as $key => $val) {
            if (in_array($key, $this->modelInstance->getFillable()))
                $qualified[$key] = $val;
        }
        return $qualified;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function create(array $data) {
        return $this->modelInstance->create($data);
    }

    /**
     * @param array $data
     * @param $id
     * @param string $attribute
     * @return mixed
     */
    public function update(array $data, $id, string $attribute = "id") {
        $this->modelInstance->where($attribute, '=', $id)->update($this->onlyFillable($data));
        return $this->find($id);
    }

    /**
     * @param $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, array $columns = array('*')) {
        return $this->modelInstance->findOrFail($id, $columns);
    }

    public function updateOrCreate($criteria, $data) {
        return $this->modelInstance->updateOrCreate(
            $criteria,
            $data
        );
    }

    public function findBy($attribute, $value, $columns = array('*'), $caseInsensitive = false, $withRelationships = []) {
        if ($caseInsensitive)
            $query = $this->modelInstance->whereRaw("LOWER(`" . $attribute . "`) LIKE '" .
                strtolower($value) . "'");
        else
            $query = $this->modelInstance->where($attribute, '=', $value);

        if (count($withRelationships) > 0)
            $query = $query->with($withRelationships);

        $model = $query->first();

        if (!$model)
            throw new ModelNotFoundException("Model with criteria: '" . $attribute . "' equal to '" . $value . "' was not found.");
        return $model;
    }

}
