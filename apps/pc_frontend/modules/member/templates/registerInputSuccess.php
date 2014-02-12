<?php
$options = array(
  'title' => __('Member Registration'),
  'url'   => url_for('member/registerInput?token='.$token),
  'button' => __('Register'),
);
sa_include_form('RegisterForm', $form, $options);
?>
