<?php
/**
 * Categories.php
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

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
class Categories
{
	private $categories = array();

	/**
	 * Construct a collection of categories.
	 */
	function __construct()
	{
		$this->load();
	}

	/**
	 * Get a category or all categories.
	 * If no id is supplied will return all categories.
	 * If id is string will search category names.
	 * @param $id category id
	 * @return category
	 */
	private function get($id = null)
	{
		if (empty($this->categories))
			$this->load();

		if (isset($id))
		{
			if (is_integer($id))
				return isset($this->categories[$id]) ? 
				   $this->categories[$id] : null;

			return $this->get_by_name($id);
		}

		return $this->categories;
	}

	/**
	 * Get a category by name.
	 * @param $name category name
	 * @return category
	 */
	private function get_by_name($name)
	{
		foreach ($this->categories as $category)
			if ($category->name === $name)
				return $category;

		return null;
	}

	/**
	 * @overload
	 * Synonym for get().
	 * @param $id category id
	 * @return category
	 */
	public function __invoke($id = null)
	{
		return $this->get($id);
	}

	/**
	 * Check if there are categories.
	 * @return true if categories list is empty
	 */
	public function is_empty()
	{
		return empty($this->categories);
	}

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
		if (isset($id) && is_integer($id))
			$this->categories[$id] = new Category($name, $display, 
			   $show_desc);
		else
			$this->categories[] = new Category($name, $display, 
			   $show_desc);
	}

	/**
	 * Remove a category.
	 * @param $id category id
	 */
	public function remove($id)
	{
		$category = $this->get($id);

		if (isset($category))
			unset($this->categories[$id]);
	}

	/**
	 * Load categories.
	 */
	public function load()
	{
		$data = @getXML(LM_CATEGORY_DATA);

		if (!empty($data))
		{
			foreach ($data->children() as $item)
			{
				$id = intval($item->id);
				$name = cl($item->name);
				$display = cl($item->display);
				$show_desc = intval($item->show_desc);
				$this->add($id, $name, $display, 
				   $show_desc);
			}
		}
	}

	/**
	 * Save categories.
	 */
	public function save()
	{
		@copy(LM_CATEGORY_DATA, LM_CATEGORY_BACKUP);
		$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><channel></channel>');

		foreach ($this() as $id => $category)
		{
			$item = $xml->addChild('item');
			$elem = $item->addChild('id');
			$elem->addCData($id);
			$elem = $item->addChild('name');
			$elem->addCData($category->name);
			$elem = $item->addChild('display');
			$elem->addCData($category->display);
			$elem = $item->addChild('show_desc');
			$elem->addCData($category->show_desc);
		}

		return @XMLsave($xml, LM_CATEGORY_DATA);
	}

	static public function is_admin_url()
	{
		return isset($_GET['categories']);
	}

	static public function admin_url()
	{
		return LM_PLUGIN_URL . '&amp;categories';
	}

	public function admin_panel()
	{
		echo $this->return_admin_panel();
	}

	public function return_admin_panel()
	{
		ob_start();
		include(LM_CATEGORY_ADMIN_TEMPLATE);
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
		$category = isset($id) ? $this($id) : null;

		ob_start();
		include(LM_CATEGORY_EDIT_TEMPLATE);
		return ob_get_clean();
	}

	static public function is_edit_submit()
	{
		return self::is_admin_url() && isset($_POST['submit']);
	}

	public function do_edit_submit()
	{
		$id = isset($_POST['category-id']) ? 
		   intval($_POST['category-id']) : null;
		$name = isset($_POST['category-name']) ?
		   safe_slash_html($_POST['category-name']) : null;
		$display = isset($_POST['category-display']) ?
		   $_POST['category-display'] : null;
		$show_desc = isset($_POST['category-show-desc']) ?
		   intval($_POST['category-show-desc']) : 0;

		$this->add($id, $name, $display, $show_desc);
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
		return copy(LM_CATEGORY_BACKUP, LM_CATEGORY_DATA);
	}

}

/**
 * Define a category.
 *
 * @author Adrian D. Elgar
 */
class Category
{
	private $name;
	private $display;
	private $show_desc;

	/**
	 * Construct a category.
	 * @param $name category name
	 * @param $display link display options
	 * @param $show_desc show link description
	 */
	function __construct($name, $display = LM_CATEGORY_DISPLAY_BOTH,
	   $show_desc = false)
	{
		$this->name = $name;
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

	/**
	 * @overload
	 * Get category property.
	 * Only returns name.
	 * @param $prop property name
	 * @return property
	 */
	public function __get($prop)
	{
		return $this->$prop;
	}

	/**
	 * @overload
	 * Get category as string.
	 * @return category name
	 */
	public function __toString()
	{
		return $this->name;
	}

}

?>
