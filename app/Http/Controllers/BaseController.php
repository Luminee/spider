<?php

namespace App\Http\Controllers;

use App\Models\Rpc as Rpc;

class BaseController extends Controller
{
    private $ModelMap;
    
    private $_model;
    
    public function __construct()
    {
        $this->ModelMap = [
            'model' => Rpc\DB\Model::class,
            'module' => Rpc\DB\Module::class,
            'relation' => Rpc\DB\Relation::class,
            
            'functions' => Rpc\Repo\Functions::class,
            'repository' => Rpc\Repo\Repository::class,
            
            'api' => Rpc\Service\Api::class,
            'call' => Rpc\Service\ApiCall::class,
            'service' => Rpc\Service\Service::class,
        ];
    }
    
    private function _bind($model)
    {
        $Model = $this->ModelMap[$model];
        app()->singleton($Model, function () use ($Model) {
            return new $Model;
        });
        return app($Model);
    }
    
    protected function setModel($model_name)
    {
        $this->_model = $this->_bind($model_name);
        return $this;
    }
    
    protected function getModel()
    {
        return $this->_model;
    }
    
    protected function with($relation)
    {
        $this->_model->with($relation);
        return $this;
    }
    
    protected function selectRaw($query, Array $param = [])
    {
        $this->_model->selectRaw($query, $param);
        return $this;
    }
    
    protected function innerJoin($table, $one, $operator, $two)
    {
        $this->_model->join($table, $one, $operator, $two);
        return $this;
    }
    
    protected function whereField($field, $value, $equal = null)
    {
        $this->_model->where($field, $equal, $value);
        return $this;
    }
    
    protected function whereBetween($field, array $valueArray)
    {
        $this->_model->whereBetween($field, $valueArray);
        return $this;
    }
    
    protected function whereIn($field, array $valueArray)
    {
        $this->_model->whereIn($field, $valueArray);
        return $this;
    }
    
    protected function whereRaw($query, Array $param = [])
    {
        $this->_model->whereRaw($query, $param);
        return $this;
    }
    
    protected function whereNull($field)
    {
        $this->_model->whereNull($field);
        return $this;
    }
    
    protected function whereNotNull($field)
    {
        $this->_model->whereNotNull($field);
        return $this;
    }
    
    protected function whereHas($relation, $field, $value, $operator = '=')
    {
        $this->_model
            ->whereHas($relation, function ($query) use ($field, $value, $operator) {
                $query->where($field, $operator, $value);
            });
        return $this;
    }
    
    protected function orderBy($field, $sort = 'asc')
    {
        $this->_model->orderBy($field, $sort);
        return $this;
    }
    
    protected function groupBy($field)
    {
       $this->_model->groupBy($field);
        return $this;
    }
    
    protected function find($id)
    {
        return $this->_model->find($id);
    }
    
    protected function listField($field, $alias = null)
    {
        return $this->_model->lists($field, $alias);
    }
    
    protected function getFirst()
    {
        return $this->_model->first();
    }
    
    protected function getCollection()
    {
        return $this->_model->get();
    }
    
    protected function getPagination($perPage, $nowPage = 1, $columns = ['*'], $pageName = 'page')
    {
        return $this->_model->paginate($perPage, $columns, $pageName, $nowPage);
    }
    
    protected function create(array $data)
    {
        return $this->_model->create($data);
    }
    
    protected function insert($data)
    {
        return $this->_model->insert($data);
    }
    
    protected function firstOrCreate($data)
    {
        return $this->_model->firstOrCreate($data);
    }
    
    protected function batchUpdate($data)
    {
        return $this->_model->update($data);
    }
    
    protected function delete()
    {
        return $this->_model->delete();
    }
    
}