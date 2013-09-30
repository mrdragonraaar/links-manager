<?php
/**
 * Links.php
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

require_once('Categories.php');

// plugin link data path
if (!defined('LM_LINK_DATA')) {
define('LM_LINK_DATA', GSDATAOTHERPATH . 'links.xml');
}
// plugin backup link data path
if (!defined('LM_LINK_BACKUP')) {
//define('LM_LINK_BACKUP', GSBACKUPSPATH . 'other/links.xml');
}

// links admin panel
if (!defined('LM_LINK_ADMIN_TEMPLATE')) {
define('LM_LINK_ADMIN_TEMPLATE', LM_TEMPLATE_PATH . 
   'link_admin_panel.php');
}
// links edit panel
if (!defined('LM_LINK_EDIT_TEMPLATE')) {
define('LM_LINK_EDIT_TEMPLATE', LM_TEMPLATE_PATH . 
   'link_edit_panel.php');
}

// link template file
if (!defined('LM_LINK_HTML_TEMPLATE')) {
define('LM_LINK_HTML_TEMPLATE', LM_TEMPLATE_PATH . 'link_html.php');
}

/**
 * Define a collection of links.
 *
 * @author Adrian D. Elgar
 */
class Links
{
	private $links = array();
	private $categories;

	/**
	 * Construct a collection of links.
	 */
	function __construct()
	{
		$this->load();
		$this->init_categories();
	}

	/**
	 * Initialise categories.
	 * @return categories.
	 */
	private function init_categories()
	{
		return $this->categories = new Categories();
	}

	/**
	 * Get a link or all links.
	 * If no id is supplied will return all links.
	 * If id is string will search link names.
	 * @param $id link id
	 * @return link
	 */
	private function get($id = null)
	{
		if (empty($this->links))
			$this->load();

		if (isset($id))
		{
			if (is_integer($id))
				return isset($this->links[$id]) ? 
				   $this->links[$id] : null;

			return $this->get_by_name($id);
		}

		return $this->links;
	}

	/**
	 * Get a link by name.
	 * @param $name link name
	 * @return link
	 */
	private function get_by_name($name)
	{
		foreach ($this->links as $link)
			if ($link->name === $name)
				return $link;

		return null;
	}

	/**
	 * Get a link by slug.
	 * @param $slug link slug
	 * @return link
	 */
	private function get_by_slug($slug)
	{
		foreach ($this->links as $link)
			if ($link->slug === $slug)
				return $link;

		return null;
	}

	/**
	 * @overload
	 * Synonym for get().
	 * @param $id link id
	 * @return link
	 */
	public function __invoke($id = null)
	{
		return $this->get($id);
	}

	/**
	 * Check if there are links.
	 * @return true if links list is empty
	 */
	public function is_empty()
	{
		return empty($this->links);
	}

	/**
	 * Add a link.
	 * @param $id link id
	 * @param $name link name
	 * @param $slug link slug
	 * @param $url link url
	 * @param $page_slug link page slug
	 * @param $target link target
	 * @param $desc link description
	 * @param $category_id link category id
	 * @param $icon link icon url
	 * @return link
	 */
	public function add($id = null, $name, $slug, $url, $page_slug, $target,
	   $desc, $category_id, $icon)
	{
		$link = new Link($name, $slug, $url,
		   $page_slug, $target, $desc, $category_id, $icon);
		$link->set_links($this);

		if (isset($id) && is_integer($id))
			return $this->links[$id] = $link;

		return $this->links[] = $link;
	}

	/**
	 * Remove a link.
	 * @param $id link id
	 */
	public function remove($id)
	{
		$link = $this->get($id);

		if (isset($link))
			unset($this->links[$id]);
	}

	/**
	 * Load links.
	 */
	public function load()
	{
		$data = @getXML(LM_LINK_DATA);

		if (!empty($data))
		{
			foreach ($data->children() as $item)
			{
				$id = intval($item->id);
				$name = cl($item->name);
				$slug = cl($item->slug);
				$url = strval($item->url);
				$page_slug = strval($item->page);
				$target = cl($item->target);
				$desc = cl($item->desc);
				$category_id = intval($item->category);
				$icon = strval($item->icon);

				$this->add($id, $name, $slug, $url, $page_slug,
				   $target, $desc, $category_id, $icon);
			}
		}
	}

