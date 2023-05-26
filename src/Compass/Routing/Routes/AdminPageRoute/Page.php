<?php

namespace Knapsack\Compass\Routing\Routes\AdminPageRoute;

use Illuminate\Support\Collection;
use Knapsack\Compass\Routing\Registrar\RequestHandler;
use Knapsack\Compass\Support\Collections\Arr;

class Page
{
    protected Collection $methods;

    protected array $attributes = [];

    /**
     * @var string|null
     */
    protected $parentSlug = null;

    public function __construct(Collection $methods, array $attributes = [])
    {
        $this->methods = $methods;
        $this->attributes = $attributes;

        if (Arr::has($attributes, 'parent_slug')) {
            $this->parentSlug = Arr::get($attributes, 'parent_slug');
        }

        if (! Arr::has($attributes, 'title')) {
            $this->attributes['title'] = trim($this->getEndpoint(), '/');
        }
    }

    public function hasMethods()
    {
        return $this->methods->isNotEmpty();
    }

    public function getTitle()
    {
        return Arr::get($this->attributes, 'title', 'Untitled');
    }

    public function getRequiredCapability()
    {
        return Arr::get($this->attributes, 'capability', 'manage_options');
    }

    public function getEndpoint()
    {
        return Arr::get($this->attributes, 'endpoint', '');
    }

    public function getIcon()
    {
        return Arr::get($this->attributes, 'icon', 'dashicons-admin-generic');
    }

    public function getMethods()
    {
        return RequestHandler::handle($this->methods);
    }

    public function hasParent()
    {
        return ! is_null($this->parentSlug);
    }

    public function getParentSlug()
    {
        return $this->parentSlug;
    }

    /**
     * Add the page to the WordPress admin menu.
     */
    public function add()
    {
        if (! $this->hasParent()) {
            return add_menu_page(
                $this->getTitle(),
                $this->getTitle(),
                $this->getRequiredCapability(),
                $this->getEndpoint(),
                $this->getMethods(),
                $this->getIcon(),
            );
        }

        return add_submenu_page(
            $this->getParentSlug(),
            $this->getTitle(),
            $this->getTitle(),
            $this->getRequiredCapability(),
            $this->getEndpoint(),
            $this->getMethods(),
        );
    }
}
