<?php
/**
 * LinksManager.php
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

// instructions admin panel
if (!defined('LM_INSTRUCTIONS_TEMPLATE')) {
define('LM_INSTRUCTIONS_TEMPLATE', LM_TEMPLATE_PATH . 
   'instructions_panel.php');
}

/**
 * Define LinksManager abstract class.
 *
 * @author Adrian D. Elgar
 */
abstract class LinksManager implements ILinksManager
{
	protected $objects = array();

	/**
	 * Construct Links Manager.
	 */
	function __construct()
	{
		$this->load();
	}

	/**
	 * Get an object or all objects.
	 * If no id is supplied will return all objects.
	 * If id is string will search object names.
	 * @param $id object id
	 * @return LinksManager_Object
	 */
	private function get($id = null)
	{
		if (empty($this->objects))
			$this->load();

		if (isset($id))
		{
			if (is_integer($id))
				return isset($this->objects[$id]) ? 
				   $this->objects[$id] : null;

			return $this->get_by_name($id);
		}

		return $this->objects;
	}

	/**
	 * Get an object by name.
	 * @param $name object name
	 * @return LinksManager_Object
	 */
	private function get_by_name($name)
	{
		foreach ($this->objects as $object)
			if ($object->name === $name)
				return $object;

		return null;
	}

	/**
	 * @overload
	 * Synonym for get().
	 * @param $id object id
	 * @return LinksManager_Object
	 */
	public function __invoke($id = null)
	{
		return $this->get($id);
	}

	/**
	 * Check if there are objects.
	 * @return true if objects list is empty
	 */
	public function is_empty()
	{
		return empty($this->objects);
	}

	/**
	 * Add an object.
	 * @param $id object id
	 * @param $obj object
	 */
	public function add_obj($id = null, LinksManager_Object $obj)
	{
		if (isset($id) && is_integer($id))
			$this->objects[$id] = $obj;
		else
			$this->objects[] = $obj;
	}

	/**
	 * Load objects.
	 */
	public function load()
	{
		$data = @getXML(static::data_xml());

		if (!empty($data))
		{
			$this->objects = array();
			foreach ($data->children() as $item)
			{
				$id = intval($item->id);
				$obj = $this->get_xml_obj($item);

				$this->add_obj($id, $obj); 
			}

			return true;
		}

		return false;
	}

	/**
	 * Save objects.
	 */
	public function save()
	{
		@copy(static::data_xml(), static::data_backup_xml());
		$xml = new SimpleXMLExtended('<?xml version="1.0" encoding="UTF-8"?><channel></channel>');

		foreach ($this() as $id => $obj)
		{
			$item = $xml->addChild('item');
			$elem = $item->addChild('id');
			$elem->addCData($id);
			$this->get_xml_item($obj, $item);
		}

		return @XMLsave($xml, static::data_xml());
	}

	/**
	 * Restore objects.
	 */
	public function restore()
	{
		return copy(static::data_backup_xml(), static::data_xml());
	}

	/**
	 * Remove an object.
	 * @param $id object id
	 */
	public function remove($id)
	{
		$object = $this($id);

		if (isset($object))
			unset($this->objects[$id]);
	}

	/**
	 * Check if current url is admin url.
	 * @return true if admin url
	 */
	static public function is_admin_url()
	{
		return isset($_GET[static::admin_slug()]);
	}

	/**
	 * Get admin url.
	 * @return admin url
	 */
	static public function admin_url()
	{
		return LM_PLUGIN_URL . '&amp;' . static::admin_slug();
	}

	/**
	 * Display admin panel.
	 */
	public function admin_panel()
	{
		echo $this->return_admin_panel();
	}

	/**
	 * Get admin panel.
	 * @return admin panel html
	 */
	public function return_admin_panel()
	{
		ob_start();
		include(static::admin_panel_template());
		return ob_get_clean();
	}

	/**
	 * Check if current url is edit url.
	 * @return true if edit url
	 */
	static public function is_edit_url()
	{
		return self::is_admin_url() && isset($_GET['edit']);
	}

