<?php

namespace Knapsack\Compass\Routing;

use InvalidArgumentException;
use Knapsack\Compass\Contracts\Serializable;

class Route implements Serializable
{
    public string $endpoint;

    public string $method;

    /**
     * @var string|callable|null
     */
    protected $action;

    /**
     * @param  array|callable|null  $action
     */
    public function __construct($endpoint, $action = null, $method = 'GET')
    {
        $this->endpoint = $endpoint;
        $this->action = $action;
        $this->method = $method;
    }

    public function run($args = [])
    {
        if ($this->action instanceof \Closure) {
            return call_user_func($this->action);
        }

        [$controller, $method] = $this->getValidatedAction();

        return vgb_app($controller)->callAction($method, $args);
    }

    public function adminRun()
    {
        if ($this->action instanceof \Closure) {
            return;
        }

        [$controller] = $this->getValidatedAction();

        vgb_app($controller)->adminRun();
    }

    public function serialize()
    {
        return sha1(json_encode($this));
    }

    // Rewrite the endpoint in the format the WP rest API expects
    public function getApiEndpoint()
    {
        return preg_replace_callback('/{([^}]+)}/', function ($matches) {
            $variableName = $matches[1];
            return '(?P<' . $variableName . '>\d+)';
        }, $this->endpoint);
    }

    protected function getValidatedAction()
    {
        if (! is_array($this->action) || count($this->action) !== 2) {
            throw new InvalidArgumentException('Invalid action, Actions must be an array with a controller class string and method.');
        }

        /** @var array $action */
        $action = $this->action;

        return $action;
    }
}
