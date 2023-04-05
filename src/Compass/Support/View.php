<?php

namespace Compass\Support;

use Jenssegers\Blade\Blade;
use Compass\Contracts\ViewContract;

class View implements ViewContract
{
    protected Blade $blade;

    public function __construct()
    {
        $this->blade = new Blade(
            vgb_resource_path('views'),
            vgb_storage_path('framework/views')
        );
    }

    public function render(string $view, array $data = [])
    {
        return $this->blade->make($view, $data)->render();
    }

    public function exists(string $view)
    {
        return $this->blade->exists($view);
    }
}
