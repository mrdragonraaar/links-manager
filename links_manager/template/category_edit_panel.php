<?php
/**
 * Links Manager template for categories edit panel.
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

queue_script(LM_PLUGIN_ID, GSBACK);

$category = isset($id) ? $this($id) : null;
?>
<!-- Title -->
<?php if (isset($category)) { ?>
<h3><?php i18n(LM_PLUGIN_ID . '/EDIT_CATEGORY'); ?></h3>
<?php } else { ?>
<h3><?php i18n(LM_PLUGIN_ID . '/NEW_CATEGORY'); ?></h3>
<?php } ?>
<!-- END Title -->

<!-- Edit Category Form -->
<form class="largeform" id="edit" action="<?php echo Categories::admin_url(); ?>" method="post" accept-charset="utf-8">
<!-- ID -->
<?php if (isset($category)) { ?>
<p><input name="category-id" type="hidden" value="<?php echo $id; ?>" /></p>
<?php } ?>
<!-- END ID -->
<!-- Name -->
<p>
<label for="category-name"><?php i18n(LM_PLUGIN_ID . '/CATEGORY'); ?>:</label>
<input class="text required" name="category-name" id="category-name" type="text" value="<?php if (isset($category)) { echo $category->name; } ?>" />
</p>
<!-- END Name -->
<!-- Display -->
<p>
<label for="category-display"><?php i18n(LM_PLUGIN_ID . '/DISPLAY'); ?>:</label>
<select class="text" name="category-display" id="category-display">
<option value=""><?php i18n(LM_PLUGIN_ID . '/ICON_AND_TEXT'); ?></option>
<option value="icon" <?php if (isset($category) && $category->is_display_icon()) { echo 'selected'; } ?>><?php i18n(LM_PLUGIN_ID . '/ICON_ONLY'); ?></option>
<option value="text" <?php if (isset($category) && $category->is_display_text()) { echo 'selected'; } ?>><?php i18n(LM_PLUGIN_ID . '/TEXT_ONLY'); ?></option>
</select>
</p>
<!-- END Display -->
<!-- Show Description -->
<p class="inline">
<input name="category-show-desc" id="category-show-desc" type="checkbox" value="1" <?php if (isset($category) && $category->is_show_desc()) { ?>checked<?php } ?> />&nbsp;<label for="category-show-desc"><b><?php i18n(LM_PLUGIN_ID . '/SHOW_DESC'); ?></b></label>
</p>
<!-- END Show Description -->
<p>
<!-- Submit -->
<input class="submit" type="submit" name="submit" value="<?php i18n(LM_PLUGIN_ID . '/SAVE_CATEGORY'); ?>" />
<!-- END Submit -->
&nbsp;&nbsp;
<?php i18n(LM_PLUGIN_ID . '/OR'); ?>
&nbsp;&nbsp;
<!-- Cancel -->
<a title="Cancel" href="<?php echo Categories::cancel_url(); ?>" class="cancel"><?php i18n(LM_PLUGIN_ID . '/CANCEL'); ?></a>
<!-- END Cancel -->
<!-- Delete -->
<?php if (isset($category)) { ?>
/
<a href="<?php echo Categories::delete_url($id); ?>" class="delconfirm noajax" title="<?php i18n(LM_PLUGIN_ID . '/DELETE_CATEGORY'); ?>: <?php echo $category->name; ?>?">
<?php i18n(LM_PLUGIN_ID . '/DELETE'); ?>
</a>
<?php } ?>
<!-- END Delete -->
</p>
</form>
<!-- END Edit Category Form -->
