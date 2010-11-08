<?php

/**
 * majaxMarkdownPlugin configuration.
 * 
 * @package     majaxMarkdownPlugin
 * @subpackage  config
 * @author      Jacob Mather
 * @version     SVN: $Id: PluginConfiguration.class.php 17207 2009-04-10 15:36:26Z Kris.Wallsmith $
 */
class majaxMarkdownPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.0.0-DEV';

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    $modules = sfConfig::get('sf_enabled_modules', array());
    $modules[] = 'majaxMarkdown';
    sfConfig::set('sf_enabled_modules', $modules);
  }
}
