<?php
class majaxMarkdown
{
	public static function transform($string)
	{
		$path = dirname(__FILE__).'/../vendor/'.sfConfig::get('app_markdown_style', 'markdown_extra').'/markdown.php';
		require_once($path);
		$string = Markdown($string);
		if (sfConfig::get('app_markdown_smartypants_enabled', true))
		{
			$style = sfConfig::get('app_markdown_smartypants_style', 'smartypants_typographer');
			$path = dirname(__FILE__).'/../vendor/'.$style.'/smartypants.php';
			require_once($path);
			$string = SmartyPants($string, sfConfig::get('app_markdown_smartypants_options', 1));
		}
		return $string;
	}
}
