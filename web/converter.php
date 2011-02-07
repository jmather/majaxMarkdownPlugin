<?php
function clean_content($content)  
{
        $content = preg_replace('/^\ +/m', '', $content);
        $content = preg_replace('/^ \*/m', '*', $content);
        $content = preg_replace('/^\#\#\#$/m', '', $content);
        $content = preg_replace('/^\*$/m', '', $content);
        $content = preg_replace('/^[12]$/m', '', $content);
        $content = trim($content);
        return $content;
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
	exec('/usr/bin/wvText '.$file['tmp_name'].' '.$file['tmp_name'].'.txt');
	$cont = file_get_contents($file['tmp_name'].'.txt');
	$cont = clean_content($cont);
	unlink($file['tmp_name']);
	unlink($file['tmp_name'].'.txt');
	echo '<pre>'.$cont.'</pre>';
	
}
?>
	</body>
</html>
