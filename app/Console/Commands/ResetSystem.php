<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ResetSystem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'face-recog:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset Opencv dataset and database';

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
     * @return int
     */
    public function handle()
    {
        $this->info('Removing trainer file.');
        $osCmdDeleteFile = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ? 'del' : 'rm';
        $trainerFile = app_path('Console' . DIRECTORY_SEPARATOR . 'Scripts'
            . DIRECTORY_SEPARATOR . 'trainer.yml');
        if (!file_exists($trainerFile)) {
            $this->info('There`s no trainer file.');
            return 0;
        }
        $process = new Process([$osCmdDeleteFile, $trainerFile]);
        $process->run();
        if (!$process->isSuccessful()) {
            $this->error('Something went wrong!');
            throw new ProcessFailedException($process);
        }
        $this->info('Trainer file removed.');
        $this->info('Removing Dataset');
        $facesFolder = storage_path('app' . DIRECTORY_SEPARATOR . 'faces');
        $osCmdDeleteFolder = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN' ?
            ['Rmdir', '/S', $facesFolder] : ['rm', '-rv', $facesFolder];
        $process = new Process($osCmdDeleteFolder);
        $process->run();
        if (!$process->isSuccessful()) {
            $this->error('Something went wrong!');
            throw new ProcessFailedException($process);
        }
        $this->info('Dataset removed.');
        $this->info('Refreshing Database');
        $this->call('migrate:refresh');
        $this->info('Done!');
        return 0;
    }
}
