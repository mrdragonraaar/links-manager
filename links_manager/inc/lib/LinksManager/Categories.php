<?php
/**
 * Categories.php
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

require_once('LinksManager.php');

// plugin category data path
if (!defined('LM_CATEGORY_DATA')) {
define('LM_CATEGORY_DATA', GSDATAOTHERPATH . 'link_categories.xml');
}
// plugin backup category data path
if (!defined('LM_CATEGORY_BACKUP')) {
define('LM_CATEGORY_BACKUP', GSBACKUPSPATH . 'other/link_categories.xml');
}

// categories admin panel
if (!defined('LM_CATEGORY_ADMIN_TEMPLATE')) {
define('LM_CATEGORY_ADMIN_TEMPLATE', LM_TEMPLATE_PATH . 
   'category_admin_panel.php');
}
// categories edit panel
if (!defined('LM_CATEGORY_EDIT_TEMPLATE')) {
define('LM_CATEGORY_EDIT_TEMPLATE', LM_TEMPLATE_PATH . 
   'category_edit_panel.php');
}

// category display option: icon only
define('LM_CATEGORY_DISPLAY_ICON', 'icon');
// category display option: text only
define('LM_CATEGORY_DISPLAY_TEXT', 'text');

/**
 * Define a collection of categories.
 *
 * @author Adrian D. Elgar
 */
class Categories extends LinksManager
{
	/**
	 * Add a category.
	 * @param $id category id
	 * @param $name category name
	 * @param $display link display options
	 * @param $show_desc show link description
	 * @return category
	 */
	public function add($id = null, $name, $display, $show_desc)
	{
		$category = new Category($name, $display, $show_desc);

		$this->add_obj($id, $category);
	}

	/**
	 * Get category from xml item.
	 * @param $item xml element
	 * @return category
	 */
	function get_xml_obj(SimpleXMLElement $item)
	{
		$name = cl($item->name);
		$display = cl($item->display);
		$show_desc = intval($item->show_desc);

		return new Category($name, $display, $show_desc);
	}

	/**
	 * Get xml item from category.
	 * @param $category category
	 * @param $item xml element
	 */
	function get_xml_item(LinksManager_Object $category, 
	   SimpleXMLElement &$item)
	{
		$elem = $item->addChild('name');
		$elem->addCData($category->name);
		$elem = $item->addChild('display');
		$elem->addCData($category->display);
		$elem = $item->addChild('show_desc');
		$elem->addCData($category->show_desc);
	}

	/**
	 * Get url slug of categories admin section.
	 * @return categories admin slug
	 */
	static public function admin_slug()
	{
		return 'categories';
	}

	/**
	 * Get categories admin panel template.
	 * @return categories admin panel template
	 */
	static public function admin_panel_template()
	{
		return LM_CATEGORY_ADMIN_TEMPLATE;
	}

	/**
	 * Get categories edit panel template.
	 * @return categories edit panel template
	 */
	static public function edit_panel_template()
	{
		return LM_CATEGORY_EDIT_TEMPLATE;
	}

	/**
	 * Get category id from edit form submit.
	 * return category id.
	 */
	static public function get_edit_submit_id()
	{
		return isset($_POST['category-id']) ? 
		   intval($_POST['category-id']) : null;
	}

	/**
	 * Get category from edit form submit.
	 * return category.
	 */
	public function get_edit_submit()
	{
		$name = isset($_POST['category-name']) ?
		   safe_slash_html($_POST['category-name']) : null;
		$display = isset($_POST['category-display']) ?
		   $_POST['category-display'] : null;
		$show_desc = isset($_POST['category-show-desc']) ?
		   intval($_POST['category-show-desc']) : 0;

		return new Category($name, $display, $show_desc);
	}

	/**
	 * Get categories data xml file.
	 * @return categories data xml file
	 */
	static public function data_xml()
	{
		return LM_CATEGORY_DATA;
	}

	/**
	 * Get categories backup data xml file.
	 * @return categories backup data xml file
	 */
	static public function data_backup_xml()
	{
		return LM_CATEGORY_BACKUP;
	}
}

/**
 * Define a category.
 *
 * @author Adrian D. Elgar
 */
class Category extends LinksManager_Object
{
	protected $display;
	protected $show_desc;

	/**
	 * Construct a category.
	 * @param $name category name
	 * @param $display link display options
	 * @param $show_desc show link description
	 */
	function __construct($name, $display = LM_CATEGORY_DISPLAY_BOTH,
	   $show_desc = false)
	{
		parent::__construct($name);
		$this->display = $display;
		$this->show_desc = $show_desc;
	}

	/**
	 * Check if display types match.
	 * @param $type display type
	 * @return true if display types match
	 */
	protected function is_display($type)
	{
		return isset($this->display) && ($this->display === $type);
	}

	/**
	 * Check if links in this category should only display icon.
	 * @return true if display icon
	 */
	public function is_display_icon()
	{
		return $this->is_display(LM_CATEGORY_DISPLAY_ICON);
	}

	/**
	 * Check if links in this category should only display text.
	 * @return true if display text
	 */
	public function is_display_text()
	{
		return $this->is_display(LM_CATEGORY_DISPLAY_TEXT);
	}

	/**
	 * Check if links in this category should show description.
	 * @return true if show description
	 */
	public function is_show_desc()
	{
		return isset($this->show_desc) && $this->show_desc;
	}

}

?>
