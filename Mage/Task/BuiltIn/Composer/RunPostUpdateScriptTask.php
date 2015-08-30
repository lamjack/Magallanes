<?php
/**
 * Author: jack<linjue@wilead.com>
 * Date: 15/8/31
 */

namespace Mage\Task\BuiltIn\Composer;

use Exception;
use Mage\Task\ErrorWithMessageException;
use Mage\Task\SkipException;

/**
 * Class RunPostUpdateScriptTask
 * @package Mage\Task\BuiltIn\Composer
 */
class RunPostUpdateScriptTask extends ComposerAbstractTask
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'Run post update script via Composer [built-in]';
    }

    /**
     * @return bool
     */
    public function run()
    {
        return $this->runCommand($this->getComposerCmd() . ' run-script post-update-cmd');
    }
}