<?php

namespace Compass;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Compass\Support\Facades\Config;

class App extends Container
{
    /**
     * Indicates if the application has been bootstrapped before.
     */
    protected bool $hasBeenBootstrapped = false;

    public function __construct()
    {
        $this->registerBaseBindings();
    }

    protected function registerBaseBindings()
    {
        static::setInstance($this);

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
        return Str::start($value, $this->getName().'_');
    }

    public function unprefix(string $value)
    {
        return Str::after($value, $this->getName().'_');
    }
}
