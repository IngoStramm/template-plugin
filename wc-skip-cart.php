<?php

/**
 * Plugin Name: Woocommerce Skip Cart
 * Plugin URI: https://agencialaf.com
 * Description: Descrição do Woocommerce Skip Cart.
 * Version: 0.0.1
 * Author: Ingo Stramm
 * Text Domain: wc-skip-cart
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('WC_SKIP_CART_DIR', plugin_dir_path(__FILE__));
define('WC_SKIP_CART_URL', plugin_dir_url(__FILE__));

function wc_skip_cart_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

require_once 'tgm/tgm.php';
// require_once 'classes/classes.php';
// require_once 'scripts.php';
require_once 'cmb.php';
require_once 'functions.php';
require 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/wc-skip-cart/master/info.json',
    __FILE__,
    'wc-skip-cart'
);
