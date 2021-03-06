<?php

/**
 * This file is part of the SfAdvanced package.
 * (c) SfAdvanced Project (http://www.sfadvanced.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

/**
 * site actions.
 *
 * @package    SfAdvanced
 * @subpackage site
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */
class siteActions extends sfActions
{
 /**
  * Executes config action
  *
  * @param sfRequest $request A request object
  */
  public function executeConfig(sfWebRequest $request)
  {
    $this->category = $request->getParameter('category', 'general');
    $this->categoryAttributes = sfConfig::get('sfadvanced_site_category_attribute');

    $this->forward404If(!empty($this->categoryAttributes[$this->category]['Hidden']));

    $formName = 'sa'.sfInflector::camelize($this->category).'SiteConfigForm';
    if (class_exists($formName, true))
    {
      $this->form = new $formName();
    }
    else
    {
      $this->form = new SiteConfigForm(array(), array('category' => $this->category));
    }

    if ($request->isMethod('post'))
    {
      $this->form->bind($request->getParameter('site_config'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('site/config?category='.$this->category);
      }

      $this->getUser()->setFlash('error', 'Failed to save.', false);
    }
  }

 /**
  * Executes term action
  *
  * @param sfRequest $request A request object
  */
  public function executeTerm(sfWebRequest $request)
  {
    $this->form = new saSiteTermForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $this->form->bind($request->getParameter('term'));
      if ($this->form->isValid())
      {
        $this->form->save();
        $this->getUser()->setFlash('notice', 'Saved.');
        $this->redirect('site/term');
      }
    }
  }

 /**
  * Executes list action
  *
  * @param sfWebRequest $request A request object
  */
  public function executeList(sfWebRequest $request)
  {
    $this->list = array();

    $types = Doctrine::getTable('Navigation')->getTypesByAppName($request->getParameter('app', 'pc'));

    foreach ($types as $type)
    {
      $navs = Doctrine::getTable('Navigation')->retrieveByType($type);
      foreach ($navs as $nav)
      {
        $this->list[$type][] = new NavigationForm($nav);
      }
      $nav = new Navigation();
      $nav->setType($type);
      $this->list[$type][] = new NavigationForm($nav);
    }
  }

  public function executeCache(sfWebRequest $request)
  {
    $this->form = new sfForm();

    if ($request->isMethod(sfWebRequest::POST))
    {
      $request->checkCSRFProtection();

      saToolkit::clearCache();

      $this->getUser()->setFlash('notice', 'Caches are now cleared.');
      $this->redirect('site/cache');
    }
  }
}
