<?php

namespace Corals\Foundation\Services;

use Corals\Foundation\Classes\CSVImporters\BaseImporter;
use Corals\Foundation\Transformers\FractalPresenter;
use Illuminate\Http\Request;

class BaseServiceClass
{
    protected $model;
    protected $modelClass;
    protected $presenter = null;
    protected $excludedRequestParams = [];

    /**
     * @param $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @param FractalPresenter|null $presenter
     */
    public function setPresenter(FractalPresenter $presenter = null)
    {
        $this->presenter = $presenter;
    }

    public function getRequestData($request)
    {
        if (is_array($request)) {
            return \Arr::except($request, $this->excludedRequestParams);
        } else {
            return $request->except($this->excludedRequestParams);
        }
    }

    public function index($query, $dataTable)
    {
        foreach ($dataTable->getScopes() as $scope) {
            $scope->apply($query);
        }

        $result = $query->paginate();

        if (!is_null($this->presenter)) {
            return $this->presenter->present($result);
        } else {
            return $result;
        }
    }

    /**
     * @param $request
     * @param $modelClass
     * @param array $additionalData
     * @return mixed
     */
    public function store($request, $modelClass, $additionalData = [])
    {
        if (method_exists($this, 'preStore')) {
            $this->preStore($request, $additionalData);
        }

        if (method_exists($this, 'preStoreUpdate')) {
            $this->preStoreUpdate($request, $additionalData);
        }

        $data = array_merge($this->getRequestData($request), $additionalData);

        $this->model = $modelClass::query()->create($data);

        if (method_exists($this, 'postStore')) {
            $this->postStore($request, $additionalData);
        }

        if (method_exists($this, 'postStoreUpdate')) {
            $this->postStoreUpdate($request, $additionalData);
        }

        return $this->model;
    }

    /**
     * @param $request
     * @param $model
     * @param array $additionalData
     * @return mixed
     */
    public function update($request, $model, $additionalData = [])
    {
        if (method_exists($this, 'preUpdate')) {
            $this->preUpdate($request, $additionalData);
        }

        if (method_exists($this, 'preStoreUpdate')) {
            $this->preStoreUpdate($request, $additionalData);
        }

        $data = array_merge($this->getRequestData($request), $additionalData);

        $model->update($data);

        $this->model = $model;

        if (method_exists($this, 'postUpdate')) {
            $this->postUpdate($request, $additionalData);
        }

        if (method_exists($this, 'postStoreUpdate')) {
            $this->postStoreUpdate($request, $additionalData);
        }

        return $this->model;
    }

    /**
     * @param $request
     * @param $modelClass
     * @param $config
     * @return \Illuminate\Http\JsonResponse
     */

    public function import(Request $request, $modelClass, $config)
    {
        try {
            $file = $request->file('file_csv');
            $importer = (new BaseImporter($modelClass, $config))->setCsvFile($file);
            $importer->run();
            $importer->finish();
            $message = ['level' => 'success', 'message' => 'File been Created Successfully'];
        } catch (\Exception $exception) {
            log_exception($exception, $modelClass);
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
        }
        return response()->json($message);
    }

    /**
     * @param $data
     * @param $modelClass
     */


    public function destroy($request, $model)
    {
        $model->delete();
    }

    /**
     * @param null $model
     * @return mixed
     */
    public function getModelDetails($model = null)
    {
        if (!is_null($model)) {
            $this->setModel($model);
        }

        if (!is_null($this->presenter)) {
            $this->model->setPresenter($this->presenter);
        }

        if (method_exists($this->model, 'presenter')) {
            return $this->model->presenter();
        }

        return $model;
    }
}
