<?php

namespace Knapsack\Compass\Core\Boostrap;

use Exception;
use SplFileInfo;
use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\Bootstrapable;
use Knapsack\Compass\Support\Collections\Arr;
use Knapsack\Compass\Contracts\FilesystemContract;
use Knapsack\Compass\Contracts\Config\Repository as RepositoryContract;
use Knapsack\Compass\Support\Repository;

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

        $configPath = realpath($app->configPath());

        $files = Arr::where($app->make(FilesystemContract::class)->allFiles($configPath), function ($file) {
            return $file->getExtension() === 'php';
        });

        foreach ($files as $file) {
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
