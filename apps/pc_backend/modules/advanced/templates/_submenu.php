<?php $categoryAttributes = sfConfig::get('sfadvanced_site_category_attribute'); ?>
<?php foreach (sfConfig::get('sfadvanced_site_category') as $category => $configs) :?>
<?php
if (!empty($categoryAttributes[$category]['Hidden']) || empty($categoryAttributes[$category]['Advanced']))
{
  continue;
}
$caption = !empty($categoryAttributes[$category]['Caption']) ? $categoryAttributes[$category]['Caption'] : $category;
?>
<li><?php echo link_to(__($caption), 'advanced/config?category='.$category) ?></li>
<?php endforeach; ?>
<li><?php echo link_to(__('RichTextarea Configuration'), 'advanced/richTextarea') ?></li>
