<?php

namespace Knapsack\Compass\Routing\Routes;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Knapsack\Compass\Routing\Route;
use Knapsack\Compass\Routing\Routes\AdminPageRoute\Page;
use Knapsack\Compass\Support\Collections\Arr;
use RuntimeException;

class AdminPageRoute extends Route
{
    /**
     * @var Route[]
     */
    protected array $childPages = [];

    protected array $config = [];

    public function __construct(string $endpoint, array $config = [])
    {
        parent::__construct($endpoint, $config['action'] ?? null, 'GET');

        $this->config = $config;
    }

    public function get(string $endpoint, $action = null)
    {
        $this->childPages[] = new Route($endpoint, $action, 'GET');
    }

    public function post(string $endpoint, $action = null)
    {
        $this->childPages[] = new Route($endpoint, $action, 'POST');
    }

    // TODO: Add support for other methods ...

    public function getTitle()
    {
        return Arr::get($this->config, 'title', Str::slug($this->endpoint, ' '));
    }

    public function getRequiredCapability()
    {
        return Arr::get($this->config, 'capability', 'manage_options');
    }

    public function getIcon()
    {
        return Arr::get($this->config, 'icon', 'dashicons-admin-generic');
    }

    public function getChildPages()
    {
        return $this->childPages;
    }

    public function getChildPagesCollection()
    {
        return Collection::make($this->childPages)
            ->groupBy('endpoint');
    }

    public function getRootPage()
    {
        $rootPage = $this->getChildPagesCollection()
            ->pull('/');

        if ($rootPage->isEmpty()) {
            throw new RuntimeException('No root page found for ' . $this->endpoint);
        }

        return new Page($rootPage, [
            'title' => $this->getTitle(),
            'capability' => $this->getRequiredCapability(),
            'endpoint' => $this->endpoint,
            'icon' => $this->getIcon(),
        ]);
    }

    public function listSubPages()
    {
        return $this->getChildPagesCollection()
            ->except('/')
            ->mapWithKeys(function ($pages, $endpoint) {
                return [$endpoint => new Page($pages, [
                    'endpoint' => $endpoint,
                    'parent_slug' => $this->endpoint,
                ])];
            });
    }
}
