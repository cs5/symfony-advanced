<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Pick Home Layout Form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class PickHomeLayoutForm extends sfForm
{
  public $choices = array();
  protected $layoutName;

  public function configure()
  {
    $gadgetConfigs = Doctrine::getTable('Gadget')->getConfig();

    $layoutName = $this->getOption('layout_name', 'gadget');
    $this->choices = $gadgetConfigs[$layoutName]['layout']['choices'];
    $default = array_search($gadgetConfigs[$layoutName]['layout']['default'], $this->choices);

    if ($layoutName === 'gadget')
    {
      $layoutName = 'home';
    }
    $this->layoutName = $layoutName.'_layout';

    $siteConfig = Doctrine::getTable('SiteConfig')->retrieveByName($this->layoutName);

    if ($siteConfig)
    {
      $default = array_search($siteConfig->getValue(), $this->choices);
    }

    $this->setWidget('layout', new sfWidgetFormSelectPhotoRadio(array(
      'default'      => (int)$default,
      'class'        => 'layoutSelection',
      'choices'      => $this->choices,
      'image_prefix' => 'layout_selection_',
    )));

    $this->setValidator('layout', new sfValidatorChoice(array(
      'choices' => array_keys($this->choices),
    )));
    $this->widgetSchema->setNameFormat('pick_home_layout[%s]');
  }

  public function save()
  {
    if (!$this->isValid())
    {
      return false;
    }

    $siteConfig = Doctrine::getTable('SiteConfig')->retrieveByName($this->layoutName);
    if (!$siteConfig)
    {
      $siteConfig = new SiteConfig();
      $siteConfig->setName($this->layoutName);
    }
    $value = $this->choices[$this->values['layout']];
    $siteConfig->setValue($value);

    return (bool)$siteConfig->save();
  }
}
