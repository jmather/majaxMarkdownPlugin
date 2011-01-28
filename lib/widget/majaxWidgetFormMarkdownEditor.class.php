<?php
class majaxWidgetFormMarkdownEditor extends sfWidgetFormTextarea {
  
  protected function configure($options = array(), $attributes = array())
  {
    $this->setAttribute('cols', 70);
    $this->setAttribute('rows', 20);
    parent::configure($options, $attributes);
    $this->addOption('skin', 'simple');
    $this->addOption('set', 'markdown');
    $this->addOption('stacked', false);
    $this->addOption('height', null);
  }
  
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    $sfContext = sfContext::getInstance();
    $resp = $sfContext->getResponse();
    $resp->addJavascript('/majaxMarkdownPlugin/js/markitup/jquery.markitup.js');
    $resp->addJavascript('/majaxMarkdownPlugin/js/markitup/sets/'.$this->getOption('set').'/set.js');
    $resp->addStylesheet('/majaxMarkdownPlugin/js/markitup/skins/'.$this->getOption('skin').'/style.css');
    $resp->addStylesheet('/majaxMarkdownPlugin/js/markitup/sets/'.$this->getOption('set').'/style.css');
    $resp->addStylesheet('/majaxMarkdownPlugin/css/display.css');
    $id = $this->generateId($name);
    $out = '';
    if ($this->getOption('stacked') == true)
    {
      $attributes['style'] = 'width: 98% !important;';
    }
    if ($this->getOption('height') !== null)
    {
      $out .= '<style>#'.$id.' { height: '.$this->getOption('height').' !important; }</style>';
    }
    $out .= parent::render($name, $value, $attributes, $errors);
    $out .= '<button id="'.$id.'_style_guide_button" aria-disabled="false" role="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" id="button"><span class="ui-button-text">Open Style Guide</span></button>';
    $out .= '
<div id="'.$id.'_style_guide" title="Style Guide">
<table width="100%"><tbody><tr><td valign="top">
<h3>Phrase Emphasis</h3>

<pre><code>*italic*   **bold**
_italic_   __bold__
</code></pre>

<h3>Headers</h3>

<p>Setext-style:</p>

<pre><code>Header 1
========

Header 2
--------
</code></pre>

<p>atx-style (closing #\'s are optional):</p>

<pre><code># Header 1 #

## Header 2 ##

###### Header 6
</code></pre>

<h3>Lists</h3>

<p>Ordered, without paragraphs:</p>

<pre><code>1.  Foo
2.  Bar
</code></pre>

<p>Unordered, with paragraphs:</p>

<pre><code>*   A list item.

    With multiple paragraphs.

*   Bar

</code></pre>

<p>You can nest them:</p>

<pre><code>*   Abacus
    * ass
*   Bastard
    1.  bitch
    2.  bupkis
        * BELITTLER
    3. burper
*   Cunning
</code></pre>

<h3>Special Characters</h3>
<table>
 <thead>
  <tr>
   <th>Name</th><th>Char</th><th>Code</th>
  </tr>
 </thead>
 <tbody>
  <tr>
   <td>en-dash</td><td>&ndash;</td><td><kbd>--</kbd></td>
  </tr>
  <tr>
   <td>em-dash</td><td>&mdash;</td><td><kbd>---</kbd></td>
  </tr>
  <tr>
   <td>ellipsis</td><td>&hellip;</td><td><kbd>...</kbd></td>
  </tr>
  <tr>
   <td>guillemets</td><td>&laquo; or &raquo;</td><td><kbd>&lt;&lt; or &gt;&gt;</kbd></td>
  </tr>
 </tbody>
</table>
</td><td valign="top">
<h3>Links</h3>

<p>Inline:</p>

<pre><code>An [example](http://url.com/ "Title")

</code></pre>

<p>Reference-style labels (titles are optional):</p>

<pre><code>An [example][id]. Then, anywhere
else in the doc, define the link:

  [id]: http://example.com/  "Title"
</code></pre>

<h3>Images</h3>

<p>Inline (titles are optional):</p>

<pre><code>![alt text](/path/img.jpg "Title")
</code></pre>

<p>Reference-style:</p>

<pre><code>![alt text][id]

  [id]: /url/to/img.jpg "Title"
</code></pre>

<h3>Blockquotes</h3>

<pre><code>&gt; Email-style angle brackets
&gt; are used for blockquotes.

&gt; &gt; And, they can be nested.


&gt; #### Headers in blockquotes
&gt; 
&gt; * You can quote a list.
&gt; * Etc.
</code></pre>

<h3>Horizontal Rules</h3>

<p>Three or more dashes or asterisks:</p>

<pre><code>---

* * *

- - - -
</code></pre>

<h3>Manual Line Breaks</h3>

<p>End a line with two or more spaces:</p>

<pre><code>Roses are red,   
Violets are blue.
</code></pre>

</td></tr></tbody></table>

</div>
';
    $out .= '<script type="text/javascript">
(function($){
	$(\'#'.$id.'_style_guide\').dialog({autoOpen: false, width: 700, height: 500});
	$(\'#'.$id.'_style_guide_button\').click(function() { $(\'#'.$id.'_style_guide\').dialog(\'open\'); return false; });
	var intHeightTimer = null;
	var toPreviewUpdateTimer = null;
	myMarkdownSettings = mySettings;
	myMarkdownSettings[\'previewParserVar\'] = \'markdown\';
	myMarkdownSettings[\'previewPosition\'] = \'before\';
	myMarkdownSettings[\'previewParserPath\'] = \''.url_for('majaxMarkdown/preview').'\';
	myMarkdownSettings[\'previewAutoRefresh\'] = true;
	$(\'#'.$id.'\').markItUp(mySettings);';
    if ($this->getOption('stacked') == false)
      $out .= '
	$(\'#'.$id.'\').before(\'<iframe style="margin-top: 22px; width: 48%; float: right;" src="'.url_for('majaxMarkdown/previewFrame?id='.$id).'" id="'.$id.'_preview_panel" name="'.$id.'_preview_panel"></iframe>\');';
    else
      $out .= '
	$(\'#'.$id.'\').parent().append(\'<br /><iframe style="width: 99%;" src="'.url_for('majaxMarkdown/previewFrame?id='.$id).'" id="'.$id.'_preview_panel" name="'.$id.'_preview_panel"></iframe>\');';

    $out .= '
	var timedFunction = function() { $(\'#'.$id.'_preview_panel\').css(\'height\', $(\'#'.$id.'\').height()+\'px\'); };
	intHeightTimer = setInterval(timedFunction, 1000);
	$(document).bind(\'unload\', function() { clearInterval(intHeightTimer); });

	var fnTextPreviewUpdateTrigger = function(previewUpdateTimer, textPreviewUpdate) {
		return function() {
			if (previewUpdateTimer != null)
				clearTimeout(previewUpdateTimer);
			previewUpdateTimer = setTimeout(textPreviewUpdate, 200);
		};
	};


	var fnTextPreviewUpdate = function(textPreviewUpdateCallback) {
		return function() {
			$.post(\''.url_for('majaxMarkdown/preview').'\', { markdown: $(\'#'.$id.'\').val() }, textPreviewUpdateCallback, \'text\');
		};
	};
	var fnTextPreviewUpdateCallback = function(data) {
		var area = window.frames[\''.$id.'_preview_panel\'].document.getElementById(\'preview_content\');
		$(area).html(data);
	};

	var ttF = fnTextPreviewUpdateTrigger(toPreviewUpdateTimer, fnTextPreviewUpdate(fnTextPreviewUpdateCallback));

	$(\'#'.$id.'\').keypress(ttF);
	$(\'#'.$id.'\').change(ttF);
	$(\'#'.$id.'\').blur(ttF);
	var ridx = {\'parent\': false, \'child\': false};
	var tF = fnTextPreviewUpdate(fnTextPreviewUpdateCallback);
	var bothReady = function(fn, ridx) {
		return function(r) {
			return function() {
				ridx[r] = true;
				if (ridx[\'parent\'] == true && ridx[\'child\'] == true)
					fn();
			}
		}
	}
	var brF = bothReady(tF, ridx);
	$(window.frames[\''.$id.'_preview_panel\'].document).ready(brF(\'child\'));
	$(document).ready(brF(\'parent\'));

	var fnMatchScrollA = function()
	{
		var a = document.getElementById(\''.$id.'\');
		var b = window.frames[\''.$id.'_preview_panel\'];

		var top = a.scrollTop;
		b.scrollBy(0, top - b.document.body.scrollTop);
	}
	var fnMatchScrollB = function()
	{
		var b = document.getElementById(\''.$id.'\');
		var a = window.frames[\''.$id.'_preview_panel\'];

		var top = a.document.body.scrollTop;
		b.scrollTop = top;
//		b.scrollBy(0, top - b.scrollTop);
	}
	document.getElementById(\''.$id.'\').onscroll = fnMatchScrollA;
	window.frames[\''.$id.'_preview_panel\'].onscroll = fnMatchScrollB;
})(jQuery);
</script>';
    return $out; 
  }
}
