<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saOpenIDAxProfileExport
 *
 * @package    SfAdvanced
 * @subpackage util
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class saOpenIDAxProfileExport extends saProfileExport
{
  public $tableToSfAdvanced = array(
    'http://schema.openid.net/namePerson/friendly'     => 'name',
    'http://schema.openid.net/contact/email'           => 'email',
    'http://schema.openid.net/namePerson'              => 'name',
    'http://schema.openid.net/birthDate'               => 'sa_preset_birthday',
    'http://schema.openid.net/birthDate/birthYear'     => 'sa_preset_birthday',
    'http://schema.openid.net/birthDate/birthMonth'    => 'sa_preset_birthday',
    'http://schema.openid.net/birthDate/birthday'      => 'sa_preset_birthday',
    'http://schema.openid.net/person/gender'           => 'sa_preset_sex',
    'http://schema.openid.net/contact/postalCode/home' => 'sa_preset_postal_code',
    'http://schema.openid.net/contact/phone/default'   => 'sa_preset_telephone_number',
    'http://schema.openid.net/contact/country/home'    => 'sa_preset_country',
    'http://schema.openid.net/media/biography'         => 'sa_preset_self_introduction',
    'http://schema.openid.net/pref/language'           => 'language',
    'http://schema.openid.net/pref/timezone'           => 'time_zone',
    'http://schema.openid.net/media/image/default'     => 'image',
    'http://schema.openid.net/media/image/aspect11'    => 'image',
    'http://schema.openid.net/media/image/aspect43'    => 'image',
    'http://schema.openid.net/media/image/aspect34'    => 'image',
  );

  public
    $profiles = array(
      'http://schema.openid.net/birthDate',
      'http://schema.openid.net/birthDate/birthYear',
      'http://schema.openid.net/birthDate/birthMonth',
      'http://schema.openid.net/birthDate/birthday',
      'http://schema.openid.net/person/gender',
      'http://schema.openid.net/contact/postalCode/home',
      'http://schema.openid.net/contact/country/home',
      'http://schema.openid.net/media/biography',
      'http://schema.openid.net/contact/phone/default',
    ),
    $names = array(
      'http://schema.openid.net/namePerson/friendly',
      'http://schema.openid.net/namePerson',
    ),
    $emails = array(
      'http://schema.openid.net/contact/email',
    ),
    $images = array(
      'http://schema.openid.net/media/image/default',
      'http://schema.openid.net/media/image/aspect11',
      'http://schema.openid.net/media/image/aspect43',
      'http://schema.openid.net/media/image/aspect34',
    ),
    $configs = array(
      'http://schema.openid.net/pref/language',
      'http://schema.openid.net/pref/timezone',
    );

  public function getPersonGender()
  {
    $sex = (string)$this->member->getProfile('sa_preset_sex');
    if (!$sex)
    {
      return '';
    }

    return ('Man' === $sex ? 'M' : 'W');
  }

  public function getPrefLanguage()
  {
    $language = $this->member->getConfig('language');

    return str_replace('_', '-', $language);
  }

  public function getBirthDateBirthYear()
  {
    $birth = (string)$this->member->getProfile('sa_preset_birthday');
    if (!$birth)
    {
      return '';
    }

    $tmp = explode('-', $birth);

    return $tmp[0];
  }

  public function getBirthDateBirthMonth()
  {
    $birth = (string)$this->member->getProfile('sa_preset_birthday');
    if (!$birth)
    {
      return '';
    }

    $tmp = explode('-', $birth);

    return $tmp[1];
  }

  public function getBirthDateBirthday()
  {
    $birth = (string)$this->member->getProfile('sa_preset_birthday');
    if (!$birth)
    {
      return '';
    }

    $tmp = explode('-', $birth);

    return $tmp[2];
  }

  public function getMediaImageAspect11()
  {
    return $this->getMemberImageURI(array('size' => '180x180'));
  }

  public function getMediaImageAspect43()
  {
    return $this->getMemberImageURI(array('size' => '640x480'));
  }

  public function getMediaImageAspect34()
  {
    return $this->getMemberImageURI(array('size' => '480x640'));
  }

  protected function getGetterMethodName($key)
  {
    $path = str_replace('/', '_', substr(parse_url($key, PHP_URL_PATH), 1));

    return 'get'.sfInflector::camelize($path);
  }
}
