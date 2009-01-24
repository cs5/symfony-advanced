<?php ob_start() ?>
<p><?php echo __('It is necessary to register the cell-phone Information to entry in %1%.', array('%1%' => $op_config['sns_name'])) ?></p>
<p><?php echo __('URL for mobile registration is send to the mobile mail address appropriating input here.') ?></p>
<ul>
<li><?php echo __('ここで入力したメールアドレスは他のメンバーには公開されません。') ?></li>
<li><?php echo __('Please set your mobile phone to receive a mail from %1%.', array('%1%' => $op_config['admin_mail_address'])) ?></li>
</ul>
<?php $partsInfo = ob_get_clean() ?>
<?php
$options = array(
  'title' => __('Register Mobile phone'),
  'partsInfo' => $partsInfo,
);
op_include_form('registerMobile', $form, $options);
?>
