<?php

namespace App\Http\Controllers\Rpc;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class DBController extends BaseController
{
    public function getModelList()
    {
        $module_list = $this->setModel('module')->with('model_list')->getCollection();
        return $this->success(compact('module_list'));
    }
    
    public function getModelInfo(Request $request)
    {
        $model_id = $request->input('model_id');
        $model    = $this->setModel('model')->with(['relation_list', 'repo_function_list'])->find($model_id);
        return $this->success(compact('model'));
    }
}