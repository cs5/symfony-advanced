<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saPermitMemberConfigSiteConfig form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saPermitMemberConfigSiteConfigForm extends BaseForm
{
  public function configure()
  {
    $choices = array();

    $categories = sfConfig::get('sfadvanced_member_category');
    $categoryAttributes = sfConfig::get('sfadvanced_member_category_attribute');

    foreach ($categories as $key => $value)
    {
      $caption = $key;
      if (!empty($categoryAttributes[$key]['caption']))
      {
        $caption = $categoryAttributes[$key]['caption'];
      }

      $choices[$key] = $caption;
    }

    $this->setWidgets(array(
      'ignored_site_config' => new sfWidgetFormSelectMany(array('choices' => $choices)),
    ));

    $this->setValidators(array(
      'ignored_site_config' => new sfValidatorChoice(array('multiple' => true, 'choices' => array_keys($choices), 'required' => false)),
    ));

    $default = Doctrine::getTable('SiteConfig')->get('ignored_site_config', array());
    if ($default)
    {
      $default = unserialize($default);
    }
    $this->setDefault('ignored_site_config', $default);

    $this->widgetSchema->setNameFormat('site_config[%s]');
  }

  public function save()
  {
    $ignored = (array)$this->getValue('ignored_site_config');
    Doctrine::getTable('SiteConfig')->set('ignored_site_config', serialize($ignored));
  }
}
