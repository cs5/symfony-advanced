<?php sa_mobile_page_title(__('Delete this %community%'), $community->getName()) ?>
<?php
$form = new BaseForm();
sa_include_parts('yesNo', 'deleteConfirmForm', array(
  'body' => __('Do you delete this %community%?'),
  'yes_form' => '<input type="hidden" name="is_delete">'
              . '<input type="hidden" name="'.$form->getCSRFFieldName().'" value="'.$form->getCSRFToken().'">',
  'button' => __('Delete'),
))
?>