	/**
	 * Get edit url.
	 * @param $id id
	 * @return edit url
	 */
	static public function edit_url($id = null)
	{
		return self::admin_url() . '&amp;edit' . 
		   (isset($id) && is_integer($id) ? '=' . $id : '');
	}

	/**
	 * Display edit panel.
	 */
	public function edit_panel()
	{
		echo $this->return_edit_panel();
	}

	/**
	 * Get edit panel.
	 * @return edit panel html
	 */
	public function return_edit_panel()
	{
		$id = (self::is_edit_url() && is_numeric($_GET['edit'])) ?
		   intval($_GET['edit']) : null;

		ob_start();
		include(static::edit_panel_template());
		return ob_get_clean();
	}

	/**
	 * Check if edit form has been submitted.
	 * @return true if edit submit
	 */
	static public function is_edit_submit()
	{
		return self::is_admin_url() && isset($_POST['submit']);
	}

	/**
	 * Perform edit form submit action.
	 */
	public function do_edit_submit()
	{
		$id = static::get_edit_submit_id();
		$obj = $this->get_edit_submit();

		$this->add_obj($id, $obj);
		return $this->save();
	}

	/**
	 * Check if current url is delete url.
	 * @return true if delete url
	 */
	static public function is_delete_url()
	{
		return self::is_admin_url() && isset($_GET['delete']);
	}

	/**
	 * Get delete url.
	 * @param $id id
	 * @return delete url
	 */
	static public function delete_url($id = null)
	{
		return self::admin_url() . '&amp;delete' . 
		   (isset($id) && is_integer($id) ? '=' . $id : '');
	}

	/**
	 * Perform delete action.
	 */
	public function do_delete()
	{
		$id = (self::is_delete_url() && is_numeric($_GET['delete'])) ?
		   intval($_GET['delete']) : null;

		$this->remove($id);
		return $this->save();
	}

	/**
	 * Check if current url is cancel url.
	 * @return true if cancel url
	 */
	static public function is_cancel_url()
	{
		return self::is_admin_url() && isset($_GET['cancel']);
	}

	/**
	 * Get cancel url.
	 * @return cancel url
	 */
	static public function cancel_url()
	{
		return self::admin_url() . '&amp;cancel';
	}

	/**
	 * Check if current url is undo url.
	 * @return true if undo url
	 */
	static public function is_undo_url()
	{
		return self::is_admin_url() && isset($_GET['undo']);
	}

	/**
	 * Get undo url.
	 * @return undo url
	 */
	static public function undo_url()
	{
		return self::admin_url() . '&amp;undo';
	}

	/**
	 * Perform undo action.
	 */
	public function do_undo()
	{
		return $this->restore() && $this->load();
	}

	/**
	 * Check if current url is instructions url.
	 * @return true if instructions url
	 */
	static public function is_instructions_url()
	{
		return isset($_GET['instructions']);
	}

	/**
	 * Get instructions url.
	 * @return instructions url
	 */
	static public function instructions_url()
	{
		return LM_PLUGIN_URL . '&amp;instructions';
	}

	/**
	 * Display instructions panel.
	 */
	static public function instructions_panel()
	{
		echo self::return_instructions_panel();
	}

	/**
	 * Get instructions panel.
	 * @return instructions panel html
	 */
	static public function return_instructions_panel()
	{
		ob_start();
		include(self::instructions_panel_template());
		return ob_get_clean();
	}

	/**
	 * Get instructions admin panel template.
	 * @return instructions admin panel template
	 */
	static public function instructions_panel_template()
	{
		return LM_INSTRUCTIONS_TEMPLATE;
	}

