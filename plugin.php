<?php

/**
 * Plugin Name: Template Plugin
 * Plugin URI: https://agencialaf.com
 * Description: Descrição do Template Plugin.
 * Version: 0.0.1
 * Author: Ingo Stramm
 * Text Domain: text-domain
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('PREFIX_DIR', plugin_dir_path(__FILE__));
define('PREFIX_URL', plugin_dir_url(__FILE__));

function prefix_debug($debug)
{
    echo '<pre>';
    var_dump($debug);
    echo '</pre>';
}

require_once 'tgm/tgm.php';
require_once 'classes/classes.php';
require_once 'scripts.php';

require 'plugin-update-checker-4.10/plugin-update-checker.php';
$updateChecker = Puc_v4_Factory::buildUpdateChecker(
    'https://raw.githubusercontent.com/IngoStramm/template-plugin/master/info.json',
    __FILE__,
    'text-domain'
);
