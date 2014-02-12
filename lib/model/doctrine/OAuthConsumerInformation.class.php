<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class OAuthConsumerInformation extends BaseOAuthConsumerInformation
{
  public function preSave($event)
  {
    if (!$this->exists())
    {
      $this->key_string = saToolkit::generatePasswordString(16, false);
      $this->secret = saToolkit::generatePasswordString(32);
    }
  }

  public function getAPICaptions()
  {
    $apiList = array();

    foreach (saToolkit::retrieveAPIList() as $key => $value)
    {
      if (in_array($key, $this->getUsingApis()))
      {
        $apiList[] = $value;
      }
    }

    return $apiList;
  }

  public function getOAuthAdminAccessToken()
  {
    return Doctrine::getTable('OAuthAdminToken')->findOneByOauthConsumerIdAndType($this->id, 'access');
  }

  public function getOAuthMemberAccessToken($memberId)
  {
    return Doctrine::getTable('OAuthMemberToken')
      ->findOneByOauthConsumerIdAndTypeAndMemberId($this->id, 'access', $memberId);
  }

  public function getImageFileName()
  {
    return (string)$this->getImage();
  }
}
