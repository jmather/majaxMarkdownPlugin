<?php

require_once dirname(__FILE__).'/../lib/BasemajaxMarkdownActions.class.php';

/**
 * majaxMarkdown actions.
 * 
 * @package    majaxMarkdownPlugin
 * @subpackage majaxMarkdown
 * @author     Jacob Mather
 * @version    SVN: $Id: actions.class.php 12534 2008-11-01 13:38:27Z Kris.Wallsmith $
 */
class majaxMarkdownActions extends BasemajaxMarkdownActions
{
  public function executePreview(sfWebRequest $request)
  {
    $preview = majaxMarkdown::transform($request->getParameter('markdown'));
    if (sfConfig::get('app_majaxMarkdown_post_preview', false))
    {
      $render = sfConfig::get('app_majaxMarkdown_post_preview');
      $preview = call_user_func($render, $preview);
    }
    $this->preview = $preview;
  }
  public function executePreviewFrame(sfWebRequest $request)
  {
    sfConfig::set('sf_web_debug', false);
  }
}
