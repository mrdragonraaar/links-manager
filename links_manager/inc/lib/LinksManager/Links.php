<?php
/**
 * Links.php
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

require_once('LinksManager.php');
require_once('Categories.php');

// plugin link data path
if (!defined('LM_LINK_DATA')) {
define('LM_LINK_DATA', GSDATAOTHERPATH . 'links.xml');
}
// plugin backup link data path
if (!defined('LM_LINK_BACKUP')) {
define('LM_LINK_BACKUP', GSBACKUPSPATH . 'other/links.xml');
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
class Links extends LinksManager
{
	protected $categories;

	/**
	 * Construct a collection of links.
	 */
	function __construct()
	{
		parent::__construct();
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
	 * Get a link by slug.
	 * @param $slug link slug
	 * @return link
	 */
	private function get_by_slug($slug)
	{
		foreach ($this() as $link)
			if ($link->slug === $slug)
				return $link;

		return null;
	}

	/**
	 * Get links by category id.
	 * @param $category_id category id
	 * @return links
	 */
	private function get_by_category($category_id)
	{
		$links = array();
		foreach ($this() as $id => $link)
			if ($link->category_id === $category_id)
				$links[$id] = $link;

		return $links;
	}

	/**
	 * Get maximum link id.
	 * @return max link id
	 */
	public function max_link_id()
	{
		return (count($this()) > 0) ? (count($this()) - 1) : 0;
	}

	/**
	 * Check if minimum id is valid.
	 * @return true if valid
	 */
	private function is_valid_min_link_id($min_id = null)
	{
		if (!isset($min_id) || !is_integer($min_id) ||
		   ($min_id < 0))
			return false;

		return true;
	}

	/**
	 * Check if maximum id is valid.
	 * @return true if valid
	 */
	private function is_valid_max_link_id($max_id = null)
	{
		if (!isset($max_id) || !is_integer($max_id) ||
		   ($max_id > $this->max_link_id()))
			return false;

		return true;
	}

	/**
	 * Get random link.
	 * @param $rand_min minimum value for id
	 * @param $rand_max maximum value for id
	 * @return random link
	 */
	public function get_rand_link($rand_min = null, $rand_max = null)
	{
		if (!$this->is_valid_min_link_id($rand_min))
		{
			$rand_min = 0;
			if (!$this->is_valid_max_link_id($rand_max))
				$rand_max = $this->max_link_id();
		}
		else
		{
			if (!isset($rand_max))
			{
				$rand_max = $rand_min;
				$rand_min = 0;
			}

			if (!$this->is_valid_max_link_id($rand_max))
				$rand_max = $this->max_link_id();
		}

		$id = rand($rand_min, $rand_max);
		return $this($id);
	}

	/**
	 * Search links name and desc for keyword.
	 * @param $keyword keyword
	 * @return links
	 */
	public function search_links($keyword)
	{
		$links = array();
		foreach ($this() as $id => $link)
			if ((stripos($link->name, $keyword) !== false) ||
			   (stripos($link->name, $keyword) !== false))
				$links[$id] = $link;

		return $links;
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

		$this->add_obj($id, $link);
	}

	/**
	 * Get link from xml item.
	 * @param $item xml element
	 * @return link
	 */
	function get_xml_obj(SimpleXMLElement $item)
	{
		$name = cl($item->name);
		$slug = cl($item->slug);
		$url = strval($item->url);
		$page_slug = strval($item->page);
		$target = cl($item->target);
		$desc = cl($item->desc);
		$category_id = intval($item->category);
		$icon = strval($item->icon);

		$link = new Link($name, $slug, $url, $page_slug, $target, $desc,
		   $category_id, $icon);
		$link->set_links($this);
		return $link;
	}

	/**
	 * Get xml item from link.
	 * @param $link link
	 * @param $item xml element
	 */
	function get_xml_item(LinksManager_Object $link, 
	   SimpleXMLElement &$item)
	{
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
	 * @param $category_id category id
	 * @return links html
	 */
	public function toHtml($category_id = null)
	{
		$links = (isset($category_id) && is_int($category_id)) ?
		   $this->get_by_category($category_id) : $this();

		return $this->linksToHtml($links);
	}

	/**
	 * Search links as html.
	 * @param $keyword keyword
	 * @return links html
	 */
	public function searchToHtml($keyword)
	{
		$links = $this->search_links($keyword);

		return $this->linksToHtml($links);
	}

	/**
	 * Get list of links as html.
	 * @param $links links array
	 * @return links html
	 */
	private function linksToHtml($links)
	{
		$html = "<ul>\n";
		foreach ($links as $link)
			$html .= $link->toHtml();
		$html .= "</ul>\n";

		return $html;
	}

	/**
	 * Get url slug of links admin section.
	 * @return links admin slug
	 */
	static public function admin_slug()
	{
		return 'links';
	}

	/**
	 * Get links admin panel template.
	 * @return links admin panel template
	 */
	static public function admin_panel_template()
	{
		return LM_LINK_ADMIN_TEMPLATE;
	}

	/**
	 * Get links edit panel template.
	 * @return links edit panel template
	 */
	static public function edit_panel_template()
	{
		return LM_LINK_EDIT_TEMPLATE;
	}

	/**
	 * Get link id from edit form submit.
	 * return link id.
	 */
	static public function get_edit_submit_id()
	{
		return isset($_POST['link-id']) ? 
		   intval($_POST['link-id']) : null;
	}

	/**
	 * Get link from edit form submit.
	 * return link.
	 */
	public function get_edit_submit()
	{
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

		$link = new Link($name, $slug, $url, $page_slug, $target, $desc,
		   $category_id, $icon);
		$link->set_links($this);
		return $link;
	}

	/**
	 * Check if change link order has been submitted.
	 * @return true if change order submit
	 */
	static public function is_change_order_submit()
	{
		return self::is_admin_url() && isset($_POST['order']);
	}

	/**
	 * Perform change order submit action.
	 */
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

		$this->objects = $links;
		return $this->save();
	}

	/**
	 * Get links data xml file.
	 * @return links data xml file
	 */
	static public function data_xml()
	{
		return LM_LINK_DATA;
	}

	/**
	 * Get links backup data xml file.
	 * @return links backup data xml file
	 */
	static public function data_backup_xml()
	{
		return LM_LINK_BACKUP;
	}
}

/**
 * Define a link.
 *
 * @author Adrian D. Elgar
 */
class Link extends LinksManager_Object
{
	protected $slug;
	protected $url;
	protected $page_slug;
	protected $target;
	protected $desc;
	protected $category_id;
	protected $icon;
	protected $links;

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
		parent::__construct($name);
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
