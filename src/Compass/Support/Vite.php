<?php

namespace Knapsack\Compass\Support;

use Exception;
use Knapsack\Compass\Exceptions\ViteException;

class Vite
{
    protected $buildDirectory;

    protected $manifestFilename = 'manifest.json';

    /**
     * The cached manifest files.
     *
     * @var array
     */
    protected static $manifests = [];

    public function __construct($buildDirectory = 'assets')
    {
        $this->buildDirectory = $buildDirectory;

        add_filter('script_loader_tag', $this->configureModuleScriptLoading(), 10, 3);
    }

    public static function make()
    {
        return new self();
    }

    public function asset($asset)
    {
        if ($this->isRunningHot()) {
            $file = $this->hotAsset($asset);
        } else {
            $manifest = $this->manifest();

            $this->preloadImports($manifest, $asset);
            $this->loadAssetCss($manifest, $asset);

            $chunk = $this->chunk($manifest, $asset);
            $file = $this->assetUrl($chunk['file']);
        }

        $this->enqueue($file);
    }

    protected function preloadImports(array $manifest, $asset)
    {
        $preloads = $manifest[$asset]['imports'] ?? [];

        // TODO: Optimize for CSS / JS (see laravel implementation)
        $urls = array_map(function ($preload) use ($manifest) {
            $path = $this->assetUrl($manifest[$preload]['file']);

            return "<link rel='modulepreload' href='$path'>";
        }, $preloads);

        if (! empty($urls)) {
            add_action('wp_head', function () use ($urls) {
                echo implode(PHP_EOL, $urls);
            });
        }
    }

    protected function loadAssetCss(array $manifest, $asset)
    {
        $assetCssFiles = $manifest[$asset]['css'] ?? [];

        foreach ($assetCssFiles as $cssFile) {
            $this->enqueue($this->assetUrl($cssFile));
        }
    }

    protected function enqueue($file)
    {
        if (str_ends_with($file, '.js') || str_ends_with($file, '.ts')) {
            $handle = "module/vite/$file";
            wp_register_script($handle, $file, [], true);
            wp_enqueue_script($handle);

            return;
        }

        if ($this->isCssPath($file)) {
            $handle = "style/vite/$file";
            wp_register_style($handle, $file);
            wp_enqueue_style($handle);

            return;
        }

        throw ViteException::invalidAssetType($file);
    }

    protected function hotAsset($asset)
    {
        return rtrim(file_get_contents($this->hotFile())).'/'.$asset;
    }

    protected function chunk($manifest, $file)
    {
        if (! isset($manifest[$file])) {
            throw new Exception("Unable to locate file in Vite manifest: {$file}.");
        }

        return $manifest[$file];
    }

    protected function manifestPath()
    {
        return vgb_public_path($this->buildDirectory).DIRECTORY_SEPARATOR.$this->manifestFilename;
    }

    protected function manifest()
    {
        $path = $this->manifestPath();

        if (! isset(static::$manifests[$path])) {
            if (! is_file($path)) {
                throw ViteException::invalidManifest($path);
            }

            static::$manifests[$path] = json_decode(file_get_contents($path), true);
        }

        return static::$manifests[$path];
    }

    /**
     * Determine whether the given path is a CSS file.
     *
     * @param  string  $path
     * @return bool
     */
    protected function isCssPath($path)
    {
        return preg_match('/\.(css|less|sass|scss|styl|stylus|pcss|postcss)$/', $path) === 1;
    }

    public function isRunningHot()
    {
        return is_file($this->hotFile());
    }

    public function hotFile()
    {
        return vgb_public_path('hot');
    }

    public function assetUrl(string $asset)
    {
        return vgb_asset($this->buildDirectory.'/'.$asset);
    }

    /**
     * Configure the script loader tag to load Vite modules as type="module".
     */
    private function configureModuleScriptLoading()
    {
        return function ($tag, $handle, $src) {
            if (! str_contains($handle, 'module/vite/')) {
                return $tag;
            }

            $attributes = [
                'type' => 'module',
                'src' => esc_url($src),
            ];

            if (! $this->isRunningHot()) {
                $attributes['crossorigin'] = '';
            }

            return '<script '.vgb_html_attributes($attributes).'></script>';
        };
    }
}
