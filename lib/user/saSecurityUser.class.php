<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * saSecurityUser will handle credential for SfAdvanced.
 *
 * @package    SfAdvanced
 * @subpackage user
 * @author     Kousuke Ebihara <ebihara@php.net>
 * @author     Shogo Kawahara <kawahara@tejimaya.com>
 */
class saSecurityUser extends saAdaptableUser
{
  protected
    $authAdapters = array(),
    $serializedMember = '';

  /**
   * Initializes the current user.
   *
   * @see sfBasicSecurityUser
   */
  public function initialize(sfEventDispatcher $dispatcher, sfStorage $storage, $options = array())
  {
    parent::initialize($dispatcher, $storage, $options);

    $this->initializeUserStatus();
  }

  public function clearSessionData()
  {
    parent::clearSessionData();

    // remove member cache
    $this->serializedMember = '';
  }

  public function getMemberId($inactive = false)
  {
    return $this->getMember($inactive)->getId();
  }

  public function setMemberId($memberId)
  {
    $this->serializedMember = '';

    return $this->setAttribute('member_id', $memberId, 'saSecurityUser');
  }

  /**
   * Get Member object. This method always return object.
   *
   * @param  bool $inactive If true then use Member::findInactive(), otherwise use Member::find().
   * @return Member or saAnonymousMember
   */
  public function getMember($inactive = false)
  {
    // get memberId from session storage
    $memberId = $this->getAttribute('member_id', null, 'saSecurityUser');

    if (!$memberId)
    {
      return new saAnonymousMember();
    }

    if ($inactive)
    {
      $member = Doctrine::getTable('Member')->findInactive($memberId);
      if (!$member)
      {
        return new saAnonymousMember();
      }

      return $member;
    }

    if ($this->serializedMember)
    {
      $member = unserialize($this->serializedMember);
    }
    else
    {
      // You may get a inactive Member object here.
      $member = Doctrine::getTable('Member')->find($memberId);
      if (!$member)
      {
        return new saAnonymousMember();
      }

      if ($member->getIsActive())
      {
        $this->serializedMember = serialize($member);
      }
    }

    return $member;
  }

  public function getCurrentMemberRegisterToken()
  {
    saActivateBehavior::disable();
    $config = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('register_token', $this->getMemberId(), true);
    saActivateBehavior::enable();

    if ($config)
    {
      return $config->getValue();
    }

    return null;
  }

  public function getRegisterInputAction($token = null)
  {
    if (!$token)
    {
      $token = $this->getCurrentMemberRegisterToken();
    }

    return $this->getAuthAdapter()->getRegisterInputAction($token);
  }

  public function getRegisterEndAction($token = null)
  {
    if (!$token)
    {
      $token = $this->getCurrentMemberRegisterToken();
    }

    return $this->getAuthAdapter()->getRegisterEndAction($token);
  }

 /**
  * get remember login cookie
  *
  * @return array
  */
  protected function getRememberLoginCookie()
  {
    $key = md5(sfContext::getInstance()->getRequest()->getHost());
    if ($value = sfContext::getInstance()->getRequest()->getCookie($key))
    {
      $value = json_decode(base64_decode($value));

      return $value;
    }
  }

 /**
  * set remember login cookie
  */
  protected function setRememberLoginCookie($isDeleteCookie = false)
  {
    $key = md5(sfContext::getInstance()->getRequest()->getHost());
    $path = sfContext::getInstance()->getRequest()->getRelativeUrlRoot();
    if (!$path)
    {
      $path = '/';
    }

    if ($isDeleteCookie)
    {
      if (!sfContext::getInstance()->getRequest()->getCookie($key))
      {
        return;
      }

      if ($this->getMemberId())
      {
        $this->getMember()->setConfig('remember_key', '');
      }

      $value = null;
      $expire = time() - 3600;
    }
    else
    {
      $rememberKey = saToolkit::getRandom();
      if (!$this->getMemberId())
      {
        throw new LogicException('No login');
      }
      $this->getMember()->setConfig('remember_key', $rememberKey);

      $value = base64_encode(json_encode(array($this->getMemberId(), $rememberKey)));
      $expire = time() + sfConfig::get('sa_remember_login_limit', 60*60*24*30);
    }

    sfContext::getInstance()->getResponse()->setCookie($key, $value, $expire, $path, '', false, true);
  }

