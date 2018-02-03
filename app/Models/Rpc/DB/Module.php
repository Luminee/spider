<?php

namespace App\Models\Rpc\DB;

use App\Models\BaseModel;

class Module extends BaseModel
{
    protected $table = 'rpc_db_module';
    
    protected $fillable = ['code', 'label'];
    
}
