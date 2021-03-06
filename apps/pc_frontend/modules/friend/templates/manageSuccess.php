<?php echo sa_include_parts('manageList', 'manageList', array(
  'pager' => $pager,
  'pager_url'=> '@friend_manage?page=%d',
  'item_url' => 'obj_member_profile',
  'image_filename_method' => 'getImageFilename',
  'title' => __('%my_friend% Setting', array(
    '%my_friend%' => $sa_term['my_friend']->titleize()->pluralize(),
  )),
  'menus' => array(
    array('text' => __('Delete from %my_friend%.', array(
      '%my_friend%' => $sa_term['my_friend']->pluralize(),
    )), 'url' => 'obj_friend_unlink', 'class' => 'delete'),
  ),
  'use_sa_link_to_member' => true,
)); ?>
