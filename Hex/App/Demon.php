<?php

namespace Hex\App;

use Database;

/*
 * Use $process->check() to check resources limit
 */
class Demon
{
    protected $name;

    protected $memoryLimit = 100 * 1024 * 1024; // bytes // 100 MB
    protected $timeLimit = 60; // sec

    protected $memoryUsage;
    protected $time;
    // protected $cachePath;
    // protected $processes = array();
    protected $statistics;

    const STATUS_WORKING = 1;
    const STATUS_STOPED = 0;

    protected $status = self::STATUS_STOPED;

    public function __construct($name, $config = array())
    {
        $this->name = $name;

        if (isset($config['memoryLimit'])) {
            $this->memoryLimit = $config['memoryLimit'];
        }

        if (isset($config['timeLimit'])) {
            $this->timeLimit = $config['timeLimit'];
        }

        // if (isset($config['cachePath'])) {
        //     $this->cachePath = $config['cachePath'];
        // } else {
        //     $this->cachePath = \Model::$conf->cachePath;
        // }

        $this->updateProcessesStatus();
    }

    // public function getCacheFilePath()
    // {
    //     return $this->cachePath . '/demonProcesses.php';
    // }

    public function getName()
    {
        return $this->name;
    }

    public function getMemoryLimit()
    {
        return $this->memoryLimit;
    }

    public function getTimeLimit()
    {
        return $this->timeLimit;
    }

    private function updateProcessesStatus()
    {
        // $file = $this->getCacheFilePath();

        // if (! file_exists($file)) {
        //     file_put_contents($file, '');
        // }

        // $processes = include($file);

        $process = Database::value("SELECT * FROM `Processes` WHERE `name` = '" . $this->getName() . "'", true);

        if (is_array($process)) {
            $this->setStatus($process['status']);
        }
    }

    private function recordProcessStatus()
    {
        // $file = $this->getCacheFilePath();

        // $content = "<?php\n" . var_export($this->processes, true) . ';';

        // file_put_contents($file, $content);

        $statistics = $this->getStatistics();

        if (Database::value("SELECT COUNT(*) FROM `Processes` WHERE name = '" . $this->getName() . "'")) {
            Database::query("UPDATE `Processes` SET `status` = '" . $this->getStatus() . "', `memory` = '" . (int) $statistics['memory'] . "', `time` = '" . (int) $statistics['time'] . "' WHERE name = '" . $this->getName() . "'");
        } else {
            Database::query("INSERT INTO `Processes` SET `name` = '" . $this->getName() . "', `status` = '" . $this->getStatus() . "', `memory` = '" . (int) $statistics['memory'] . "', `time` = '" . (int) $statistics['time'] . "'");
        }
    }

    protected function setStatus($status)
    {
        $this->status = (int) $status;

        // $this->processes[$this->getName()] = $this->status;

        $this->recordProcessStatus();
    }

    public function getStatus()
    {
        return $this->status;
    }

    protected function setWorking()
    {
        $this->setStatus(self::STATUS_WORKING);
    }

    protected function setStoped()
    {
        $this->setStatus(self::STATUS_STOPED);
    }

    public function getMemoryUsage()
    {
        return $this->memoryUsage;
    }

    public function getTime()
    {
        return $this->time;
    }

    public function recordMemoryUsage()
    {
        $this->memoryUsage = memory_get_usage();
    }

    public function recordTime()
    {
        $this->time = microtime(true);
    }

    public function getMemoryUsed()
    {
        return memory_get_usage() - $this->getMemoryUsage();
    }

    public function getTimeElapsed()
    {
        return microtime(true) - $this->getTime();
    }

    public function isEnoughResources()
    {
        if ($this->getMemoryUsed() > $this->getMemoryLimit()) {
            return false;
        }

        if ($this->getTimeElapsed() > $this->getTimeLimit()) {
            return false;
        }

        return true;
    }

    public function updateStatistics()
    {
        $info = array();

        $info['memory'] = $this->getMemoryUsed();
        $info['memory_MB'] = $info['memory'] / 1024 / 1024;

        $info['time'] = $this->getTimeElapsed();

        $this->statistics = $info;
    }

    public function getStatistics()
    {
        return $this->statistics;
    }

    public function isStoped()
    {
        return $this->getStatus() == self::STATUS_STOPED;
    }

    public function isWorking()
    {
        return $this->getStatus() == self::STATUS_WORKING;
    }

    public function check()
    {
        $this->updateProcessesStatus();

        $this->updateStatistics();
        
        if ($this->isStoped() or ! $this->isEnoughResources()) {
            $this->abort();
        }
    }

    public function abort()
    {
        $this->stop();

        exit();
    }

    public function run($function)
    {
        if ($this->isWorking()) {
            throw new \Exception("Already running");
        }

        $this->setWorking();

        $this->recordMemoryUsage();
        $this->recordTime();

        register_shutdown_function(function () {
            $this->stop();
        });

        // Execute process
        if (! is_callable($function)) {
            throw new \Exception("Can't call function", 1);
        }

        $function();
    }

    public function stop()
    {
        $this->updateStatistics();

        $this->setStoped();
    }
}
