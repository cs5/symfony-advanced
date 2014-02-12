<?php if ($sf_user->isSiteMember()): ?>
<?php echo sa_banner('side_after') ?>
<?php else: ?>
<?php echo sa_banner('side_before') ?>
<?php endif ?>
