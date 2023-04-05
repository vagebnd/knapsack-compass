<?php

namespace Compass\Core\Boostrap;

use Exception;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Compass\App;
use Compass\Contracts\Bootstrapable;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

class LoadConfiguration implements Bootstrapable
{
    public function bootstrap(App $app)
    {
        // TODO: Implement caching.
        $app->instance('config', $config = new Repository());

        $this->loadConfigurationFiles($app, $config);
    }

    private function loadConfigurationFiles(App $app, RepositoryContract $repository)
    {
        $files = $this->getConfigurationFiles($app);

        if (! isset($files['app'])) {
            throw new Exception('Unable to load the "app" configuration file.');
        }

        foreach ($files as $key => $path) {
            $repository->set($key, require $path);
        }
    }

    private function getConfigurationFiles(App $app)
    {
        $files = [];

        $configPath = realpath(vgb_config_path());

        foreach (Finder::create()->files()->name('*.php')->in($configPath) as $file) {
            $directory = $this->getNestedDirectory($file, $configPath);

            $files[$directory.basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        ksort($files, SORT_NATURAL);

        return $files;
    }

    /**
     * Get the configuration file nesting path.
     *
     * @param  string  $configPath
     * @return string
     */
    protected function getNestedDirectory(SplFileInfo $file, $configPath)
    {
        $directory = $file->getPath();

        if ($nested = trim(str_replace($configPath, '', $directory), DIRECTORY_SEPARATOR)) {
            $nested = str_replace(DIRECTORY_SEPARATOR, '.', $nested).'.';
        }

        return $nested;
    }
}
