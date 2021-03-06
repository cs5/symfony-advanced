<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * BannerImage form.
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Masato Nagasawa <nagasawa@tejimaya.com>
 */
class BannerImageForm extends BaseBannerImageForm
{
  public function setup()
  {
    unset($this['id'], $this['file_id'], $this['name'], $this['url']);
    $this->setWidget('file', new sfWidgetFormInputFile());
    $this->setWidget('url', new sfWidgetFormInputText(array(), array('size' => 40)));
    $this->setWidget('name', new sfWidgetFormInputText());
    $this->setValidators(array(
      'file' => new saValidatorImageFile(array('required' => $this->isNew())),
      'url' => new sfValidatorPass(),
      'name' => new sfValidatorPass(),
    ));
    $this->widgetSchema->setLabels(array(
      'file' => 'Image',
      'url' => 'Link place',
      'name' => 'Banner name',
    ));
    $this->widgetSchema->setNameFormat('banner_image[%s]');
  }

  public function save()
  {
    if ($this->isNew())
    {
      $bannerImage = new BannerImage();
    }
    else
    {
      $bannerImage = $this->getObject();
    }

    if ($this->getValue('file'))
    {
      $file = new File();
      $file->setFromValidatedFile($this->getValue('file'));
      $file->setName('b_'.$file->getName());
      $bannerImage->setFile($file);
    }

    $bannerImage->setUrl($this->getValue('url'));
    $bannerImage->setName($this->getValue('name'));

    return $bannerImage->save();
  }
}
