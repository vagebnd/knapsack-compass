<?php

namespace Knapsack\Compass\Routing\Routes;

use Knapsack\Compass\Routing\Route;

class TemplateRoute extends Route
{
    public string $template;

    /**
     * @var string|callable|null
     */
    protected $action;

    /**
     * @param  array|callable|null  $action
     */
    public function __construct($template, $action = null)
    {
        parent::__construct("template-$template", $action, 'GET');

        $this->template = $template;
        $this->action = $action;
    }

    public function getTemplate()
    {
        return $this->template;
    }
}
