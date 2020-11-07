<?php

/**
 * PREFIX_Post_Type class.
 *
 * Built Custom Post Types.
 *
 * @package  Odin
 * @category Post Types
 * @author   WPBrasil
 * @version  2.1.4
 */
class PREFIX_Post_Type
{

    /**
     * Array of labels for the post type.
     *
     * @var array
     */
    protected $labels = array();

    /**
     * Post type arguments.
     *
     * @var array
     */
    protected $arguments = array();

    /**
     * Construct Post Type.
     *
     * @param string $name       Singular name.
     * @param string $slug       Post type slug.
     */
    public function __construct($name, $slug)
    {
        $this->name = $name;
        $this->slug = $slug;

        // Register post type.
        add_action('init', array(&$this, 'register_post_type'));
    }

    /**
     * Set custom labels.
     *
     * @param array $labels Custom labels.
     */
    public function set_labels($labels = array())
    {
        $this->labels = $labels;
    }

    /**
     * Set custom arguments.
     *
     * @param array $arguments Custom arguments.
     */
    public function set_arguments($arguments = array())
    {
        $this->arguments = $arguments;
    }

    /**
     * Define Post Type labels.
     *
     * @return array Post Type labels.
     */
    protected function labels()
    {
        $default = array(
            'name'               => sprintf(__('%ss', 'text-domain'), $this->name),
            'singular_name'      => sprintf(__('%s', 'text-domain'), $this->name),
            'view_item'          => sprintf(__('View %s', 'text-domain'), $this->name),
            'edit_item'          => sprintf(__('Edit %s', 'text-domain'), $this->name),
            'search_items'       => sprintf(__('Search %s', 'text-domain'), $this->name),
            'update_item'        => sprintf(__('Update %s', 'text-domain'), $this->name),
            'parent_item_colon'  => sprintf(__('Parent %s:', 'text-domain'), $this->name),
            'menu_name'          => sprintf(__('%ss', 'text-domain'), $this->name),
            'add_new'            => __('Add New', 'text-domain'),
            'add_new_item'       => sprintf(__('Add New %s', 'text-domain'), $this->name),
            'new_item'           => sprintf(__('New %s', 'text-domain'), $this->name),
            'all_items'          => sprintf(__('All %ss', 'text-domain'), $this->name),
            'not_found'          => sprintf(__('No %s found', 'text-domain'), $this->name),
            'not_found_in_trash' => sprintf(__('No %s found in Trash', 'text-domain'), $this->name)
        );

        return array_merge($default, $this->labels);
    }

    /**
     * Define Post Type arguments.
     *
     * @return array Post Type arguments.
     */
    protected function arguments()
    {
        $default = array(
            'labels'              => $this->labels(),
            'hierarchical'        => false,
            'supports'            => array('title', 'editor', 'thumbnail', 'comments', 'revisions'),
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'publicly_queryable'  => true,
            'exclude_from_search' => false,
            'has_archive'         => true,
            'query_var'           => true,
            'can_export'          => true,
            'rewrite'             => true,
            'capability_type'     => 'post'
        );

        return array_merge($default, $this->arguments);
    }

    /**
     * Register Post Type.
     */
    public function register_post_type()
    {
        register_post_type($this->slug, $this->arguments());
    }
}
