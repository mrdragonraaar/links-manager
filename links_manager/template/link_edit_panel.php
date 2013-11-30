<?php
/**
 * Links Manager template for links edit panel.
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

queue_script(LM_PLUGIN_ID, GSBACK);

$link = isset($id) ? $this($id) : null;
?>
<!-- Title -->
<?php if (isset($link)) { ?>
<h3><?php i18n(LM_PLUGIN_ID . '/EDIT_LINK'); ?></h3>
<?php } else { ?>
<h3><?php i18n(LM_PLUGIN_ID . '/NEW_LINK'); ?></h3>
<?php } ?>
<!-- END Title -->

<!-- Edit Link Form -->
<form class="largeform" id="edit" action="<?php echo Links::admin_url(); ?>" method="post" accept-charset="utf-8">
<!-- ID -->
<?php if (isset($link)) { ?>
<p><input name="link-id" type="hidden" value="<?php echo $id; ?>" /></p>
<p><input name="link-slug" type="hidden" value="<?php echo $link->slug; ?>" /></p>
<?php } ?>
<!-- END ID -->
<!-- Name -->
<p>
<label for="link-name"><?php i18n(LM_PLUGIN_ID . '/NAME'); ?>:</label>
<input class="text required" name="link-name" id="link-name" type="text" value="<?php if (isset($link)) { echo $link->name; } ?>" />
</p>
<!-- END Name -->
<!-- URL -->
<p>
<label for="link-url"><?php i18n(LM_PLUGIN_ID . '/URL'); ?>:</label>
<input class="text url !required" name="link-url" id="link-url" type="text" value="<?php echo isset($link) ? $link->url : ''; ?>" />
</p>
<!-- END URL -->
<!-- Internal Page -->
<p>
<label for="link-page-slug"><?php i18n(LM_PLUGIN_ID . '/INTERNAL_PAGE'); ?>:</label>
<select class="text" name="link-page-slug" id="link-page-slug">
<option value="">-</option>
<?php foreach (get_available_pages() as $page) { ?>
<option value="<?php echo $page['slug']; ?>" <?php if (isset($link) && ($page['slug'] === $link->page_slug)) { echo 'selected'; }?>><?php echo $page['title']; ?></option>
<?php } ?>
</select>
</p>
<!-- END Internal Page -->
<!-- Target -->
<p>
<label for="link-target"><?php i18n(LM_PLUGIN_ID . '/TARGET'); ?>:</label>
<select class="text" name="link-target" id="link-target">
<option value="">none</option>
<option value="_blank" <?php if (isset($link) && ($link->target === '_blank')) { echo 'selected'; } ?>>_blank</option>
<option value="_top" <?php if (isset($link) && ($link->target === '_top')) { echo 'selected'; } ?>>_top</option>
</select>
</p>
<!-- END Target -->
<!-- Description -->
<p>
<label for="link-desc"><?php i18n(LM_PLUGIN_ID . '/DESCRIPTION'); ?>:</label>
<textarea class="text" name="link-desc" id="link-desc">
<?php if (isset($link)) { echo $link->desc; } ?>
</textarea>
</p>
<!-- END Description -->
<!-- Category -->
<p>
<label for="link-category-id"><?php i18n(LM_PLUGIN_ID . '/CATEGORY'); ?>:</label>
<select class="text" name="link-category-id" id="link-category-id">
<option value="-1">-</option>
<?php $categories = $this->categories(); ?>
<?php foreach ($categories() as $id => $category) { ?>
<option value="<?php echo $id; ?>" <?php if (isset($link) && ($id == $link->category_id)) { echo 'selected'; } ?>><?php echo $category->name; ?></option>
<?php } ?>
</select>
</p>
<!-- END Category -->
<!-- Icon URL -->
<p class="clearfix">
<label for="link-icon"><?php i18n(LM_PLUGIN_ID . '/ICON_URL'); ?>:</label>
<span class="edit-nav">
<a id="link-browse-image" href="#"><?php i18n(LM_PLUGIN_ID . '/BROWSE_IMAGES'); ?></a>
</span>
<input class="text url" name="link-icon" id="link-icon" type="text" value="<?php if (isset($link)) { echo $link->icon; } ?>" />
</p>
<!-- END Icon URL -->
<p>
<!-- Submit -->
<input class="submit" type="submit" name="submit" value="<?php i18n(LM_PLUGIN_ID . '/SAVE_LINK'); ?>" />
<!-- END Submit -->
&nbsp;&nbsp;
<?php i18n(LM_PLUGIN_ID . '/OR'); ?>
&nbsp;&nbsp;
<!-- Cancel -->
<a href="<?php echo Links::cancel_url(); ?>" class="cancel"><?php i18n(LM_PLUGIN_ID . '/CANCEL'); ?></a>
<!-- END Cancel -->
<!-- Delete -->
<?php if (isset($link)) { ?>
/
<a href="<?php echo Links::delete_url($id); ?>" class="delconfirm noajax" title="<?php i18n(LM_PLUGIN_ID . '/DELETE_LINK'); ?>: <?php echo $link->name; ?>?">
<?php i18n(LM_PLUGIN_ID . '/DELETE'); ?>
</a>
<?php } ?>
<!-- END Delete -->
</p>
</form>
<!-- END Edit Link Form -->
