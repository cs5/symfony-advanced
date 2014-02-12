<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<?php include_http_metas() ?>
<?php include_metas() ?>
<?php include_title() ?>
<?php use_stylesheet('/cache/css/customizing.css') ?>
<?php include_stylesheets() ?>
<?php if (Doctrine::getTable('SnsConfig')->get('customizing_css')): ?>
<link rel="stylesheet" type="text/css" href="<?php echo url_for('@customizing_css') ?>" />
<?php endif; ?>
<?php use_helper('Javascript') ?>
<?php use_javascript('jquery.min.js') ?>
<?php use_javascript('jquery.tmpl.min.js') ?>
<?php use_javascript('jquery.notify.js') ?>
<?php if (opConfig::get('enable_jsonapi') && opToolkit::isSecurePage()): ?>
<?php
use_javascript('sa_notify.js');
$jsonData = array(
  'apiKey' => $sf_user->getMemberApiKey(),
  'apiBase' => app_url_for('api', 'homepage'),
  'baseUrl' => $sf_request->getRelativeUrlRoot().'/',
);

echo javascript_tag('
var sfadvanced = '.json_encode($jsonData).';
');
?>
<?php endif ?>
<?php include_javascripts() ?>
<?php echo $sa_config->get('pc_html_head') ?>
</head>
<body id="<?php printf('page_%s_%s', $view->getModuleName(), $view->getActionName()) ?>" class="<?php echo opToolkit::isSecurePage() ? 'secure_page' : 'insecure_page' ?>">
<?php echo $sa_config->get('pc_html_top2') ?>
<div id="Body">
<?php echo $sa_config->get('pc_html_top') ?>
<div id="Container">

<div id="Header">
<div id="HeaderContainer">
<?php include_partial('global/header') ?>
</div><!-- HeaderContainer -->
</div><!-- Header -->

<div id="Contents">
<div id="ContentsContainer">

<div id="localNav">
<?php
$context = sfContext::getInstance();
$module = $context->getActionStack()->getLastEntry()->getModuleName();
$localNavOptions = array(
  'is_secure' => opToolkit::isSecurePage(),
  'type'      => sfConfig::get('sf_nav_type', sfConfig::get('mod_'.$module.'_default_nav', 'default')),
  'culture'   => $context->getUser()->getCulture(),
);
if ('default' !== $localNavOptions['type'])
{
  $localNavOptions['nav_id'] = sfConfig::get('sf_nav_id', $context->getRequest()->getParameter('id'));
}
include_component('default', 'localNav', $localNavOptions);
?>
</div><!-- localNav -->

<div id="Layout<?php echo $layout ?>" class="Layout">

<?php if ($sf_user->hasFlash('error')): ?>
<?php sa_include_parts('alertBox', 'flashError', array('body' => __($sf_user->getFlash('error'), $sf_data->getRaw('sf_user')->getFlash('error_params', array())))) ?>
<?php endif; ?>
<?php if ($sf_user->hasFlash('notice')): ?>
<?php sa_include_parts('alertBox', 'flashNotice', array('body' => __($sf_user->getFlash('notice'), $sf_data->getRaw('sf_user')->getFlash('notice_params', array())))) ?>
<?php endif; ?>

<?php if (has_slot('sa_top')): ?>
<div id="Top">
<?php include_slot('sa_top') ?>
</div><!-- Top -->
<?php endif; ?>

<?php if (has_slot('sa_sidemenu')): ?>
<div id="Left">
<?php include_slot('sa_sidemenu') ?>
</div><!-- Left -->
<?php endif; ?>

<div id="Center">
<?php echo $sf_content ?>
</div><!-- Center -->

<?php if (has_slot('sa_bottom')): ?>
<div id="Bottom">
<?php include_slot('sa_bottom') ?>
</div><!-- Bottom -->
<?php endif; ?>

</div><!-- Layout -->

<div id="sideBanner">
<?php include_component('default', 'sideBannerGadgets'); ?>
</div><!-- sideBanner -->

</div><!-- ContentsContainer -->
</div><!-- Contents -->

<?php if ($sf_request->isSmartphone(false)): ?>
<div id="SmtSwitch">
<a href="javascript:void(0)" id="SmtSwitchLink"><?php echo __('View this page on smartphone style') ?></a>
<?php echo javascript_tag('
document.getElementById("SmtSwitchLink").addEventListener("click", function() {
  opCookie.set("disable_smt", "0", undefined, sfadvanced.baseUrl);
  location.reload();
}, false);
') ?>
</div>
<?php endif ?>

<div id="Footer">
<div id="FooterContainer">
<?php include_partial('global/footer') ?>
</div><!-- FooterContainer -->
</div><!-- Footer -->

<?php echo $sa_config->get('pc_html_bottom2') ?>
</div><!-- Container -->
<?php echo $sa_config->get('pc_html_bottom') ?>
</div><!-- Body -->
</body>
</html>
