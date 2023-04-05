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
        return new self($ID);
    }

    public function __construct($ID)
    {
        $this->post = get_post($ID);
        $this->meta = Collection::make(get_post_meta($ID));
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
        if ($this->meta->has(vgb_app()->prefix($key))) {
            return $this->meta->get(vgb_app()->prefix($key))[0] ?? null;
        }

        if (property_exists($this->post, $key)) {
            return $this->post->{$key};
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
