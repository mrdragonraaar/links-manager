<?php
/*
Plugin Name: Links Manager
Description: Manage a collection of links and link categories
Version: 2.0.4
Author: Adrian D. Elgar
Author URI: http://mrdragonraaar.com
*/

// plugin id
define('LM_PLUGIN_ID', basename(__FILE__, '.php'));
// plugin path
define('LM_PLUGIN_PATH', GSPLUGINPATH . LM_PLUGIN_ID . '/');
// plugin includes path
define('LM_INC_PATH', LM_PLUGIN_PATH . 'inc/');
// plugin template path
define('LM_TEMPLATE_PATH', LM_PLUGIN_PATH . 'template/');
// plugin url
define('LM_PLUGIN_URL', 'load.php?id=' . LM_PLUGIN_ID);

// add in this plugin's language file
i18n_merge(LM_PLUGIN_ID) || i18n_merge(LM_PLUGIN_ID, 'en_US');

// register plugin
register_plugin(
	LM_PLUGIN_ID,
	i18n_r(LM_PLUGIN_ID . '/LM_TITLE'),
	'2.0.4',
	'Adrian D. Elgar',
	'http://mrdragonraaar.com',
	i18n_r(LM_PLUGIN_ID . '/LM_DESC'),
	'links',
	'lm_main'
);

// hooks
add_action('nav-tab', 'createNavTab', 
   array('links', LM_PLUGIN_ID, i18n_r(LM_PLUGIN_ID . '/LINKS'), 'links'));
add_action('links-sidebar', 'createSideMenu', 
   array(LM_PLUGIN_ID, i18n_r(LM_PLUGIN_ID . '/MANAGE_LINKS'), 'links'));
add_action('links-sidebar', 'createSideMenu', 
   array(LM_PLUGIN_ID, i18n_r(LM_PLUGIN_ID . '/MANAGE_CATEGORIES'), 'categories'));
add_action('links-sidebar', 'createSideMenu', 
   array(LM_PLUGIN_ID, i18n_r(LM_PLUGIN_ID . '/INSTRUCTIONS'), 'instructions'));
add_filter('content', 'lm_filter');

// includes
require_once(LM_INC_PATH . 'functions.php');

// javascript
register_script('jquery-validate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.min.js', '1.10.0');
register_script(LM_PLUGIN_ID, $SITEURL . 'plugins/' . LM_PLUGIN_ID . '/js/' . LM_PLUGIN_ID . '.js', '2.0.4', true);
queue_script('jquery-ui', GSBACK);
queue_script('jquery-validate', GSBACK);
//queue_script(LM_PLUGIN_ID, GSBACK);

// stylesheet
register_style(LM_PLUGIN_ID, $SITEURL . 'plugins/' . LM_PLUGIN_ID . '/css/' . LM_PLUGIN_ID . '.css', '2.0.4', 'screen');
queue_style(LM_PLUGIN_ID, GSBACK);
register_style(LM_PLUGIN_ID . '_links', $SITEURL . 'plugins/' . LM_PLUGIN_ID . '/css/' . LM_PLUGIN_ID . '_links.css', '2.0.4', 'screen');
queue_style(LM_PLUGIN_ID . '_links', GSFRONT);

?>
