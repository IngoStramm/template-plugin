<?php

add_action('wp_enqueue_scripts', 'tp_frontend_scripts');

function tp_frontend_scripts()
{

    $min = (in_array($_SERVER['REMOTE_ADDR'], array('127.0.0.1','::1', '10.0.0.3'))) ? '' : '.min';

    if (empty($min)) :
        wp_enqueue_script('template-plugin-livereload', 'http://localhost:35729/livereload.js?snipver=1', array(), null, true);
    endif;

    wp_register_script('template-plugin-script', TP_URL . 'assets/js/template-plugin' . $min . '.js', array('jquery', 'jquery-mask', 'jquery-cross-origin'), '1.0.0', true);
    
    wp_enqueue_script('template-plugin-script');
    
    wp_localize_script('template-plugin-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    wp_enqueue_style('template-plugin-style', TP_URL . 'assets/css/template-plugin.css', array(), false, 'all');


}