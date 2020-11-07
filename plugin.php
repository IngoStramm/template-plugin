<?php

/**
 * Plugin Name: Template Plugin
 * Plugin URI: https://agencialaf.com
 * Description: Descrição do Template Plugin.
 * Version: 0.0.1
 * Author: Ingo Stramm
 * Text Domain: template-plugin
 * License: GPLv2
 */

defined('ABSPATH') or die('No script kiddies please!');

define('TP_DIR', plugin_dir_path(__FILE__));
define('TP_URL', plugin_dir_url(__FILE__));

function tp_debug($debug)
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
    'template-plugin'
);
