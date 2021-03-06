<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * member actions.
 *
 * @package    SfAdvanced
 * @subpackage member
 * @author     Kousuke Ebihara <ebihara@php.net>
 */
class memberActions extends saMemberAction
{
  public function executeHome(saWebRequest $request)
  {
    $this->gadgetConfig = sfConfig::get('sa_mobile_gadget_list');

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('mobile');
    $this->mobileTopGadgets = $gadgets['mobileTop'];
    $this->mobileContentsGadgets = $gadgets['mobileContents'];
    $this->mobileBottomGadgets = $gadgets['mobileBottom'];

    $filteredCategory = $this->filterConfigCategory();
    $this->categories = $filteredCategory['category'];
    $this->categoryCaptions = $filteredCategory['captions'];

    return parent::executeHome($request);
  }

  public function executeLogin(saWebRequest $request)
  {
    if (saConfig::get('external_mobile_login_url') && $request->isMethod(sfWebRequest::GET))
    {
      $this->redirect(saConfig::get('external_mobile_login_url'));
    }

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('mobileLogin');
    $this->mobileLoginContentsGadgets = $gadgets['mobileLoginContents'];

    return parent::executeLogin($request);
  }

  public function executeSearch(saWebRequest $request)
  {
    $this->size = 10;

    return parent::executeSearch($request);
  }

  public function executeProfile(saWebRequest $request)
  {
    $this->friendsSize = 5;
    $this->communitiesSize = 5;

    $gadgets = Doctrine::getTable('Gadget')->retrieveGadgetsByTypesName('mobileProfile');
    $this->mobileTopGadgets = $gadgets['mobileProfileTop'];
    $this->mobileContentsGadgets = $gadgets['mobileProfileContents'];
    $this->mobileBottomGadgets = $gadgets['mobileProfileBottom'];

    return parent::executeProfile($request);
  }

  public function executeConfigUID(saWebRequest $request)
  {
    $option = array('member' => $this->getUser()->getMember());
    $this->passwordForm = new saPasswordForm(array(), $option);
    $mobileUid = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('mobile_uid', $this->getUser()->getMemberId());
    $this->isSetMobileUid = $mobileUid && $mobileUid->getValue();
    $this->isDeletableUid = ((int)saConfig::get('retrieve_uid') < 2) && $this->isSetMobileUid;

    if ($request->isMethod('post'))
    {
      $this->passwordForm->bind($request->getParameter('password'));
      if ($this->passwordForm->isValid())
      {
        if ($request->hasParameter('update'))
        {
          $cookieUid = sfContext::getInstance()->getResponse()->generateMobileUidCookie();

          if (!$request->getMobileUID(false) && !$cookieUid)
          {
            $this->getUser()->setFlash('error', 'Your mobile UID was not registered.');
            $this->redirect('member/configUID');
          }

          $member = $this->getUser()->getMember();
          $member->setConfig('mobile_uid', $request->getMobileUID(false));
          if ($cookieUid)
          {
            $member->setConfig('mobile_cookie_uid', $cookieUid);
          }

          $this->getUser()->setFlash('notice', 'Your mobile UID was set successfully.');
          $this->redirect('member/configUID');
        }
        elseif ($request->hasParameter('delete') && $this->isDeletableUid)
        {
          $mobileUid->delete();
          sfContext::getInstance()->getResponse()->deleteMobileUidCookie();
          $this->getUser()->setFlash('notice', 'Your mobile UID was deleted successfully.'); 
          $this->redirect('member/configUID');
        }
      }
    }

    return sfView::SUCCESS;
  }

  public function executeRegisterMobileToRegisterEnd(saWebRequest $request)
  {

    $id = $request->getParameter('id');
    $token = $request->getParameter('token');

    $memberConfig = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('register_mobile_token', $id);

    $this->forward404Unless($memberConfig && $token === $memberConfig->getValue());

    saActivateBehavior::disable();
    $this->form = new saPasswordForm(null, array('member' => $memberConfig->getMember()));
    saActivateBehavior::enable();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('password'));
      if ($this->form->isValid())
      {
        $member = $memberConfig->getMember();
        $pre = Doctrine::getTable('MemberConfig')->retrieveByNameAndMemberId('mobile_address_pre', $id);
        if ($pre)
        {
          $member->setConfig('mobile_address', $pre->getValue());
          $pre->delete();
        }
        $member->setConfig('mobile_uid', $request->getMobileUID(false));

        $this->getUser()->setCurrentAuthMode($memberConfig->getMember()->getConfig('register_auth_mode'));
        $this->getUser()->setMemberId($id);
        $this->redirect($this->getUser()->getRegisterEndAction());
      }
    }

    return sfView::SUCCESS;
  }

  public function executeDeleteImage(saWebRequest $request)
  {
    $this->image = Doctrine::getTable('MemberImage')->find($request->getParameter('member_image_id'));
    $this->forward404Unless($this->image);
    $this->forward404Unless($this->image->getMemberId() == $this->getUser()->getMemberId());

    $this->form = new sfForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      $this->image->delete();
      $this->redirect('member/configImage');
    }
  }

  public function executeShowActivity(saWebRequest $request)
  {
    $this->size = 10;

    parent::executeShowActivity($request);
  }

  public function executeSetSid(saWebRequest $request)
  {
    $this->forward404Unless(isset($request['sid']));
    $this->forward404Unless(isset($request['ts']) && abs(time() - $request['ts']) <= strtotime('30 minutes'));

    $sid = $this->getUser()->decryptSid($request['sid'], $request['ts']);

    $uri = isset($request['next_uri']) ? $request['next_uri'] : '';
    $validator = new saValidatorNextUri();

    try
    {
      $uri = $validator->clean($uri);
    }
    catch (Exception $e)
    {
      $this->forward404($e->getMessage());
    }

    $this->getUser()->setSid($sid, !empty($request['is_remember_login']));

    $this->redirect($uri);
  }

 /**
  * Execute show all member activities action
  *
  * @param saWebRequest $request a request object
  */
  public function executeShowAllMemberActivity(saWebRequest $request)
  {
    $this->size = 10;

    return parent::executeShowAllMemberActivity($request);
  }

}
