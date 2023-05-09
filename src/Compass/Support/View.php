<?php

namespace Knapsack\Compass\Support;

use Knapsack\Compass\App;
use Knapsack\Compass\Contracts\ViewContract;
use Knapsack\Compass\Support\Traits\ForwardsCalls;
use Knapsack\Compass\Support\View\BladeCompiler;

class View implements ViewContract
{
    use ForwardsCalls;

    protected $blade;

    public function __construct(App $app)
    {
        $this->blade = new BladeCompiler(
            $app->resourcePath('views'),
            $app->storagePath('framework/views'),
            BladeCompiler::MODE_AUTO,
        );

        $this->blade->setCompiledExtension('.php');
    }

    public function render(string $view, array $data = [])
    {
        return $this->blade->run($view, $data);
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->blade, $method, $parameters);
    }
}
