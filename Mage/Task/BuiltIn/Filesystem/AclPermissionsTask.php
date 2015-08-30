<?php
/**
 * Author: jack<linjue@wilead.com>
 * Date: 15/8/31
 */

namespace Mage\Task\BuiltIn\Filesystem;

use Mage\Task\AbstractTask;
use Mage\Task\Releases\IsReleaseAware;
use Mage\Task\SkipException;

/**
 * Class AclPermissionsTask
 * @package Mage\Task\BuiltIn\Filesystem
 */
class AclPermissionsTask extends AbstractTask implements IsReleaseAware
{
    /**
     * @return bool
     * @throws SkipException
     */
    public function run()
    {
        $paths = $this->getParameter('paths', array());

        if (empty($linkedEntities)) {
            throw new SkipException('No folders configured for setfacl');
        }

        $remoteDirectory = rtrim($this->getConfig()->deployment('to'), '/') . '/';
        $releasesDirectoryPath = $remoteDirectory . $this->getConfig()->release('directory', 'releases');
        $currentCopy = $releasesDirectoryPath . '/' . $this->getConfig()->getReleaseId();
        foreach ($paths as $path) {
            $path = dirname($currentCopy . $path);
            $command = sprintf('setfacl -R -m u:"%s":rwX -m u:`whoami`:rwX %s', $this->getWebServerUser(), escapeshellarg($path));
            $this->runCommandRemote($command);
            $command = sprintf('setfacl -dR -m u:"%s":rwX -m u:`whoami`:rwX %s', $this->getWebServerUser(), escapeshellarg($path));
            $this->runCommandRemote($command);
        }
        return true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "Giving write permissions to web server user for given paths with ACL [built-in]";
    }

    /**
     * @return mixed
     * @throws SkipException
     */
    protected function getWebServerUser()
    {
        $this->runCommand("ps aux | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1", $webServerUser);

        if (empty($webServerUser)) {
            throw new SkipException("Can't guess web server user. Please check if it is running or force it by setting the group parameter");
        }

        return $webServerUser;
    }
}