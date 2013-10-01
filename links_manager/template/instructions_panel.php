<?php
/**
 * Links Manager template for instructions admin panel.
 *
 * (c)2013 mrdragonraaar.com
 */
if (!defined('IN_GS')) { die('you cannot load this page directly.'); }

?>
<!-- Title -->
<h3><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS'); ?></h3>
<!-- END Title -->

<p><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_DESC'); ?></p>

<!-- Instructions -->
<div class="instructions">

<!-- Links: All -->
<div class="isection">
<h4 class="title"><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINKS_ALL'); ?></h4>
<p><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINKS_ALL_DESC'); ?></p>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PHP_CODE'); ?></h4>
<code>
&lt;?php $links_html = return_links(); echo $links_html; ?&gt;
<br/>
&lt;?php get_links(); ?&gt;
</code>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PAGE_PLACEHOLDER'); ?></h4>
<code>
(% links %)
</code>
</div>
<!-- END Links: All -->

<!-- Links: Category -->
<div class="isection">
<h4 class="title"><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINKS_CATEGORY'); ?></h4>
<p><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINKS_CATEGORY_DESC'); ?></p>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PHP_CODE'); ?></h4>
<code>
&lt;?php $links_html = return_links(3); echo $links_html; ?&gt;
<br/>
&lt;?php get_links(3); ?&gt;
</code>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PAGE_PLACEHOLDER'); ?></h4>
<code>
(% links:3 %)
</code>
</div>
<!-- END Links: Category -->

<!-- Category Name -->
<div class="isection">
<h4 class="title"><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_CATEGORY_NAME'); ?></h4>
<p><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_CATEGORY_NAME_DESC'); ?></p>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PHP_CODE'); ?></h4>
<code>
&lt;?php $category_name = return_category_name(3); echo $category_name; ?&gt;
<br/>
&lt;?php get_category_name(3); ?&gt;
</code>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PAGE_PLACEHOLDER'); ?></h4>
<code>
(% link_category:3 %)
</code>
</div>
<!-- END Category Name -->

<!-- Link: Id -->
<div class="isection">
<h4 class="title"><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINK_ID'); ?></h4>
<p><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINK_ID_DESC'); ?></p>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PHP_CODE'); ?></h4>
<code>
&lt;?php $link_html = return_link(7); echo $link_html; ?&gt;
<br/>
&lt;?php get_link(7); ?&gt;
</code>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PAGE_PLACEHOLDER'); ?></h4>
<code>
(% link:7 %)
</code>
</div>
<!-- END Link: Id -->

<!-- Link: Name -->
<div class="isection">
<h4 class="title"><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINK_NAME'); ?></h4>
<p><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINK_NAME_DESC'); ?></p>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PHP_CODE'); ?></h4>
<code>
&lt;?php $link_html = return_link('Fred'); echo $link_html; ?&gt;
<br/>
&lt;?php get_link('Fred'); ?&gt;
</code>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PAGE_PLACEHOLDER'); ?></h4>
<code>
(% link:Fred %)
</code>
</div>
<!-- END Link: Name -->

<!-- Link: Random -->
<div class="isection">
<h4 class="title"><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINK_RANDOM'); ?></h4>
<p><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINK_RANDOM_DESC'); ?></p>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PHP_CODE'); ?></h4>
<code>
&lt;?php $link_html = return_randlink(2, 9); echo $link_html; ?&gt;
<br/>
&lt;?php get_randlink(2, 9); ?&gt;
</code>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PAGE_PLACEHOLDER'); ?></h4>
<code>
(% randlink:2:9 %)
</code>
</div>
<!-- END Link: Random -->

<!-- Links: Search -->
<div class="isection">
<h4 class="title"><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINKS_SEARCH'); ?></h4>
<p><?php i18n(LM_PLUGIN_ID . '/INSTRUCTIONS_LINKS_SEARCH_DESC'); ?></p>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PHP_CODE'); ?></h4>
<code>
&lt;?php $links_html = return_search_links('fish'); echo $links_html; ?&gt;
<br/>
&lt;?php search_links('fish'); ?&gt;
</code>
<h4 class="subtitle"><?php i18n(LM_PLUGIN_ID . '/PAGE_PLACEHOLDER'); ?></h4>
<code>
(% search_links:fish %)
</code>
</div>
<!-- END Links: Search -->

</div>
<!-- END Instructions -->
