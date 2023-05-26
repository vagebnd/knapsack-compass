<?php

namespace Knapsack\Compass\Support\Orm;

use Illuminate\Support\Arr;
use Knapsack\Compass\Exceptions\PostNotFoundException;
use Knapsack\Compass\Exceptions\QueryException;
use Knapsack\Compass\Support\CustomPostType;
use Knapsack\Compass\Support\DB;
use Knapsack\Compass\Support\Orm\Relations\HasMany;
use Knapsack\Compass\Support\Traits\ExposeFilename;
use Knapsack\Compass\Support\Traits\ForwardsCalls;

/**
 * @mixin \WP_Post
 */
class Model extends CustomPostType
{
    use ForwardsCalls;
    use ExposeFilename;

    public $post;
    public $metaData = null;

    public function __construct($post = null)
    {
        $this->post = $post;
    }

    public static function find(int $id)
    {
        return self::forge(get_post($id));
    }

    public static function findOrFail(int $id)
    {
        $model = self::find($id);

        if (empty($model->post)) {
            throw new PostNotFoundException(self::getName());
        }

        return $model;
    }

    public static function all()
    {
        return collect(self::get())
            ->map(function ($post) {
                return self::forge($post);
            });
    }

    public static function get(array $args = [])
    {
        return get_posts(array_merge([
            'post_type' => self::getName(),
        ], $args));
    }

    public static function create(array $data)
    {
        $metaData = array_diff_key($data, array_flip(self::getPostAttributes()));
        $postData = array_diff_key($data, $metaData);

        $postID = wp_insert_post(self::addDefaultAttributes($postData));

        if (empty($postID)) {
            throw new QueryException('Failed to create post');
        }

        self::addMetaData($postID, $metaData);

        return self::find($postID);
    }

    public static function update(array $data)
    {
        $metaData = array_diff_key($data, array_flip(self::getPostAttributes()));
        $postData = array_diff_key($data, $metaData);

        $postID = wp_update_post(self::addDefaultAttributes($postData));

        self::addMetaData($postID, $metaData);

        return self::find($postID);
    }

    public static function delete($id)
    {
        if (empty($id)) {
            return;
        }

        if (is_array($id)) {
            $ids = implode(',', array_map('absint', $id));
            DB::query("DELETE FROM wp_posts WHERE ID IN ({$ids})");
            DB::query("DELETE FROM wp_postmeta WHERE post_id IN ({$ids})");
            return;
        }

        if (is_int($id)) {
            DB::query(DB::prepare('DELETE FROM wp_postmeta WHERE post_id = %s', $id));
            wp_delete_post($id);
        }
    }

    public static function addMetaData($postID, $metaData = [])
    {
        $metaData = Arr::wrap($metaData);

        foreach ($metaData as $key => $value) {
            add_post_meta($postID, $key, $value, true);
        }
    }

    private static function forge($post)
    {
        $modelClass = 'Skeleton\\Models\\' . self::getName();

        return new $modelClass($post);
    }

    private static function addDefaultAttributes($args)
    {
        $args['post_type'] = self::getName();
        $args['post_status'] = 'publish';

        return $args;
    }

    public function hasMany($modelClassName)
    {
        return (new HasMany($this, $modelClassName));
    }

    public function images()
    {
        if ($this->hasMetaValue('images')) {
            return collect($this->getMetaValue('images'))
                ->map(function ($imageID) {
                    return [
                        'id' => $imageID,
                        'thumb' => Arr::get(wp_get_attachment_image_src($imageID, 'thumbnail'), 0, null),
                    ];
                })
                ->filter(function ($image) {
                    return ! is_null($image['thumb']);
                });
        }
    }

    public function tags()
    {
        $tags = get_the_terms($this->ID, 'post_tag');

        if (! is_array($tags)) {
            return [];
        }

        return collect(get_the_terms($this->ID, 'post_tag'))
            ->pluck('name')
            ->toArray();
    }

    public function __get($key)
    {
        if (property_exists($this->post, $key)) {
            return $this->post->{$key};
        }

        if ($this->hasMetaValue($key)) {
            return $this->getMetaValue($key);
        }

        return null;
    }

    public function addTags($tags)
    {
        wp_set_post_tags($this->ID, $tags, false);
    }

    public function __call($method, $parameters)
    {
        return $this->forwardCallTo($this->post, $method, $parameters);
    }

    private function hasMetaValue($key)
    {
        if (is_null($this->metaData)) {
            $this->metaData = get_post_meta($this->post->ID);
        }

        return Arr::has($this->metaData, $key);
    }

    private function getMetaValue($key)
    {
        $value = Arr::get($this->metaData, $key);

        if (is_array($value)) {
            $value = Arr::first($value);
        }

        return maybe_unserialize($value);
    }
}
