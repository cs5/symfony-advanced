<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * Community filter form.
 *
 * @package    SfAdvanced
 * @subpackage filter
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class CommunityFormFilter extends BaseCommunityFormFilter
{
  public function __construct($defaults = array(), $options = array(), $CSRFSecret = null)
  {
    return parent::__construct($defaults, $options, false);
  }

  public function configure()
  {
    $choices = array();
    $choices[''] = sfContext::getInstance()->getI18N()->__('All categories', array(), 'form_community');

    $categories = Doctrine::getTable('CommunityCategory')->getAllChildren();
    foreach ($categories as $category)
    {
      $choices[$category->getId()] = $category->getName();
    }

    $widgets = array(
      'name'                  => new sfWidgetFormInput(),
      'community_category_id' => new sfWidgetFormChoice(array(
        'choices'     => $choices,
        'default'     => '')),
    );

    $validators = array(
      'name'                  => new saValidatorSearchQueryString(array('required' => false)),
      'community_category_id' => new sfValidatorPass(),
    );

    if ($this->getOption('use_id'))
    {
      $widgets = array('id' => new sfWidgetFormFilterInput(array('with_empty' => false, 'label' => 'ID'))) + $widgets;
      $validators = array('id' => new sfValidatorPass()) + $validators;
    }

    $this->setWidgets($widgets);
    $this->setValidators($validators);

    $this->widgetSchema->setLabel('name', '%community% Name');
    $this->widgetSchema->setLabel('community_category_id', '%community% Category');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    $this->widgetSchema->setNameFormat('community[%s]');
    $this->widgetSchema->getFormFormatter()->setTranslationCatalogue('form_community');
  }

  protected function addNameColumnQuery(Doctrine_Query $query, $field, $values)
  {
    $fieldName = $this->getFieldName($field);
    if (is_array($values))
    {
      foreach ($values as $value)
      {
        $query->andWhereLike('r.'.$fieldName, $value);
      }
    }
  }
}
