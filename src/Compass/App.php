<?php

namespace Knapsack\Compass;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Knapsack\Compass\Support\Facades\Config;

class App extends Container
{
    /**
     * Indicates if the application has been bootstrapped before.
     */
    protected bool $hasBeenBootstrapped = false;

    /**
     * Determine if the application should bind the instance to itself.
     * Allows the container to be reused for multiple applications.
     */
    protected bool $shouldBindInstance = true;

    /**
     * The base path for the container.
     *
     * @var string
     */
    protected $basePath;

    /**
     * The base URI for the application.
     *
     * @var string
     */
    protected $baseUri;

    public function __construct($basePath = null, $baseUri = null)
    {
        $this->setBasePath($basePath ?? get_template_directory());
        $this->setBaseUri($baseUri ?? get_template_directory_uri());

        $this->registerBaseBindings();
    }

    protected function registerBaseBindings()
    {
        if ($this->shouldBindInstance) {
            static::setInstance($this);
        }

        $this->instance('app', $this);

        $this->instance(Container::class, $this);
    }

    /**
     * Run the given array of bootstrap classes.
     *
     * @param  string[]  $bootstrappers
     * @return void
     */
    public function bootstrapWith(array $bootstrappers)
    {
        $this->hasBeenBootstrapped = true;

        foreach ($bootstrappers as $bootstrapper) {
            $this->make($bootstrapper)->bootstrap($this);
        }
    }

    /**
     * Determine if the application has been bootstrapped before.
     *
     * @return bool
     */
    public function hasBeenBootstrapped()
    {
        return $this->hasBeenBootstrapped;
    }

    public function getName()
    {
        return Config::get('app.name', 'knapsack');
    }

    public function prefix(string $value)
    {
        // TODO: This should be a safe prefix, snake case it.
        return Str::start($value, $this->getName().'_');
    }

    public function unprefix(string $value)
    {
        return Str::after($value, $this->getName().'_');
    }

    public function setBasePath(string $path)
    {
        $this->basePath = rtrim($path, '\/');
        return $this;
    }

    public function path(string $path = '')
    {
        return $this->joinPaths($this->basePath, $path);
    }

    public function resourcePath(string $path = '')
    {
        return $this->joinPaths($this->path('resources'), $path);
    }

    public function storagePath(string $path = '')
    {
        return $this->joinPaths($this->path('storage'), $path);
    }

    public function publicPath(string $path = '')
    {
        return $this->joinPaths($this->path('public'), $path);
    }

    public function configPath(string $path = '')
    {
        return $this->joinPaths($this->path('config'), $path);
    }

    public function setBaseUri(string $path = '')
    {
        $this->baseUri = rtrim($path, '\/');
        return $this;
    }

    public function uri(string $path = '')
    {
        return $this->joinPaths($this->baseUri, $path);
    }

    public function publicUri(string $path = '')
    {
        return $this->joinPaths($this->uri('public'), $path);
    }

    /**
     * Join the given paths together.
     *
     * @param  string  $basePath
     * @param  string  $path
     * @return string
     */
    public function joinPaths($basePath, $path = '')
    {
        return $basePath.($path != '' ? DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}
