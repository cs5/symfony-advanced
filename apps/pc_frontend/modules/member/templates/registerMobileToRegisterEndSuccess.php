<?php ob_start() ?>
<p><?php echo __('It is necessary to register the mobile-phone information to entry in %1%.', array('%1%' => $sa_config['site_name'])) ?><br />
<strong><?php echo __('Please note that you cannot use smartphones.') ?></strong></p>
<p><?php echo __('URL for mobile registration is send to the mobile mail address appropriating input here.') ?></p>
<ul>
<li><?php echo __('Other member can not view the mail address that was input here.') ?></li>
<li><?php echo __('Please set your mobile phone to receive a mail from %1%.', array('%1%' => $sa_config['admin_mail_address'])) ?></li>
</ul>
<?php $partsInfo = ob_get_clean() ?>
<?php
$options = array(
  'title' => __('Register Mobile phone'),
  'partsInfo' => $partsInfo,
);
sa_include_form('registerMobile', $form, $options);
?>
