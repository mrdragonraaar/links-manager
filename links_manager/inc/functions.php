<?php
/**
 * functions.php
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

require_once('lib/LinksManager/Links.php');

$lm_links = new Links();

/**
 * Main plugin function.
 */
function lm_main()
{
	global $lm_links;
	$lm_categories = $lm_links->categories();

	if (Links::is_admin_url())
	{
		if (Links::is_edit_url())
			$lm_links->edit_panel();
		else
		{
			if (Links::is_edit_submit())
				$lm_links->do_edit_submit() ?
				   Links::set_edit_success() :
				   Links::set_edit_error();

			if (Links::is_delete_url())
				$lm_links->do_delete() ?
				   Links::set_del_success() :
				   Links::set_del_error();

			if (Links::is_undo_url())
				$lm_links->do_undo() ?
				   Links::set_undo_success() :
				   Links::set_undo_error();

			if (Links::is_change_order_submit())
				$lm_links->do_change_order_submit() ?
				   Links::set_edit_success() :
				   Links::set_edit_error();

			Links::do_notify();

			$lm_links->admin_panel();
		}
	}

	if (Categories::is_admin_url())
	{
		if (Categories::is_edit_url())
			$lm_categories->edit_panel();
		else
		{
			if (Categories::is_edit_submit())
				$lm_categories->do_edit_submit() ? 
				   Categories::set_edit_success() :
				   Categories::set_edit_error();

			if (Categories::is_delete_url())
				$lm_categories->do_delete() ?
				   Categories::set_del_success() :
				   Categories::set_del_error();

			if (Categories::is_undo_url())
				$lm_categories->do_undo() ?
				   Categories::set_undo_success() :
				   Categories::set_undo_error();

			Categories::do_notify();

			$lm_categories->admin_panel();
		}
	}

	if (Links::is_instructions_url())
		Links::instructions_panel();
}

/**
 * Plugin filter.
 */
function lm_filter($content)
{
	/* (% links %) */
	$content = preg_replace_callback('/\(%\s*links\s*%\)/',
	   function($matches) {
		return return_links();
	   }, $content);

	/* (% links:id %) */
	$content = preg_replace_callback('/\(%\s*links:(\d+)\s*%\)/',
	   function($matches) {
		$id = intval($matches[1]);
		return return_links($id);
	   }, $content);

	/* (% search_links:keyword %) */
	$content = preg_replace_callback('/\(%\s*search_links:([\w+\s*\w+]+?)\s*%\)/',
	   function($matches) {
		$keyword = $matches[1];
		return return_search_links($keyword);
	   }, $content);

	/* (% link:id %) */
	$content = preg_replace_callback('/\(%\s*link:(\d+)\s*%\)/',
	   function($matches) {
		$id = intval($matches[1]);
		return return_link($id);
	   }, $content);

	/* (% link:name %) */
	$content = preg_replace_callback('/\(%\s*link:([\w+\s*\w+]+?)\s*%\)/',
	   function($matches) {
		$name = $matches[1];
		return return_link($name);
	   }, $content);

	/* (% randlink %) */
	$content = preg_replace_callback('/\(%\s*randlink\s*%\)/',
	   function($matches) {
		return return_randlink();
	   }, $content);

	/* (% randlink:max %) */
	$content = preg_replace_callback('/\(%\s*randlink:(\d+)\s*%\)/',
	   function($matches) {
		$max = intval($matches[1]);
		return return_randlink($max);
	   }, $content);

	/* (% randlink:min:max %) */
	$content = preg_replace_callback('/\(%\s*randlink:(\d+):(\d+)\s*%\)/',
	   function($matches) {
		$min = intval($matches[1]);
		$max = intval($matches[2]);
		return return_randlink($min, $max);
	   }, $content);

	/* (% link_category:id %) */
	$content = preg_replace_callback('/\(%\s*link_category:(\d+)\s*%\)/',
	   function($matches) {
		$id = intval($matches[1]);
		return return_category_name($id);
	   }, $content);

	return $content;
}

/**
 * Display a link.
 * @param $id link id or link name
 */
function get_link($id)
{
	echo return_link($id);
}

/**
 * Get a link.
 * @param $id link id or link name
 * @return link html
 */
function return_link($id)
{
	global $lm_links;
	$link = $lm_links($id);

	if (isset($link) && !is_array($link))
		return $link->toHtml();

	return null;
}

/**
 * Display a random link.
 * @param $rand_min minimum id
 * @param $rand_max maximum id
 */
function get_randlink($rand_min = null, $rand_max = null)
{
	echo return_randlink($rand_min, $rand_max);
}

/**
 * Get a random link.
 * @param $rand_min minimum id
 * @param $rand_max maximum id
 * @return link html
 */
function return_randlink($rand_min = null, $rand_max = null)
{
	global $lm_links;
	$link = $lm_links->get_rand_link($rand_min, $rand_max);

	if (isset($link) && !is_array($link))
		return $link->toHtml();

	return null;
}

/**
 * Display list of links.
 * If no category id is supplied will display all links.
 * @param $category_id category id
 */
function get_links($category_id = null)
{
	echo return_links($category_id);
}

/**
 * Get list of links.
 * If no category id is supplied will return all links.
 * @param $category_id category id
 * @return links html
 */
function return_links($category_id = null)
{
	global $lm_links;

	return $lm_links->toHtml($category_id);
}

/**
 * Display list of links searched by keyword.
 * @param $keyword
 */
function search_links($keyword)
{
	echo return_search_links($keyword);
}

/**
 * Get list of links searched by keyword.
 * @param $keyword
 * @return links html
 */
function return_search_links($keyword)
{
	global $lm_links;

	return $lm_links->searchToHtml($keyword);
}

/**
 * Display category name.
 * @param $category_id category id
 */
function get_category_name($category_id)
{
	echo return_category_name($category_id);
}

/**
 * Get category name.
 * @param $category_id category id
 * @return category name
 */
function return_category_name($category_id)
{
	global $lm_links;
	$categories = $lm_links->categories();
	$category = $categories($category_id);

	if (isset($category) && !is_array($category))
		return (string)$category;

	return null;
}

?>