	/**
	 * Save links.
	 */
	public function save()
	{
		@copy(LM_LINK_DATA, LM_LINK_BACKUP);
		$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><channel></channel>');

		foreach ($this() as $id => $link)
		{
			$item = $xml->addChild('item');
			$elem = $item->addChild('id');
			$elem->addCData($id);
			$elem = $item->addChild('name');
			$elem->addCData($link->name);
			$elem = $item->addChild('slug');
			$elem->addCData($link->slug);
			$elem = $item->addChild('url');
			$elem->addCData($link->url);
			$elem = $item->addChild('page');
			$elem->addCData($link->page_slug);
			$elem = $item->addChild('target');
			$elem->addCData($link->target);
			$elem = $item->addChild('desc');
			$elem->addCData($link->desc);
			$elem = $item->addChild('category');
			$elem->addCData($link->category_id);
			$elem = $item->addChild('icon');
			$elem->addCData($link->icon);
		}

		return @XMLsave($xml, LM_LINK_DATA);
	}

	/**
	 * Get categories.
	 * @return categories.
	 */
	public function categories()
	{
		return isset($this->categories) ? $this->categories :
		   $this->init_categories();
	}

	/**
	 * Get links as html.
	 * @return links html
	 */
	public function toHtml($category_id = null)
	{
		$html = '<ul>';
		foreach ($this->links as $link)
			$html .= $link->toHtml();
		$html .= '</ul>';

		return $html;
	}

	static public function is_admin_url()
	{
		return isset($_GET['links']);
	}

	static public function admin_url()
	{
		return LM_PLUGIN_URL . '&amp;links';
	}

	public function admin_panel()
	{
		echo $this->return_admin_panel();
	}

	public function return_admin_panel()
	{
		ob_start();
		include(LM_LINK_ADMIN_TEMPLATE);
		return ob_get_clean();
	}

	static public function is_edit_url()
	{
		return self::is_admin_url() && isset($_GET['edit']);
	}

	static public function edit_url($id = null)
	{
		return self::admin_url() . '&amp;edit' . 
		   (isset($id) && is_integer($id) ? '=' . $id : '');
	}

	public function edit_panel()
	{
		echo $this->return_edit_panel();
	}

	public function return_edit_panel()
	{
		$id = (self::is_edit_url() && is_numeric($_GET['edit'])) ?
		   intval($_GET['edit']) : null;
		$link = isset($id) ? $this($id) : null;

		ob_start();
		include(LM_LINK_EDIT_TEMPLATE);
		return ob_get_clean();
	}

	static public function is_edit_submit()
	{
		return self::is_admin_url() && isset($_POST['submit']);
	}

	public function do_edit_submit()
	{
		$id = isset($_POST['link-id']) ? 
		   intval($_POST['link-id']) : null;
		$name = isset($_POST['link-name']) ?
		   safe_slash_html($_POST['link-name']) : null;
		$slug = isset($_POST['link-slug']) ?
		   safe_slash_html($_POST['link-slug']) : null;
		$url = isset($_POST['link-url']) ?
		   $_POST['link-url'] : null;
		$page_slug = isset($_POST['link-page-slug']) ?
		   $_POST['link-page-slug'] : null;
		$target = isset($_POST['link-target']) ?
		   $_POST['link-target'] : null;
		$desc = isset($_POST['link-desc']) ?
		   $_POST['link-desc'] : null;
		$category_id = isset($_POST['link-category-id']) ?
		   intval($_POST['link-category-id']) : -1;
		$icon = isset($_POST['link-icon']) ?
		   $_POST['link-icon'] : null;

		$this->add($id, $name, $slug, $url, $page_slug, $target, $desc,
		   $category_id, $icon);
		return $this->save();
	}

	static public function is_delete_url()
	{
		return self::is_admin_url() && isset($_GET['delete']);
	}

	static public function delete_url($id = null)
	{
		return self::admin_url() . '&amp;delete' . 
		   (isset($id) && is_integer($id) ? '=' . $id : '');
	}

	public function do_delete()
	{
		$id = (self::is_delete_url() && is_numeric($_GET['delete'])) ?
		   intval($_GET['delete']) : null;

		$this->remove($id);
		return $this->save();
	}

	static public function is_cancel_url()
	{
		return self::is_admin_url() && isset($_GET['cancel']);
	}

	static public function cancel_url()
	{
		return self::admin_url() . '&amp;cancel';
	}

