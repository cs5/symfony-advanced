<?php
$options = array(
  'title' => __('Invite a friend to %1%', array('%1%' => $sa_config['sns_name'])),
);
sa_include_box('inviteForm', __('Sent.'), $options);
?>
