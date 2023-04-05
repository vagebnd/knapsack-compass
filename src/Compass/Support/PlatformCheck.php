<?php

namespace Compass\Support;

use Compass\Exceptions\VersionException;

class PlatformCheck
{
    private string $minimumPhpVersion;

    private string $minimumWordPressVersion;

    public function __construct(string $minimumPhpVersion = '7.1', string $minimumWordPressVersion = '4.7.0')
    {
        $this->minimumPhpVersion = $minimumPhpVersion;
        $this->minimumWordPressVersion = $minimumWordPressVersion;
    }

    public function check()
    {
        $this->checkPhpVersion();
        $this->checkWordPressVersion();
    }

    public function checkPhpVersion()
    {
        if (version_compare($this->minimumPhpVersion, phpversion(), '>=')) {
            throw VersionException::invalidPhpVersion($this->minimumPhpVersion);
        }
    }

    public function checkWordPressVersion()
    {
        if (version_compare($this->minimumWordPressVersion, get_bloginfo('version'), '>=')) {
            throw VersionException::invalidWordPressVersion($this->minimumWordPressVersion);
        }
    }
}
