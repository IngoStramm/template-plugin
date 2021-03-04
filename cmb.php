<?php

add_action('cmb2_admin_init', 'wc_skip_cart_register_demo_metabox');

function wc_skip_cart_register_demo_metabox()
{

    $cmb_demo = new_cmb2_box(array(
        'id'            => 'wc_skip_cart_demo_metabox',
        'title'         => esc_html__('WooCommerce Skip Cart', 'cmb2'),
        'object_types' => array('options-page'),
        'option_key'      => 'wc_skip_cart_options', // The option key and admin menu page slug.
        // 'icon_url'        => 'dashicons-palmtree', // Menu icon. Only applicable if 'parent_slug' is left empty.
        // 'menu_title'      => esc_html__( 'Options', 'myprefix' ), // Falls back to 'title' (above).
        'parent_slug'     => 'options-general.php', // Make options page a submenu item of the themes menu.
        'capability'      => 'manage_options', // Cap required to view options-page.
        // 'position'        => 1, // Menu position. Only applicable if 'parent_slug' is left empty.
        // 'admin_menu_hook' => 'network_admin_menu', // 'network_admin_menu' to add network-level options page.
        // 'display_cb'      => false, // Override the options-page form output (CMB2_Hookup::options_page_output()).
        // 'save_button'     => esc_html__( 'Save Theme Options', 'myprefix' ), // The text for the options-page save button. Defaults to 'Save'.

    ));

    $cmb_demo->add_field(array(
        'name'       => esc_html__('Texto do botão "Comprar"', 'cmb2'),
        'id'         => 'wc_skip_cart_buy_btn',
        'type'       => 'text',
        'default'   => 'wc_skip_cart_default_buy_text_btn'
    ));

    $cmb_demo->add_field(array(
        'name'       => esc_html__('Texto do botão "Veja mais"', 'cmb2'),
        'id'         => 'wc_skip_cart_read_more_btn',
        'type'       => 'text',
        'default'   => 'wc_skip_cart_default_read_more_text_btn'
    ));
}

function wc_skip_cart_default_buy_text_btn()
{
    return __('Comprar', 'wc-skip-cart');
}

function wc_skip_cart_default_read_more_text_btn()
{
    return __('Veja mais', 'wc-skip-cart');
}

function get_wc_skip_cart_buy_text_btn()
{
    $buy_text = wc_skip_cart_get_option('wc_skip_cart_buy_btn');
    return !$buy_text ? wc_skip_cart_default_buy_text_btn() : $buy_text;
}

function get_wc_skip_cart_read_more_text_btn()
{
    $read_more_text = wc_skip_cart_get_option('wc_skip_cart_read_more_btn');
    return !$read_more_text ? wc_skip_cart_default_read_more_text_btn() : $read_more_text;
}

/**
 * Wrapper function around cmb2_get_option
 * @since  0.1.0
 * @param  string $key     Options array key
 * @param  mixed  $default Optional default value
 * @return mixed           Option value
 */
function wc_skip_cart_get_option($key = '', $default = false)
{
    if (function_exists('cmb2_get_option')) {
        // Use cmb2_get_option as it passes through some key filters.
        return cmb2_get_option('wc_skip_cart_options', $key, $default);
    }

    // Fallback to get_option if CMB2 is not loaded yet.
    $opts = get_option('wc_skip_cart_options', $default);

    $val = $default;

    if ('all' == $key) {
        $val = $opts;
    } elseif (is_array($opts) && array_key_exists($key, $opts) && false !== $opts[$key]) {
        $val = $opts[$key];
    }

    return $val;
}
