<?php slot('_register_success_box'); ?>
<p><?php echo __('Sent you an invitation for %1%.', array('%1%' => $sa_config['site_name'])) ?></p>
<p><?php echo __('Begin your registration from a URL in the mail.') ?></p>
<?php end_slot(); ?>
<?php sa_include_box('requestSuccess', get_slot('_register_success_box'), array('title' => __('Register'))) ?>
