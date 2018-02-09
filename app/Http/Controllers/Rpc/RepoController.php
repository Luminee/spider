<?php

namespace App\Http\Controllers\Rpc;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class RepoController extends BaseController
{
    public function getRepositoryList()
    {
        $repository_list = $this->setModel('repository')->with('functions_list')->getCollection();
        return $this->success(compact('repository_list'));
    }
    
    public function getRepositoryInfo(Request $request)
    {
        $repository_id = $request->input('repository_id');
        $repository    = $this->setModel('repository')->with(['functions_list'])->find($repository_id);
        return $this->success(compact('repository'));
    }
    
    public function getFunctionInfo(Request $request)
    {
        $function_id = $request->input('function_id');
        $function    = $this->setModel('functions')->with(['apiCall_list', 'repository', 'setModel'])->find($function_id);
        return $this->success(compact('function'));
    }
    
    
}