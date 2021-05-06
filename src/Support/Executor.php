<?php

namespace PragmaRX\ArtisanTool\Support;

use Closure;
use Carbon\Carbon;
use Symfony\Component\Process\Process;

class Executor
{
    public $time;

    public $startedAt;

    public $endedAt;

    /**
     * Execute one command.
     *
     * @param $command
     * @param null         $runDir
     * @param Closure|null $callback
     * @param null         $timeout
     *
     * @return Process
     */
    public function exec(
        $command,
        $runDir = null,
        $ttyFile,
        Closure $callback = null,
        $timeout = null
    ) {
        $process = Process::fromShellCommandline($command, $runDir);

        $process->setTimeout($timeout);

        $this->startedAt = Carbon::now();

        $process->run($callback);

        $this->endedAt = Carbon::now();

        $output = $process->getOutput();

        if (strlen($output)) {
            \File::append($ttyFile, $output);
        }

        return $process;
    }

    /**
     * Get the elapsed time formatted for humans.
     *
     * @return mixed
     */
    public function elapsedForHumans()
    {
        return $this->endedAt->diffForHumans($this->startedAt);
    }
}
