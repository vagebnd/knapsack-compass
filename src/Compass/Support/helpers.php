<?php

use Knapsack\Compass\App;
use Knapsack\Compass\Models\Post;
use Knapsack\Compass\Contracts\ViewContract;
use Knapsack\Compass\Contracts\Config\Repository;
use Knapsack\Compass\Support\HigherOrderTapProxy;

if (! function_exists('vgb_path')) {
    function vgb_path($path = '')
    {
        return get_template_directory().'/'.$path;
    }
}

if (! function_exists('vgb_resource_path')) {
    function vgb_resource_path($path = '')
    {
        return vgb_path('resources/'.$path);
    }
}

if (! function_exists('vgb_storage_path')) {
    function vgb_storage_path($path = '')
    {
        return vgb_path('storage/'.$path);
    }
}

if (! function_exists('vgb_public_path')) {
    function vgb_public_path($path = '')
    {
        return vgb_path('public/'.trim($path, '/'));
    }
}

if (! function_exists('vgb_config_path')) {
    function vgb_config_path($path = '')
    {
        return vgb_path('config/'.$path);
    }
}

if (! function_exists('vgb_asset')) {
    function vgb_asset($path = '')
    {
        return get_template_directory_uri().'/public/'.trim($path, '/');
    }
}

if (! function_exists('vgb_html_attributes')) {
    function vgb_html_attributes($attributes = [])
    {
        if (! $attributes) {
            return '';
        }

        $compiled = implode('="%s" ', array_keys($attributes)).'="%s"';

        return vsprintf($compiled, array_map('htmlspecialchars', array_values($attributes)));
    }
}

if (! function_exists('vgb_app')) {
    function vgb_app($abstract = null, array $parameters = [])
    {
        if (is_null($abstract)) {
            return App::getInstance();
        }

        return App::getInstance()->make($abstract, $parameters);
    }
}

if (! function_exists('vgb_view')) {
    function vgb_view(string $name, $attributes = [])
    {
        echo vgb_app(ViewContract::class)->render($name, $attributes);
    }
}

if (! function_exists('vgb_viewinstance')) {
    function vgb_viewinstance()
    {
        return vgb_app(ViewContract::class);
    }
}

if (! function_exists('vgb_config')) {
    /**
     * Get / set the specified configuration value.
     *
     * If an array is passed as the key, we will assume you want to set an array of values.
     *
     * @param  array|string|null  $key
     * @return mixed|Repository
     */
    function vgb_config($key = null, $default = null)
    {
        if (is_null($key)) {
            return vgb_app('config');
        }

        if (is_array($key)) {
            return vgb_app('config')->set($key);
        }

        return vgb_app('config')->get($key, $default);
    }
}

if (! function_exists('vgb_run_controller')) {
    function vgb_run_controller($controller, $method, $parameters = [])
    {
        return vgb_app($controller)->callAction($method, $parameters);
    }
}

if (! function_exists('vgb_the_post')) {
    function vgb_the_post()
    {
        return new Post(get_post());
    }
}

if (! function_exists('vgb_tap')) {
    /**
     * Call the given Closure with the given value then return the value.
     *
     * @param  mixed  $value
     * @param  callable|null  $callback
     * @return mixed
     */
    function vgb_tap($value, $callback = null)
    {
        if (is_null($callback)) {
            return new HigherOrderTapProxy($value);
        }

        $callback($value);

        return $value;
    }
}
