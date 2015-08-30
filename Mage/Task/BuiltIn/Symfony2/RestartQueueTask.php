<?php
/**
 * Author: jack<linjue@wilead.com>
 * Date: 15/8/31
 */

namespace Mage\Task\BuiltIn\Symfony2;

use Exception;
use Mage\Task\ErrorWithMessageException;
use Mage\Task\SkipException;

/**
 * Class RestartQueueTask
 * @package Mage\Task\BuiltIn\Symfony2
 */
class RestartQueueTask extends SymfonyAbstractTask
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Symfony v2 - Restart resque schedule process and workers [built-in]';
    }

    /**
     * @return bool
     */
    public function run()
    {
        $workers = $this->getParameter('workers_option', array());

        $command = sprintf('%s resque:scheduledworker-stop --quiet', $this->getAppPath());
        $this->runCommand($command);
        $command = sprintf('%s resque:worker-stop -a --quiet', $this->getAppPath());
        $this->runCommand($command);
        $command = sprintf('%s resque:scheduledworker-start -i 1', $this->getAppPath());
        $this->runCommand($command);

        foreach ($workers as $worker) {
            $command = sprintf('%s resque:worker-start %s', $this->getAppPath(), $worker);
            $this->runCommand($command);
        }

        return true;
    }
}