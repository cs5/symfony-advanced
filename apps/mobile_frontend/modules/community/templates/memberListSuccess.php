<?php sa_mobile_page_title($community->getName(), __('%Community% Members')) ?>

<center>
<?php sa_include_pager_total($pager); ?>
</center>

<?php
$list = array();
foreach ($pager->getResults() as $member) {
  $list[] = sa_link_to_member($member, array('link_target' => sprintf('%s(%d)', $member->getName(), $member->countFriends())));
}
$option = array(
  'border' => true,
);
sa_include_list('memberList', $list, $option);
?>

<?php sa_include_pager_navigation($pager, '@community_memberList?page=%d&id='.$id, array('is_total' => false)); ?>

<hr color="<?php echo $sa_color['core_color_11'] ?>">

<?php echo link_to(__('%Community% Top'), '@community_home?id='.$community->getId()) ?>
