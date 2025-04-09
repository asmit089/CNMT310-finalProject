<?php

namespace finalBookmarkProject;

/**
*
* Page class
* 
* Used to create multiple unique HTML pages.
*
*****************************
* Usage:
*****************************
*   require_once("Page.php");
*   $page = new MyNamespace\Page("My Page");
*
*   print $page->getTopSection();
*   print "<h1>Some page-specific HTML goes here</h1>" . PHP_EOL;
*   print $page->getBottomSection();
*
*****************************
*   Alternate Usage (with external CSS):
*****************************
*   require_once("Page.php");
*   $page = new MyNamespace\Page("My Page Uses CSS");
*
*   $page->addHeadElement("<link rel=\"stylesheet\" src=\"style.css\">");
*
*   print $page->getTopSection();
*   print "<h1>Some page-specific HTML goes here</h1>" . PHP_EOL;
*   print $page->getBottomSection();
*
* @author Steve Suehring <steve.suehring@uwsp.edu>
*/

class Page {

  protected $_top;
  protected $_bottom;
  protected $_title;
  protected $_lang;
  protected $_headElements = array();
  protected $_bottomElements = array();
  protected $_headSection = "";
  protected $_bodyElement = "<body>";
  private $_topPrepared = false;
  private $_bottomPrepared = false;

  function __construct($title = "Default", $lang = "en") {
    $this->_title = $title;
    $this->_lang = $lang;
  }

  /**
  * function addHeadElement($include)
  *
  * Used to add things to the <head> section of an HTML doc.
  * For example, it is typical to add CSS <link> tags
  * and <script> tags in the <head> section.
  *
  * This must be called __before__ prepareTopSection and 
  * will typically be called once for each <link>, <meta", or <script>
  * that will appear in the <head> section.
  *
  * @param string $include  The element to include
  */

  function addHeadElement($include) {
    $this->_headElements[] = $include . PHP_EOL;
  } //end function addHeadElement

  function prepareTopSection() {
    $returnVal = "";
    $returnVal .= "<!doctype html>" . PHP_EOL;
    $returnVal .= "<html lang=\"" . $this->_lang . "\">" . PHP_EOL;
    $returnVal .= "<head>" . PHP_EOL;
    $returnVal .= "<title>";
    $returnVal .= $this->_title;
    $returnVal .= "</title>" . PHP_EOL;
    foreach ($this->_headElements as $elm) {
      $returnVal .= $elm;
    }
    $returnVal .= $this->_headSection;
    $returnVal .= "</head>" . PHP_EOL;
    $returnVal .= $this->_bodyElement . PHP_EOL;

    $this->_top = $returnVal;
    $this->setTopPrepared(true);

  } //end function prepareTopSection

  /**
  * function addBottomElement($include)
  *
  * Used to add things to the bottom section of an HTML doc.
  * For example, some libraries require JavaScript right 
  * before the closing </body> tag.
  *
  * This must be called __before__ prepareBottomSection and
  * will typically be called once for each <script>
  * that will appear in the section.
  *
  *
  * @param string $include  The element to include
  */

  function addBottomElement($include) {
    $this->_bottomElements[] = $include . PHP_EOL;
  } //end function addHeadElement

  function prepareBottomSection() {
    $returnVal = "";
    foreach ($this->_bottomElements as $elm) {
      $returnVal .= $elm;
    }
    $returnVal .= "</body>" . PHP_EOL;
    $returnVal .= "</html>" . PHP_EOL;

    $this->_bottom = $returnVal;
    $this->setBottomPrepared(true);

  } //end function prepareBottomSection

  /**
  * function setBodyElement($include)
  *
  * If you need to add an attribute to the body element
  * do that here. Bootstrap sometimes requires this.
  *
  * This must be called __before__ prepareBottomSection and
  * will typically be called once for each <script>
  * that will appear in the section.
  *
  *
  * @param string $attribute  The full attribute=value to include
  */

  function setBodyElement(string $attribute) {
    $this->_bodyElement = "<body " . $attribute . ">";
  }

  function getBodyElement() {
    return $this->_bodyElement;
  }

  function getTopSection() {
    if ($this->getTopPrepared() === false) {
      $this->prepareTopSection();
    }
    return $this->_top;
  }

  function getBottomSection() {
    if ($this->getBottomPrepared() === false) {
      $this->prepareBottomSection();
    }
    return $this->_bottom;
  }

  function getTopPrepared() {
    return $this->_topPrepared;
  }

  function setTopPrepared(bool $status) {
    $this->_topPreparePrepared = $status;
  }

  function getBottomPrepared() {
    return $this->_bottomPrepared;
  }

  function setBottomPrepared(bool $status) {
    $this->_bottomPrepared = $status;
  }

} // end class

?>
