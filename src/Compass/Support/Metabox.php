<?php

namespace Knapsack\Compass\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Knapsack\Compass\Contracts\MetaboxContract;
use Knapsack\Compass\Models\Post;
use Knapsack\Compass\Support\Facades\Request;

abstract class Metabox extends DependencyResolver implements MetaboxContract
{
    /** @var string */
    protected $title;

    protected $metaKeys = [];

    protected $container;

    public function __construct(string $title, string $type = 'post', string $position = 'normal', string $priority = 'default')
    {
        $this->title = $title;

        $this->container = vgb_app();

        add_action('add_meta_boxes', function () use ($type, $position, $priority) {
            add_meta_box(
                $this->getIdentifier(),
                $this->title,
                $this->forwardsTo('handleRender'),
                $type,
                $position,
                $priority,
            );
        });

        add_action('save_post', $this->forwardsTo('handleSave'));
    }

    protected function handleRender($post)
    {
        wp_nonce_field($this->getNonceAction(), $this->getNonce());

        $this->forwardsTo('render')($post);
    }

    protected function handleSave($postID)
    {
        // Backwards compatibility for WordPress < 4.7
        if (! defined('DOING_AUTOSAVE')) {
            define('DOING_AUTOSAVE', true);
        }

        // Check user permissions
        if (! Post::find($postID)->canBeUpdated()) {
            return;
        }

        // Check if our nonce is set.
        if (! Request::has($this->getNonce())) {
            return;
        }

        // Verify that the nonce is valid.
        if (! wp_verify_nonce(Request::get($this->getNonce()), $this->getNonceAction())) {
            return;
        }

        $this->forwardsTo('save')($postID);
    }

    protected function getPrefixedMetaKeys()
    {
        return Collection::make($this->metaKeys)
            ->map(function ($key) {
                return vgb_app()->prefix($key);
            });
    }

    public function getMetaValues($post)
    {
        return $this->getPrefixedMetaKeys()
            ->mapWithKeys(function ($key) use ($post) {
                return [$key => (object) [
                    'label' => vgb_app()->unprefix($key),
                    'value' => get_post_meta($post->ID, $key, true),
                ]];
            });
    }

    protected function getIdentifier()
    {
        return vgb_app()->prefix($this->title);
    }

    protected function getNonce()
    {
        return Str::snake($this->getIdentifier()).'_nonce';
    }

    protected function getNonceAction()
    {
        return $this->getNonce().'_'.md5(get_class($this));
    }

    public function save(int $postID)
    {
        Post::find($postID)
            ->setMeta(Request::only($this->getPrefixedMetaKeys()));
    }

    public function forwardsTo($method)
    {
        return function (...$arguments) use ($method) {
            return $this->callAction($method, $arguments);
        };
    }
}
