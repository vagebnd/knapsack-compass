<?php

namespace Knapsack\Compass\Support;

use Knapsack\Compass\Support\Facades\Config;
use Knapsack\Compass\Support\Traits\ExposeFilename;

class CustomPostType
{
    use ExposeFilename;

    public $id = '';
    public $plural = '';
    public $labels = [];
    public $description = '';
    public $public = true;
    public $hierarchical = false;
    public $excludeFromSearch = true;
    public $publiclyQueryable = true;
    public $showUI = true;
    public $showInMenu = false;
    public $showInNavMenus = true;
    public $showInAdminBar = true;
    public $menuPosition = null;
    public $menuIcon = '';
    public $capabilityType = 'post';
    public $capabilities = [];
    public $mapMetaCap = true;
    public $supports = [];
    public $registerMetaBoxCallback = null;
    public $taxonomies = [];
    public $hasArchive = false;
    public $rewrite = [];
    public $slug = 'knapsack_slug';
    public $withFront = true;
    public $feeds = false;
    public $pages = true;
    public $epMask = EP_PERMALINK;
    public $queryVar = 'knapsack_vars';
    public $canExport = true;
    public $deleteWithUser = false;

    public function args(): array
    {
        return [
          'labels'               => $this->labels(),
          'description'          => $this->description,
          'public'               => $this->public,
          'hierarchical'         => $this->hierarchical,
          'exclude_from_search'  => $this->excludeFromSearch,
          'publicly_queryable'   => $this->publiclyQueryable,
          'show_ui'              => $this->showUI,
          'show_in_menu'         => $this->showInMenu,
          'show_in_nav_menus'    => $this->showInNavMenus,
          'show_in_admin_bar'    => $this->showInAdminBar,
          'menu_position'        => $this->menuPosition,
          'menu_icon'            => $this->menuIcon(),
          'capability_type'      => $this->capabilityType,
          'capabilities'         => $this->capabilities,
          'map_meta_cap'         => $this->mapMetaCap,
          'supports'             => $this->supports(),
          'register_meta_box_cb' => $this->registerMetaBoxCallback,
          'taxonomies'           => $this->taxonomies,
          'has_archive'          => $this->hasArchive,
          'rewrite'              => $this->rewrite(),
          'query_var'            => $this->queryVar,
          'can_export'           => $this->canExport,
          'delete_with_user'     => $this->deleteWithUser,
        ];
    }

    public function labels(): array
    {
        $name = self::getName();
        $textDomain = Config::get('app.text-domain');

        $defaults = [
          'name'               => $this->plural,
          'singular_name'      => $name,
          'menu_name'          => $name,
          'name_admin_bar'     => $name,
          'add_new'            => sprintf(__('Add %s', $textDomain), $name),
          'add_new_item'       => sprintf(__('Add new  %s', $textDomain), $name),
          'edit_item'          => sprintf(__('Edit %s', $textDomain), $name),
          'new_item'           => sprintf(__('New %s', $textDomain), $name),
          'view_item'          => sprintf(__('View %s', $textDomain), $name),
          'search_items'       => sprintf(__('Search %s', $textDomain), $this->plural),
          'not_found'          => sprintf(__('No %s found', $textDomain), $this->plural),
          'not_found_in_trash' => sprintf(__('No %s found in trash', $textDomain), $this->plural),
          'all_items'          => $this->plural,
          'archive_title'      => $name,
          'parent_item_colon'  => '',
        ];

        if (empty($this->labels)) {
            return $defaults;
        }

        return array_merge(
            $defaults,
            $this->labels
        );
    }

    public function supports(): array
    {
        if (empty($this->supports)) {
            return [
              'title',
              'editor',
              'author',
              'thumbnail',
              'excerpt',
              'trackbacks',
              'custom-fields',
              'comments',
              'revisions',
              'post-formats',
              'tags',
            ];
        }

        return $this->supports;
    }

    public function rewrite(): array
    {
        if (empty($this->rewrite)) {
            return [
              'slug'       => $this->slug,
              'with_front' => $this->withFront,
              'pages'      => $this->pages,
              'ep_mask'    => $this->epMask,
            ];
        }

        return $this->rewrite;
    }

    public function menuIcon()
    {
        return '';
    }

    protected static function getPostAttributes()
    {
        return [
            'ID',
            'post_author',
            'post_date',
            'post_date_gmt',
            'post_content',
            'post_content_filtered',
            'post_title',
            'post_excerpt',
            'post_status',
            'post_type',
            'comment_status',
            'ping_status',
            'post_password',
            'post_name',
            'to_ping',
            'pinged',
            'post_parent',
            'menu_order',
            'post_mime_type',
            'guid',
            'import_id',
            'post_category',
            'page_template',
        ];
    }
}
