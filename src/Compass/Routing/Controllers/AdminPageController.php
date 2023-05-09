<?php

namespace Knapsack\Compass\Routing\Controllers;

use Knapsack\Compass\Routing\Controller;

abstract class AdminPageController extends Controller
{
    protected function authorize(string $capability)
    {
        if (! current_user_can($capability)) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
    }
}
