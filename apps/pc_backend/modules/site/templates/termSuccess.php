<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('Term Configuration in this Site') ?></h2>

<?php echo $form->renderFormTag(url_for('site/term')) ?>
<table>
<?php echo $form ?>
<tr><td colspan="2">
<input type="submit" value="<?php echo __('Edit') ?>" />
</td></tr>
</table>
</form>