	/**
	 * Perform notify action.
	 */
	static public function do_notify()
	{
		$update = '';
		if (isset($_GET['lm-upd']))
			$update = function_exists('filter_var') ?
			   filter_var($_GET['lm-upd'],
			      FILTER_SANITIZE_SPECIAL_CHARS) :
			   htmlentities($_GET['lm-upd']);
		$success = '';
		if (isset($_GET['lm-success']))
			$success = function_exists('filter_var') ?
			   filter_var($_GET['lm-success'],
			      FILTER_SANITIZE_SPECIAL_CHARS) :
			   htmlentities($_GET['lm-success']);
		$error = '';
		if (isset($_GET['lm-error']))
			$error = function_exists('filter_var') ?
			   filter_var($_GET['lm-error'],
			      FILTER_SANITIZE_SPECIAL_CHARS) :
			   htmlentities($_GET['lm-error']);

		switch ($update)
		{
			case 'edit-success':
				echo self::notify_save_success();
				break;
			case 'edit-error':
				echo self::notify_save_error();
				break;
			case 'undo-success':
				echo self::notify_restore_success();
				break;
			case 'undo-error':
				echo self::notify_restore_error();
				break;
			case 'del-success':
				echo self::notify_delete_success();
				break;
			case 'del-error':
				echo self::notify_delete_error();
				break;
			default:
				if ($success)
					echo self::notify_ok($success, false);
				else if ($error)
					echo self::notify_error($error);
				break;
		}
	}

	/**
	 * Set notify action to successful edit.
	 */
	static public function set_edit_success()
	{
		$_GET['lm-upd'] = 'edit-success';
	}

	/**
	 * Set notify action to edit error.
	 */
	static public function set_edit_error()
	{
		$_GET['lm-upd'] = 'edit-error';
	}

	/**
	 * Set notify action to successful undo.
	 */
	static public function set_undo_success()
	{
		$_GET['lm-upd'] = 'undo-success';
	}

	/**
	 * Set notify action to undo error.
	 */
	static public function set_undo_error()
	{
		$_GET['lm-upd'] = 'undo-error';
	}

	/**
	 * Set notify action to successful delete.
	 */
	static public function set_del_success()
	{
		$_GET['lm-upd'] = 'del-success';
	}

	/**
	 * Set notify action to delete error.
	 */
	static public function set_del_error()
	{
		$_GET['lm-upd'] = 'del-error';
	}

	/**
	 * Get successful save notify.
	 * @return save success notify
	 */
	static public function notify_save_success()
	{
		$slug = static::admin_slug();
		$msg = sprintf(i18n_r(LM_PLUGIN_ID . '/SUCCESS_SAVE'),
		   i18n_r(LM_PLUGIN_ID . '/' . strtoupper($slug)));

		return self::notify_ok($msg);
	}

	/**
	 * Get save error notify.
	 * @return save error notify
	 */
	static public function notify_save_error()
	{
		$slug = static::admin_slug();
		$msg = sprintf(i18n_r(LM_PLUGIN_ID . '/ERROR_SAVE'),
		   i18n_r(LM_PLUGIN_ID . '/' . strtoupper($slug)));

		return self::notify_error($msg);
	}

	/**
	 * Get successful restore notify.
	 * @return restore success notify
	 */
	static public function notify_restore_success()
	{
		$slug = static::admin_slug();
		$msg = sprintf(i18n_r(LM_PLUGIN_ID . '/SUCCESS_RESTORE'),
		   i18n_r(LM_PLUGIN_ID . '/' . strtoupper($slug)));

		return self::notify_ok($msg, false);
	}

	/**
	 * Get restore error notify.
	 * @return restore error notify
	 */
	static public function notify_restore_error()
	{
		$slug = static::admin_slug();
		$msg = sprintf(i18n_r(LM_PLUGIN_ID . '/ERROR_RESTORE'),
		   i18n_r(LM_PLUGIN_ID . '/' . strtoupper($slug)));

		return self::notify_error($msg);
	}

	/**
	 * Get successful delete notify.
	 * @return delete success notify
	 */
	static public function notify_delete_success()
	{
		$slug = static::admin_slug();
		$msg = i18n_r(LM_PLUGIN_ID . '/SUCCESS_DELETE');

		return self::notify_ok($msg);
	}

