<?php
/**
 * Links Manager template for single link html.
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

$category = $link->category();
$page = $link->page();
?>
<?php if (isset($page) || $link->url) { ?>
<li><a class="lm-link" <?php if (!$link->is_show_desc()) { ?>title="<?php echo $link->desc; ?>"<?php } ?> target="<?php echo $link->target; ?>" href="<?php echo isset($page) ? $page['url'] : $link->url; ?>"><?php if ($link->is_display_icon()) { ?><img alt="<?php echo $link->name; ?>" src="<?php echo $link->icon; ?>" /><?php } ?><?php if ($link->is_display_text()) { ?><?php echo $link; ?><?php } ?></a><?php if ($link->is_show_desc()) { ?><span class="lm-link-desc"><?php echo nl2br($link->desc); ?></span><?php } ?></li>
<?php } else { ?>
<li><span class="lm-link" <?php if (!$link->is_show_desc()) { ?>title="<?php echo $link->desc; ?>"<?php } ?>><?php if ($link->is_display_icon()) { ?><img alt="<?php echo $link->name; ?>" src="<?php echo $link->icon; ?>" /><?php } ?><?php if ($link->is_display_text()) { ?><?php echo $link; ?><?php } ?></span><?php if ($link->is_show_desc()) { ?><span class="lm-link-desc"><?php echo nl2br($link->desc); ?></span><?php } ?></li>
<?php } ?>
