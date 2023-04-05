<?php

namespace Knapsack\Compass\Contracts;

interface MetaboxContract
{
    public function render($post);

    public function save(int $postID);
}
