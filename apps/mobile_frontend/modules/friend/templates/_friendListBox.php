<?php

$list = array();
foreach ($friends as $friendMember)
{
  $list[] = sa_link_to_member($friendMember, array('link_target' => sprintf('%s(%d)', $friendMember->getName(), $friendMember->countFriends())));
}
$option = array(
  'title' => __('%Friend% list'),
  'border' => true,
  'moreInfo' => array(
    link_to(__('More'), '@friend_list?id='.$member->getId())
  ),
);
sa_include_list('friendList', $list, $option);
