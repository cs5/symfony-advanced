<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * MemberConfigPublicFlagForm
 *
 * @package    SfAdvanced
 * @subpackage form
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class MemberConfigPublicFlagForm extends MemberConfigForm
{
  protected $category = 'publicFlag';

  public function __construct(Member $member = null, $options = array(), $CSRFSecret = null)
  {
    parent::__construct($member, $options, $CSRFSecret);

    if (saConfig::get('is_allow_config_public_flag_profile_page'))
    {
      unset($this['profile_page_public_flag']);
    }

    if (!saConfig::get('is_allow_web_public_flag_age'))
    {
      $widget = $this->widgetSchema['age_public_flag'];

      $choices = $widget->getOption('choices');
      unset($choices[4]);
      $widget->setOption('choices', $choices);

      $this->validatorSchema['age_public_flag']->setOption('choices', array_keys($choices));
    }
  }
}