	/**
	 * Get delete error notify.
	 * @return delete error notify
	 */
	static public function notify_delete_error()
	{
		$slug = static::admin_slug();
		$msg = i18n_r(LM_PLUGIN_ID . '/ERROR_DELETE');

		return self::notify_error($msg);
	}

	/**
	 * Get ok notify.
	 * @param $msg message
	 * @param $can_undo whether to include undo link
	 * @return ok notify
	 */
	static public function notify_ok($msg, $can_undo = true)
	{
		return self::notify($msg, 'ok', $can_undo);
	}

	/**
	 * Get warning notify.
	 * @param $msg message
	 * @param $can_undo whether to include undo link
	 * @return warning notify
	 */
	static public function notify_warning($msg, $can_undo = false)
	{
		return self::notify($msg, 'warning', $can_undo);
	}

	/**
	 * Get info notify.
	 * @param $msg message
	 * @param $can_undo whether to include undo link
	 * @return info notify
	 */
	static public function notify_info($msg, $can_undo = false)
	{
		return self::notify($msg, 'info', $can_undo);
	}

	/**
	 * Get error notify.
	 * @param $msg message
	 * @param $can_undo whether to include undo link
	 * @return error notify
	 */
	static public function notify_error($msg, $can_undo = false)
	{
		return self::notify($msg, 'error', $can_undo);
	}

	/**
	 * Get notify.
	 * @param $msg message
	 * @param $type notify type
	 * @param $can_undo whether to include undo link
	 * @return notify
	 */
	static private function notify($msg, $type, $can_undo = false)
	{
		$msg .= $can_undo ? ' <a href="' . self::undo_url() . 
		   '">' . i18n_r('UNDO') . '</a>' : '';

		return '<script type="text/javascript">notify(' . 
		   json_encode($msg) . ', \'' . $type . '\');</script>';
	}

}

/**
 * LinksManager interface.
 *
 * @author Adrian D. Elgar
 */
interface ILinksManager
{
	/**
	 * Get object from xml item.
	 * @param $item xml element
	 * @return LinksManager_Object
	 */
	function get_xml_obj(SimpleXMLElement $item);

	/**
	 * Get xml item from object.
	 * @param $obj LinksManager_Object
	 * @param $item xml element
	 */
	function get_xml_item(LinksManager_Object $obj,
	   SimpleXMLElement &$item);

	/**
	 * Get url slug of admin section.
	 * @return admin slug
	 */
	static function admin_slug();

	/**
	 * Get admin panel template.
	 * @return admin panel template
	 */
	static function admin_panel_template();

	/**
	 * Get edit panel template.
	 * @return edit panel template
	 */
	static function edit_panel_template();

	/**
	 * Get id from edit form submit.
	 * return id.
	 */
	static function get_edit_submit_id();

	/**
	 * Get object from edit form submit.
	 * return LinksManager_Object.
	 */
	function get_edit_submit();

	/**
	 * Get data xml file.
	 * @return data xml file
	 */
	static function data_xml();

	/**
	 * Get backup data xml file.
	 * @return backup data xml file
	 */
	static function data_backup_xml();
}

/**
 * Define a LinksManager_Object.
 *
 * @author Adrian D. Elgar
 */
class LinksManager_Object
{
	protected $name;

	/**
	 * Construct a LinksManager_Object.
	 * @param $name name
	 */
	function __construct($name)
	{
		$this->name = $name;
	}

	/**
	 * @overload
	 * Get property.
	 * @param $prop property name
	 * @return property
	 */
	public function __get($prop)
	{
		return $this->$prop;
	}

	/**
	 * @overload
	 * Check if property is set.
	 * @param $prop property name
	 * @return true if property is set
	 */
	public function __isset($prop)
	{
		return isset($this->$prop);
	}

	/**
	 * @overload
	 * Get as string.
	 * @return name
	 */
	public function __toString()
	{
		return $this->name;
	}
}

?>
