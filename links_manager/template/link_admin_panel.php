<?php
/**
 * Links Manager template for links admin panel.
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

queue_script(LM_PLUGIN_ID, GSBACK);

?>
<!-- Title -->
<h3 class="floated"><?php i18n(LM_PLUGIN_ID . '/MANAGE_LINKS'); ?></h3>
<!-- END Title -->

<!-- Add New Link -->
<div class="edit-nav clearfix">
<a href="<?php echo Links::edit_url(); ?>"><?php i18n(LM_PLUGIN_ID . '/NEW_LINK'); ?></a>
</div>
<!-- END Add New Link -->

<?php if ($this->is_empty()) { ?>
<!-- No Links -->
<p><?php i18n(LM_PLUGIN_ID . '/NO_LINKS'); ?></p>
<!-- END No Links -->
<?php } else { ?>
<?php if (count($this()) > 1) { ?>
<!-- Change Order -->
<p><?php i18n(LM_PLUGIN_ID . '/CHANGE_ORDER'); ?></p>
<!-- END Change Order -->
<?php } ?>
<!-- Links -->
<form method="post">
<table id="links" class="highlight">
<tr>
	<th><?php i18n(LM_PLUGIN_ID . '/NAME'); ?></th>
	<th><?php i18n(LM_PLUGIN_ID . '/URL'); ?> / <?php i18n(LM_PLUGIN_ID . '/INTERNAL_PAGE'); ?></th>
	<th></th>
	<th><?php i18n(LM_PLUGIN_ID . '/TARGET'); ?></th>
	<th><?php i18n(LM_PLUGIN_ID . '/DESCRIPTION'); ?></th>
	<th><?php i18n(LM_PLUGIN_ID . '/CATEGORY'); ?></th>
	<th><?php i18n(LM_PLUGIN_ID . '/ICON'); ?></th>
	<th></th>
</tr>
<tbody>
<?php foreach ($this() as $id => $link) { ?>
<!-- <?php echo $link->name; ?> -->
<tr rel="<?php echo $link->slug; ?>">
	<!-- Name -->
	<td>
	<a href="<?php echo Links::edit_url($id); ?>" title="<?php i18n(LM_PLUGIN_ID . '/EDIT_LINK'); ?>: <?php echo $link->name; ?>">
	<?php echo $link->name; ?>
	</a>
	</td>
	<!-- END Name -->
	<!-- URL / Internal Page -->
	<?php
		$link_page = $link->page();
		$link_url_desc = isset($link_page) ? $link_page['title'] : 
		   $link->url;
		$link_url = isset($link_page) ? $link_page['url'] : 
		   $link->url;
		$link_url_title = isset($link_page) ? $link_page['title'] : 
		   $link->name;
		$link_url_i18n = isset($link_page) ? '/VIEW_PAGE' :
		   '/VIEW_LINK';
	?>
	<td>
	<span><?php echo strlen($link_url_desc) > 20 ? substr($link_url_desc, 0, 20) . '...' : $link_url_desc; ?></span>
	</td>
	<td class="secondarylink">
	<?php if ($link_url) { ?>
	<a href="<?php echo $link_url; ?>" target="_blank" title="<?php i18n(LM_PLUGIN_ID . $link_url_i18n); ?>: <?php echo $link_url_title; ?>">#</a>
	<?php } else { ?>
	<span title="<?php i18n(LM_PLUGIN_ID . '/NO_LINK'); ?>">#</span>
	<?php } ?>
	</td>
	<!-- END URL / Internal Page -->
	<!-- Target -->
	<td>
	<span><?php echo $link->target ? $link->target : 'none'; ?></span>
	</td>
	<!-- END Target -->
	<!-- Description -->
	<td title="<?php echo $link->desc; ?>">
	<span><?php echo strlen($link->desc) > 15 ? substr($link->desc, 0, 15) . '...' : $link->desc; ?></span>
	</td>
	<!-- END Description -->
	<!-- Category -->
	<?php $category = $link->category(); ?>
	<td>
	<span><?php echo isset($category) ? ('(' . $link->category_id . ') ' . $category->name) : '-'; ?></span>
	</td>
	<!-- END Category -->
	<!-- Icon -->
	<td>
	<?php if (isset($link->icon)) { ?>
	<img height="16" width="16" src="<?php echo $link->icon; ?>" />
	<?php } ?>
	</td>
	<!-- END Icon -->
	<!-- Delete -->
	<td class="delete">
	<a href="<?php echo Links::delete_url($id); ?>" class="delconfirm noajax" title="<?php i18n(LM_PLUGIN_ID . '/DELETE_LINK'); ?>: <?php echo $link->name; ?>?">
	X
	</a>
	</td>
	<!-- END Delete -->
</tr>
<!-- END <?php echo $link->name; ?> -->
<?php } ?>
</tbody>
</table>
<?php if (count($this()) > 1) { ?>
<!-- Link Order Submit -->
<input type="hidden" name="link-order" value="">
<input type="submit" class="submit" name="order" value="<?php i18n(LM_PLUGIN_ID . '/SAVE_ORDER'); ?>">
<!-- END Link Order Submit -->
<?php } ?>
</form>
<!-- END Links -->
<?php } ?>