 /**
  * get memberd member id
  *
  * @return integer the member id
  */
  public function getRememberedMemberId()
  {
    if (($value = $this->getRememberLoginCookie()) && 2 == count($value))
    {
      if ($value[0] && $value[1])
      {
        $memberConfig = Doctrine::getTable('MemberConfig')->findOneByMemberIdAndNameAndValue($value[0], 'remember_key', $value[1]);
        if ($memberConfig)
        {
          $expire = strtotime($memberConfig->getUpdatedAt()) + sfConfig::get('sa_remember_login_limit', 60*60*24*30);
          if ($expire > time())
          {
            return $value[0];
          }
        }
      }
    }
  }

 /**
  * Login
  *
  * @param integer $memberId the member id
  * @return bool   returns true if the current user is authenticated, false otherwise
  */
  public function login($memberId = null)
  {
    if (is_null($memberId))
    {
      $memberId = $this->getAuthAdapter()->authenticate();
    }

    if ($memberId)
    {
      $this->setMemberId($memberId);

      saActivateBehavior::disable();
      if ($this->getMember()->isOnBlacklist())
      {
        saActivateBehavior::enable();
        $this->logout();

        return false;
      }
      saActivateBehavior::enable();
    }

    $this->initializeUserStatus();

    if ($this->isAuthenticated())
    {
      if ($this->getAuthAdapter()->getAuthForm()->getValue('is_remember_me'))
      {
        $this->setRememberLoginCookie();
      }

      // set mobile_cookie_uid for easy login
      $cookieUid = sfContext::getInstance()->getResponse()->generateMobileUidCookie();
      if ($cookieUid)
      {
        $this->getMember()->setConfig('mobile_cookie_uid', $cookieUid);
      }

      $this->setCurrentAuthMode($this->getAuthAdapter()->getAuthModeName());
      $uri = $this->getAuthAdapter()->getAuthForm()->getValue('next_uri');

      // sharing session id between HTTP and HTTPS is needed
      $request = sfContext::getInstance()->getRequest();
      if (sfConfig::get('app_is_mobile', false)
        && sfConfig::get('sa_use_ssl', false)
        && $request->isSecure()
        && ($request->getMobile()->isSoftBank() || $request->getMobile()->isEZweb())
      )
      {
        $item = $this->encryptSid(session_id());

        $uri = '@member_setSid?next_uri='.$uri
             .'&is_remember_login='.(int)$this->getAuthAdapter()->getAuthForm()->getValue('is_remember_me')
             .'&sid='.$item[0]
             .'&ts='.$item[1];
      }

      $this->setCulture($this->getMember()->getConfig('language', sfConfig::get('sf_default_culture')));

      return $uri;
    }

    return false;
  }

 /**
  * Logout
  */
  public function logout()
  {
    $authMode = $this->getCurrentAuthMode();

    $this->setRememberLoginCookie(true);

    $this->setAuthenticated(false);
    $this->getAttributeHolder()->removeNamespace('saSecurityUser');
    $this->clearCredentials();

    $this->setCurrentAuthMode($authMode);
  }

 /**
  * Registers the current user with SfAdvanced
  *
  * @param  sfForm $form
  * @return bool   returns true if the current user is authenticated, false otherwise
  */
  public function register($form = null)
  {
    $result = $this->getAuthAdapter()->register($form);
    if ($result)
    {
      $this->setAuthenticated(true);
      $this->setAttribute('member_id', $result, 'saSecurityUser');

      return true;
    }

    return false;
  }

