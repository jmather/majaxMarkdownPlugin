<?php
function clean_content($content)  
{
        $content = preg_replace('/^\ +/m', '', $content);
        $content = preg_replace('/^ \*/m', '*', $content);
        $content = preg_replace('/^\#\#\#$/m', '', $content);
        $content = preg_replace('/^\*$/m', '', $content);
        $content = preg_replace('/^[12]$/m', '', $content);
        $content = trim($content);
	$content = str_replace('href= ', 'href=', $content);
        return $content;
}
function cleanupHtml($str)
{
        $sets = array(
                '</b><b>',
                '</u><u>',
                '</strong><strong>',
                '</em><em>',
                '</i><i>',
        );

	$str = str_replace(array("\r", "\n", "\t"), '', $str);
        $str = str_replace($sets, '', $str);
        $str = str_replace($sets, '', $str);
        $str = str_replace($sets, '', $str);
        $str = str_replace($sets, '', $str);
	$str = preg_replace('/<p[^>]+>/', '<p>', $str);
	$str = preg_replace('/<div[^>]+>/ms', '', $str);
	$str = preg_replace('/<\/div>/', '', $str);
	$str = preg_replace('/<font[^>]+>/ms', '', $str);
	$str = preg_replace('/<\/font>/ms', '', $str);
	$str = preg_replace('/<li[^>]+>/ms', '<li>', $str);
	$str = preg_replace('/(?:<u>)?(<a[^>]+>)(?:<\/u>)?(?:<u>)?([^<]+)(?:<\/u>)?(<\/a>)/i', '$1$2$3', $str);
	$str = preg_replace('/href=\s+/', 'href=', $str);
	return $str;
}
function cleanMarkdown($str)
{
  $str = trim(preg_replace('/<!--.*-->/', '', $str));
  $str = preg_replace('/\[_([^\]]+)_\]/ms', '[$1]', $str);
  $str = preg_replace('/\*\* \*\*/', ' ', $str);
  $str = preg_replace('/\* \*/', ' ', $str);
  $str = preg_replace('/_ _/', ' ', $str);
  $str = preg_replace('/<\/u>/i', '', $str);
  return $str;
}
?>
<html>
	<head><title>Upload Word File</title></head>
	<body>
		<form method="post" action="" enctype="multipart/form-data">
			<input type="file" name="file" />
			<input type="submit" name="Upload" />
		</form>
<?php

foreach($_FILES as $file)
{
	exec('/usr/bin/wvHtml '.$file['tmp_name'].' '.$file['tmp_name'].'.txt');
	$cont = file_get_contents($file['tmp_name'].'.txt');
	unlink($file['tmp_name']);
	unlink($file['tmp_name'].'.txt');
	$cont = clean_content($cont);
	$cont = cleanupHtml($cont);
	if (class_exists('tidy'))
	{
		// Specify configuration
		$config = array(
			'indent'         => true,
			'output-xhtml'   => true,
			'wrap'           => 200);
		$tidy = new tidy();
		$tidy->parseString($cont, $config, 'utf8');
		$tidy->cleanRepair();
		$cont = $tidy;
	}

	$cont = cleanupHtml($cont);
//echo '<pre>'.htmlentities($cont).'</pre>';
	require_once dirname(__FILE__).'/../vendor/markdownify/markdownify_extra_majax.php';
	$md = new Markdownify_Extra_Majax();
	$cont = $md->parseString($cont);

	$cont = cleanMarkdown($cont);

//	$cont = wordwrap($cont);

	echo '<script type="text/javascript">window.opener.set'.$_GET['id'].'Content(unescape(\''.rawurlencode($cont).'\'));</script>';
//	echo '<pre>'.htmlentities($cont).'</pre>';
}
?>
	</body>
</html>
