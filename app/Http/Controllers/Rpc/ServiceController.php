<?php

namespace App\Http\Controllers\Rpc;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;

class ServiceController extends BaseController
{
    public function getServiceList()
    {
        $service_list = $this->setModel('service')->with('api_list')->getCollection();
        return $this->success(compact('service_list'));
    }
    
    public function getServiceInfo(Request $request)
    {
        $service_id = $request->input('service_id');
        $service    = $this->setModel('service')->with('api_list')->find($service_id);
        return $this->success(compact('service'));
    }
    
    public function getApiInfo(Request $request)
    {
        $api_id = $request->input('api_id');
        $api    = $this->setModel('api')->with(['call_list', 'service'])->find($api_id);
        return $this->success(compact('api'));
    }
    
    public function getCallInfo(Request $request)
    {
        $call_id = $request->input('call_id');
        $call    = $this->setModel('call')->with(['api', 'repository', 'functions'])->find($call_id);
        return $this->success(compact('call'));
    }
    
    
}