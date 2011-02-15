<?php

require_once dirname(__FILE__).'/markdownify_extra.php';
    
class Markdownify_Extra_Majax extends Markdownify_Extra {

  /**
   * constructor, see Markdownify::Markdownify() for more information
   */
  function Markdownify_Extra_Majax($linksAfterEachParagraph = MDFY_LINKS_EACH_PARAGRAPH, $bodyWidth = MDFY_BODYWIDTH, $keepHTML = MDFY_KEEPHTML) {
    parent::Markdownify($linksAfterEachParagraph, $bodyWidth, $keepHTML);
    $this->isMarkdownable['u'] = array();
    $this->parser->blockElements['u'] = false;
  }
  function handleTag_u() {
    $this->out('_', true);
  }
}
