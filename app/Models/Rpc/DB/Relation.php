<?php

namespace App\Models\Rpc\DB;

use App\Models\BaseModel;

class Relation extends BaseModel
{
    protected $table = 'rpc_db_module_model_relation';
    
    protected $fillable = ['relation', 'model_id', 'relate_type', 'related_model', 'foreign_key', 'local_key'];
    
    public function model()
    {
        return $this->belongsTo('App\Models\Rpc\DB\Model', 'model_id', 'id');
    }
    
}
