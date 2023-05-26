<?php

namespace Knapsack\Compass\Support\Orm\Relations;

class HasMany
{
    private $model;
    private $childClass;
    private $orderBy;
    private $order;

    public function __construct($model, $childClass)
    {
        $this->model = $model;
        $this->childClass = $childClass;
    }

    public function orderBy($orderBy, $order = 'ASC')
    {
        $this->orderBy = $orderBy;
        $this->order = $order;
        return $this;
    }

    public function get()
    {
        $query = [
            'post_type' => $this->childClass::getName(),
            'post_parent' => $this->model->ID,
            'posts_per_page' => -1,
        ];

        if ($this->orderBy) {
            $query['orderby'] = $this->orderBy;
            $query['order'] = $this->order;
        }

        $models = array_map(function ($post) {
            return new $this->childClass($post);
        }, get_children($query));

        return collect($models);
    }
}