	static public function is_undo_url()
	{
		return self::is_admin_url() && isset($_GET['undo']);
	}

	static public function undo_url()
	{
		return self::admin_url() . '&amp;undo';
	}

	public function do_undo()
	{
		return copy(LM_LINK_BACKUP, LM_LINK_DATA);
	}

	static public function is_change_order_submit()
	{
		return self::is_admin_url() && isset($_POST['order']);
	}

	public function do_change_order_submit()
	{
		$links = array();
		$link_order = explode(',', $_POST['link-order']);

		foreach ($link_order as $slug)
		{
			if ($slug)
			{
				$link = $this->get_by_slug($slug);
				if (isset($link))
					$links[] = $link;
			}
		}

		$this->links = $links;
		return $this->save();
	}

}

/**
 * Define a link.
 *
 * @author Adrian D. Elgar
 */
class Link
{
	private $name;
	private $slug;
	private $url;
	private $page_slug;
	private $target;
	private $desc;
	private $category_id;
	private $icon;
	private $links;

	/**
	 * Construct a link.
	 * @param $name link name
	 * @param $slug link slug
	 * @param $url link url
	 * @param $page_slug link page slug
	 * @param $target link target
	 * @param $desc link description
	 * @param $category_id link category id
	 * @param $icon link icon url
	 */
	function __construct($name, $slug, $url, $page_slug, $target, 
	   $desc, $category_id, $icon)
	{
		$this->name = $name;
		$this->slug = $slug ? $slug : $this->unique_slug();
		$this->url = $url;
		$this->page_slug = $page_slug;
		$this->target = $target;
		$this->desc = $desc;
		$this->category_id = $category_id;
		$this->icon = $icon;
	}

	/**
	 * Create an unique link slug.
	 * @return unique link slug
	 */
	private function unique_slug()
	{
		$slug_prefix = $this->name;
		$slug_prefix = to7bit($slug_prefix, "UTF-8");
		$slug_prefix = clean_url($slug_prefix);

		return uniqid($slug_prefix);
	}

	/**
	 * Set reference to links.
	 * @param &$links links reference
	 */
	public function set_links(&$links)
	{
		$this->links = &$links;
	}

	/**
	 * @overload
	 * Get link property.
	 * @param $prop property name
	 * @return property
	 */
	public function __get($prop)
	{
		return $this->$prop;
	}

	/**
	 * @overload
	 * Check if link property is set.
	 * @param $prop property name
	 * @return true if property is set
	 */
	public function __isset($prop)
	{
		return isset($this->$prop);
	}

	/**
	 * Get category.
	 * @return category
	 */
	public function category()
	{
		if (isset($this->links) && isset($this->category_id) &&
		   is_integer($this->category_id))
		{
			$categories = $this->links->categories();
			return $categories($this->category_id);
		}

		return null;
	}

	/**
	 * Get link page data.
	 * @return page data
	 */
	public function page()
	{
		if (isset($this->page_slug) && $this->page_slug)
		{
			foreach (get_available_pages() as $page)
				if ($page['slug'] === $this->page_slug)
					return $page;
		}

		return null;
	}

	/**
	 * Check whether link should display icon.
	 * @return true if icon should be displayed
	 */
	public function is_display_icon()
	{
		$category = $this->category();
		$display = isset($this->icon) && $this->icon;

		if (isset($category))
			$display = $display && !$category->is_display_text();

		return $display;
	}

	/**
	 * Check whether link should display text.
	 * @return true if text should be displayed
	 */
	public function is_display_text()
	{
		$category = $this->category();

		if (isset($category))
			return !$category->is_display_icon() || 
			   !isset($this->icon) || !$this->icon;

		return true;
	}

	/**
	 * Check whether link should show description.
	 * @return true if text should show description
	 */
	public function is_show_desc()
	{
		$category = $this->category();

		if (isset($category))
			return isset($this->desc) && $category->is_show_desc();

		return false;
	}

	/**
	 * @overload
	 * Get link as string.
	 * @return link name
	 */
	public function __toString()
	{
		return $this->name;
	}

	/**
	 * Get link as html.
	 * @return link html
	 */
	public function toHtml()
	{
		$link = $this;

		ob_start();
		include(LM_LINK_HTML_TEMPLATE);
		return ob_get_clean();
	}
}

?>
