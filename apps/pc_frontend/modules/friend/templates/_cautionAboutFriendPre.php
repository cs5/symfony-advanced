<?php foreach ($sf_user->getMember()->getFriendPreTo() as $key => $value) : ?>
<p class="caution">
<?php
$member = $value->getMemberRelatedByMemberIdFrom();
echo link_to(sprintf(__('%1% sent my friends request to you!', array('%1%' => $member->getName())), 'member/profile?id='.$member->getId())) ?>
&nbsp;
<?php echo link_to(__('Permits'), 'friend/linkAccept?id='.$member->getId()) ?>
&nbsp;
<?php echo link_to(__('Refuses'), 'friend/linkReject?id='.$member->getId()) ?>
</p>
<?php endforeach; ?>
