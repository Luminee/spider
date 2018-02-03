<?php

namespace App\Console\Commands\Rpc;

use App\Models\Rpc\DB\Model;
use App\Models\Rpc\DB\Module;
use App\Models\Rpc\DB\Relation;
use Illuminate\Console\Command;

class CaptureRpcDB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capture:rpc:db';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capture The Rpc DB Database';
    
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $Models_Dir = 'D:\Vanthink\rpc_server\app\Models';
        $Modules    = scandir($Models_Dir);
        foreach ($Modules as $module) {
            if ($module == '.' or $module == '..' or !is_dir($Models_Dir.'\\'.$module)) {
                continue;
            }
            $this->info($module);
            $Module  = Module::firstOrCreate(['code' => $module]);
            $Models  = scandir($Models_Dir.'\\'.$module);
            $_models = require_once $Models_Dir.'\\'.$module.'\\_models.php';
            foreach ($Models as $model) {
                if ($model == '.' or $model == '..' or $model == '_models.php' or $model == '_RelatedLabel.php') {
                    continue;
                }
                $model_file = file_get_contents($Models_Dir.'\\'.$module.'\\'.$model, FILE_USE_INCLUDE_PATH);
                $preg       = [
                    'namespace' => '(namespace [a-zA-Z\\\\ ]+;)',
                    'class' => '([\s]class [a-zA-Z]+ )',
                    'table' => '(protected \$table = \'[a-zA-Z_]+\';)',
                    'fillable' => '(protected \$fillable = \[[0-9a-zA-Z_\'\, ]+\];)',
                ];
                $replace    = [
                    'namespace' => ['namespace', ';', PHP_EOL],
                    'class' => [' ', PHP_EOL],
                    'table' => ['protected $table =', ';', '\'', '"', PHP_EOL],
                    'fillable' => ['protected $fillable =', ';', '\'', '"', '[', ']', ' ', PHP_EOL]
                ];
                $keys       = array_keys($preg);
                preg_match('/'.implode('[\s\S]*', $preg).'/i', $model_file, $matches);
                $structure = [];
                foreach ($matches as $key => $match) {
                    if ($key == 0) continue;
                    $structure[$keys[$key - 1]] = trim(str_replace($replace[$keys[$key - 1]], '', $match));
                }
                $model_file = str_replace($matches[0], '', $model_file);
                preg_match('/public \$timestamps = (false|true);/i', $model_file, $matches);
                $timestamps = empty($matches) ? 1 : ($matches[1] == 'false' ? 0 : 1);
                preg_match('/use SoftDeletes;/i', $model_file, $matches);
                $use_soft_deletes = empty($matches) ? 0 : 1;
                $Class            = $structure['namespace'].'\\'.substr($structure['class'], 5);
                $model_create     = [
                    'module_id' => $Module->id,
                    'code' => str_replace('.php', '', $model),
                    'alias' => array_search($Class, $_models),
                    'class_name' => $Class,
                    'table' => $structure['table'],
                    'fillable' => $structure['fillable'],
                    'timestamps' => $timestamps,
                    'use_soft_deletes' => $use_soft_deletes
                ];
                $Model            = Model::firstOrCreate($model_create);
                $this->getRelation($model_file, $Model->id);
            }
        }
    }
    
    /**
     * @author LuminEe
     * @param string $content
     * @return void
     */
    protected function getRelation($content, $model_id)
    {
        $preg_relation = '/public function ([0-9a-zA-Z_]+)\(\)[\s]*\{[\s]*return';
        $preg_relation .= ' \$this->(belongsTo|hasMany|hasOne)';
        $preg_relation .= '\(([a-zA-Z\'"\\\\, _]+)\);[\s]*\}/i';
        preg_match($preg_relation, $content, $matches);
        if (!empty($matches)) {
            $string = array_shift($matches);
            list($relation, $type, $related_model) = $matches;
            $related = explode(',', str_replace(['\'', '"', ' '], '', $related_model));
            $model   = array_shift($related);
            if ($type == 'belongsTo') {
                $local_key   = array_shift($related);
                $foreign_key = empty($related) ? null : array_shift($related);
            } else {
                $foreign_key = array_shift($related);
                $local_key   = empty($related) ? null : array_shift($related);
            }
            $create = [
                'relation' => $relation,
                'model_id' => $model_id,
                'relate_type' => $type,
                'related_model' => $model,
                'foreign_key' => $foreign_key,
                'local_key' => $local_key
            ];
            Relation::firstOrCreate($create);
            $content = str_replace($string, '', $content);
            $this->getRelation($content, $model_id);
        }
    }
}
