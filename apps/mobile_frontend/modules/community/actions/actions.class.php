<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * community actions.
 *
 * @package    SfAdvanced
 * @subpackage community
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 * @author     Eitarow Fukamachi <fukamachi@tejimaya.net>
 */
class communityActions extends saCommunityAction
{
  /**
   * Executes home action
   *
   * @param saWebRequest $request a request object
   */
  public function executeHome(saWebRequest $request)
  {
    $this->membersSize = 5;

    return parent::executeHome($request);
  }

  /**
   * Executes joinlist action
   *
   * @param saWebRequest $request a request object
   */
  public function executeJoinlist(saWebRequest $request)
  {
    $this->size = 10;

    return parent::executeJoinlist($request);
  }

  /**
   * Executes memberList action
   *
   * @param saWebRequest $request a request object
   */
  public function executeMemberList(saWebRequest $request)
  {
    $this->size = 10;

    return parent::executeMemberList($request);
  }

  /**
   * Executes search action
   *
   * @param saWebRequest $request a request object
   */
  public function executeSearch(saWebRequest $request)
  {
    sfConfig::set('sf_nav_type', 'default');

    $params = $request->getParameter('community', array());
    $this->isResult = false;
    $this->category_id = 0;
    if (isset($params['name']))
    {
      $params['name'] = $params['name'];
      $this->isResult = true;
    }
    if (isset($params['community_category_id']))
    {
      $this->category_id = $params['community_category_id'];
      $params['community_category_id'] = $this->category_id;
      $this->isResult = true;
    }

    $this->filters = new CommunityFormFilter();
    $this->filters->bind($params);
    $q = $this->filters->getQuery()->orderBy('id desc');

    $this->pager = new sfDoctrinePager('Community', 10);
    $this->pager->setQuery($q);
    $this->pager->setPage($request->getParameter('page', 1));
    $this->pager->init();

    $this->categorys = Doctrine::getTable('CommunityCategory')->getAllChildren();

    return sfView::SUCCESS;
  }

  /**
   * Executes detail action
   *
   * @param saWebRequest $request a request object
   */
  public function executeDetail(saWebRequest $request)
  {
    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community, 'Undefined community.');
    $this->communityAdmin = Doctrine::getTable('CommunityMember')->getCommunityAdmin($this->id);
    $this->communityAdmin = Doctrine::getTable('Member')->find($this->communityAdmin->getMemberId());
    $this->communitySubAdmins = $this->community->getSubAdminMembers();

    return sfView::SUCCESS;
  }

  /**
   * Executes configImage action
   *
   * @param saWebRequest $request a request object
   */
  public function executeConfigImage(saWebRequest $request)
  {
    $this->forward404Unless($this->id && $this->isEditCommunity);
    $this->community = Doctrine::getTable('Community')->find($this->id);
  }

  /**
   * Executes deleteImage action
   *
   * @param saWebRequest $request a request object
   */
  public function executeDeleteImage(saWebRequest $request)
  {
    $this->forward404Unless($this->id && $this->isEditCommunity);

    $this->community = Doctrine::getTable('Community')->find($this->id);
    $this->forward404Unless($this->community->getImageFileName());

    $this->form = new sfForm();
    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      $this->community->getFile()->delete();
      $this->redirect('community/configImage?id='.$this->id);
    }
  }
}
