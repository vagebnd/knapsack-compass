<?php

namespace Compass\Routing;

use InvalidArgumentException;
use Compass\Contracts\Serializable;

class Route implements Serializable
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
        $this->template = $template;
        $this->action = $action;
    }

    public function run()
    {
        if ($this->action instanceof \Closure) {
            return call_user_func($this->action);
        }

        [$controller, $method] = $this->getValidatedAction();

        return vgb_app($controller)->callAction($method, []);
    }

    public function adminRun()
    {
        if ($this->action instanceof \Closure) {
            return;
        }

        [$controller] = $this->getValidatedAction();

        vgb_app($controller)->adminRun();
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function serialize()
    {
        return sha1(json_encode($this));
    }

    private function getValidatedAction()
    {
        if (! is_array($this->action) || count($this->action) !== 2) {
            throw new InvalidArgumentException('Invalid action, Actions must be an array with a controller class string and method.');
        }

        /** @var array $action */
        $action = $this->action;

        return $action;
    }
}
