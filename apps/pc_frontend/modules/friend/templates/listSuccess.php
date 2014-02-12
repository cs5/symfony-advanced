<?php
$options = array(
  'title' => __('%friend% List', array('%friend%' => $sa_term['friend']->titleize())),
  'list' => $pager->getResults(),
  'link_to' => '@member_profile?id=',
  'pager' => $pager,
  'link_to_pager' => '@friend_list?page=%d&id='.$id,
  'use_sa_link_to_member' => true,
);
sa_include_parts('photoTable', 'friendList', $options)
?>
