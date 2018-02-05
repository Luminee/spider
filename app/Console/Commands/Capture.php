<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Capture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'capture';
    
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Capture The Project';
    
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
        if (!$rpc_dir = env('RPC_DIR', null)) {
            $this->error('File [.env] need config: RPC_DIR');
            return;
        }
        $commands = [
            'capture:rpc:db' => 'Rpc -> DB',
            'capture:rpc:repo' => 'Rpc -> Repo',
            'capture:rpc:service' => 'Rpc -> Service',
        ];
        foreach ($commands as $command => $description) {
            $this->info('Capturing '.$description);
            $this->call($command);
            $this->info('');
        }
    }
    
}
