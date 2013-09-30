<?php
/**
 * Links Manager template for categories admin panel.
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

queue_script(LM_PLUGIN_ID, GSBACK);

?>
<!-- Title -->
<h3 class="floated"><?php i18n(LM_PLUGIN_ID . '/MANAGE_CATEGORIES'); ?></h3>
<!-- END Title -->

<!-- Add New Category -->
<div class="edit-nav clearfix">
<a href="<?php echo Categories::edit_url(); ?>"><?php i18n(LM_PLUGIN_ID . '/NEW_CATEGORY'); ?></a>
</div>
<!-- END Add New Category -->

<?php if ($this->is_empty()) { ?>
<!-- No Categories -->
<p><?php i18n(LM_PLUGIN_ID . '/NO_CATEGORIES'); ?></p>
<!-- END No Categories -->
<?php } else { ?>
<!-- Categories -->
<table id="categories" class="highlight">
<tr>
	<th><?php i18n(LM_PLUGIN_ID . '/ID'); ?></th>
	<th><?php i18n(LM_PLUGIN_ID . '/CATEGORY'); ?></th>
	<th><?php i18n(LM_PLUGIN_ID . '/DISPLAY'); ?></th>
	<th><?php i18n(LM_PLUGIN_ID . '/SHOW_DESC'); ?></th>
	<th></th>
</tr>
<tbody>
<?php foreach ($this() as $id => $category) { ?>
<!-- <?php echo $category->name; ?> -->
<tr rel="<?php echo $category->name; ?>">
	<!-- ID -->
	<td>
	<span>#<?php echo $id; ?></span>
	</td>
	<!-- END ID -->
	<!-- Name -->
	<td>
	<a href="<?php echo Categories::edit_url($id); ?>" title="<?php i18n(LM_PLUGIN_ID . '/EDIT_CATEGORY'); ?>: <?php echo $category->name; ?>">
	<?php echo $category->name; ?>
	</a>
	</td>
	<!-- END Name -->
	<!-- Display Options -->
	<td>
	<?php
	if ($category->is_display_icon()) { i18n(LM_PLUGIN_ID . '/ICON_ONLY'); }
	else if ($category->is_display_text()) { i18n(LM_PLUGIN_ID . '/TEXT_ONLY'); }
	else { i18n(LM_PLUGIN_ID . '/ICON_AND_TEXT'); }
	?>
	</td>
	<!-- END Display Options -->
	<!-- Show Description -->
	<td>
	<?php
	if ($category->is_show_desc()) { i18n(LM_PLUGIN_ID . '/YES'); }
	else { i18n(LM_PLUGIN_ID . '/NO'); }
	?>
	</td>
	<!-- END Show Description -->
	<!-- Delete -->
	<td class="delete">
	<a href="<?php echo Categories::delete_url($id); ?>" class="delconfirm noajax" title="<?php i18n(LM_PLUGIN_ID . '/DELETE_CATEGORY'); ?>: <?php echo $category->name; ?>?">
	X
	</a>
	</td>
	<!-- END Delete -->
</tr>
<!-- END <?php echo $category->name; ?> -->
<?php } ?>
</tbody>
</table>
<!-- END Categories -->
<?php } ?>
