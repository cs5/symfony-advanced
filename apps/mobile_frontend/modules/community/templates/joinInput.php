<?php sa_mobile_page_title($community->getName(), __('Join to "%1%"', array('%1%' => $community->getName()))); ?>

<?php echo __('Do you really join to the following %community%?') ?><br>

<font color="<?php echo $sa_color['core_color_19'] ?>"><?php echo __('%community%', array('%community%' => $sa_term['community']->titleize())) ?>:</font><br>
<?php echo $community->getName() ?>
<br><br>
<?php sa_include_form('communityJoining', $form, array(
  'button' => __('Submit'),
  'align'  => 'center'
)) ?>