 /**
  * Initializes all credentials associated and status with this user.
  * The user is one of the following:
  *  - (U1) a user as a logged in member
  *  - (U2) a user as a registering as a new member
  *  - (U3) a user as a logged out member
  */
  public function initializeUserStatus()
  {
    // Automatic Login
    if ($memberId = $this->getRememberedMemberId())
    {
      $this->setMemberId($memberId);
    }

    // get a instance of Member or saAnonymousMember as the user
    saActivateBehavior::disable();
    $member = $this->getMember();
    saActivateBehavior::enable();

    // if user is (U3), or (U1) but rejected login
    if ($member instanceof saAnonymousMember || $member->getIsLoginRejected())
    {
      $this->logout();
      $isSiteMember = false;
    }
    else
    {
      // this value is true only if user is (U1)
      $isSiteMember = (bool)$member->getIsActive();
    }

    if ($isSiteMember)
    {
      $member->updateLastLoginTime();
    }

    $this->setIsSiteMember($isSiteMember);
  }

  public function isMember()
  {
    return $this->isSiteMember();
  }

  public function isSiteMember()
  {
    return (bool)$this->getMember()->getIsActive();
  }

  public function isInvited()
  {
    $member = $this->getMember(true);

    return $member->getConfig('is_admin_invited', false) || $member->getInviteMemberId();
  }

  public function isRegisterBegin()
  {
    saActivateBehavior::disable();
    $memberId = $this->getMemberId();
    saActivateBehavior::enable();

    return $this->getAuthAdapter()->isRegisterBegin($memberId);
  }

  public function isRegisterFinish()
  {
    saActivateBehavior::disable();
    $memberId = $this->getMemberId();
    saActivateBehavior::enable();

    return $this->getAuthAdapter()->isRegisterFinish($memberId);
  }

  public function setIsSiteMember($isSiteMember)
  {
    $this->setAuthenticated($isSiteMember);

    // for BC
    if ($isSiteMember)
    {
      $this->addCredential('SiteMember');
    }
  }

  public function setIsSiteRegisterBegin($isSiteRegisterBegin)
  {
  }

  public function setIsSiteRegisterFinish($isSiteRegisterFinish)
  {
  }

  public function setRegisterToken($token)
  {
    $this->logout();

    if ('MailAddress' === $this->getAuthAdapter()->getAuthModeName())
    {
      $mailTypes = array("pc_address", "pc_address_pre", "mobile_address", "mobile_address_pre");
      $member = Doctrine::getTable('Member')->findByValidRegisterToken($token, $mailTypes);
    }
    else
    {
      $member = Doctrine::getTable('Member')->findByRegisterToken($token);
    }
    if (!$member)
    {
      return false;
    }

    $this->setMemberId($member->getId());

    $authMode = $member->getConfig('register_auth_mode');
    if ($authMode)
    {
      $this->setCurrentAuthMode($authMode);
    }

    return $member;
  }

  public function setSid($sid, $isRememberLogin = false)
  {
    if ($this->isAuthenticated())
    {
      return false;
    }

    session_write_close();

    // set session id from request
    session_id($sid);
    session_start();
    session_write_close();

    if ($isRememberLogin)
    {
      $this->setRememberLoginCookie();
    }
  }

  public function encryptSid($sid)
  {
    require_once 'Crypt/Blowfish.php';

    $time  = time();
    $bf = Crypt_Blowfish::factory('ecb',sfConfig::get('sa_sid_secret').'-'.$time);
    $data = base64_encode($bf->encrypt($sid));

    return array($data, $time);
  }

  public function decryptSid($data, $time)
  {
    require_once 'Crypt/Blowfish.php';

    $bf = Crypt_Blowfish::factory('ecb', sfConfig::get('sa_sid_secret').'-'.$time);
    $sid = $bf->decrypt(base64_decode($data));

    return $sid;
  }

  public function getMemberApiKey()
  {
    $member = $this->getMember();

    if ($member instanceof saAnonymousMember)
    {
      return '';
    }

    return $member->getApiKey();
  }
}
