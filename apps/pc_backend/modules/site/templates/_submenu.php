<?php $categoryAttributes = sfConfig::get('sfadvanced_site_category_attribute'); ?>
<?php foreach (sfConfig::get('sfadvanced_site_category') as $category => $configs) :?>
<?php
if (!empty($categoryAttributes[$category]['Hidden']) || !empty($categoryAttributes[$category]['Advanced']))
{
  continue;
}
$caption = !empty($categoryAttributes[$category]['Caption']) ? $categoryAttributes[$category]['Caption'] : $category;
?>
<li><?php echo link_to(__($caption), 'site/config?category='.$category) ?></li>
<?php endforeach; ?>
<li><?php echo link_to(__('Term Configuration in this Site'), 'site/term') ?></li>
<li><?php echo link_to(__('Cache Clear'), 'site/cache') ?></li>
