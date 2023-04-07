<?php

namespace Knapsack\Compass\Models;

use Illuminate\Support\Collection;
use WP_Post;

class Post
{
    protected WP_Post $post;

    protected Collection $meta;

    public static function find($ID): ?self
    {
        return new self(get_post($ID));
    }

    public static function make(WP_Post $post)
    {
        return new self($post);
    }

    public function __construct(WP_Post $post)
    {
        $this->post = $post;
        $this->meta = Collection::make(get_post_meta($post->ID));
    }

    public function setMeta(iterable $meta)
    {
        foreach ($meta as $key => $value) {
            if (isset($value)) {
                $this->setMetaKey($key, $value);
            }
        }
    }

    public function setMetaKey($key, $value)
    {
        update_post_meta($this->post->ID, $key, sanitize_text_field($value));
    }

    public function __get($key)
    {
        if (property_exists($this->post, $key)) {
            return $this->post->{$key};
        }

        // Remove post prefix, it's implied by the model.
        if (property_exists($this->post, "post_$key")) {
            return $this->post->{"post_$key"};
        }

        if ($this->meta->has(vgb_app()->prefix($key))) {
            return $this->meta->get(vgb_app()->prefix($key))[0] ?? null;
        }

        return null;
    }

    public function canBeUpdated()
    {
        if ($this->isPost()) {
            return current_user_can('edit_post', $this->post->ID);
        }

        if ($this->isPage()) {
            return current_user_can('edit_page', $this->post->ID);
        }

        return false;
    }

    public function isPost()
    {
        return $this->post->post_type === 'post';
    }

    public function isPage()
    {
        return $this->post->post_type === 'page';
    }
}
