<?php
$options = array(
  'title' => __('%friend% List', array('%friend%' => $sa_term['friend']->titleize())),
  'list' => $friends,
  'link_to' => '@obj_member_profile?id=',
  'use_sa_link_to_member' => true,
  'moreInfo' => array(link_to(sprintf('%s(%d)', __('Show all'), $member->countFriends()), 'friend/list?id='.$member->getId())),
  'type' => $sf_data->getRaw('gadget')->getConfig('type'),
  'row' => $row,
  'col' => $col,
);

if ($member->getId() == $sf_user->getMember()->getId())
{
  $options['moreInfo'][] = link_to(__('%my_friend% Setting', array(
    '%my_friend%' => $sa_term['my_friend']->titleize()->pluralize(),
  )), '@friend_manage');
}

sa_include_parts('nineTable', 'frendList_'.$gadget->getId(), $options);
