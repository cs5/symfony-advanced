<?php
$options = array(
  'title' => __('%community% List', array('%community%' => $sa_term['community']->titleize())),
  'list' => $pager->getResults(),
  'crownIds' => $sf_data->getRaw('crownIds'),
  'link_to' => '@community_home?id=',
  'pager' => $pager,
  'link_to_pager' => '@community_joinlist?page=%d&id='.$member->getId(),
);
sa_include_parts('photoTable', 'communityList', $options)
?>
