<?php
include 'simple_html_dom.php';
ini_set('display_errors',1);
error_reporting(E_ALL);

define('DEBUG', 0);
function debug($msg) {
    if(defined('DEBUG'))
	echo $msg;
}
define('SUCCESS', 200);
define('EMPTY_PARAMETER', 201);
define('NOT_VALID_URL', 202);
define('CANNOT_OPEN', 203);

if(!isset($_GET['userid']) || !isset($_GET['url']))
{
    die(''.EMPTY_PARAMETER);
}

$userid = $_GET['userid'];
$url = $_GET['url'];

//--------------------------------------------------get host name
$host_name = get_host_name($url);
//debug($host_name.'<br>');

function get_host_name($url) {
    // Extract the host name from the usrl
    $start = strpos($url, '://');
    if($start === false) {
        die(''.NOT_VALID_URL);
    }
    $start += 3;
    $end = strpos($url, '/', $start);
    if($end === false) 
 	return substr($url, $start);
    else 
	return substr($url, $start, $end-$start);
}

$html = file_get_html(htmlspecialchars_decode($url));
if(!isset($html) || !is_object($html)) {
    die(''.CANNOT_OPEN);
}

$titleTagText = $html->find('title',0)->plaintext;
//debug($titleTagText);
if(!isset($titleTagText) || strlen(trim($titleTagText)) <= 0)
    $title = 'Unknown'; 
else $title = $titleTagText;

$paras = $html->find('p,pre');

//if(defined('DEBUG')) {
    //foreach($paras as $p)
     	//echo $p->innertext.'<br>';
//}
//-----------------------------------------Now check whether this page is an article or not
if(!is_article()) {
    //insert the isReadble as false and the original link to the database
   echo SUCCESS;
   cleanUp(); 
   die("Not article"); 
}

//detect whether this page is an article or not
function is_article() {
    global $paras;
    if(!is_array($paras) || count($paras) <= 0) return false;
    else {
   	$validPara = 0;
	foreach($paras as $p) {
	    //debug($p->innertext.'<br>');
	    if(isset($p->innertext) && strlen(trim($p->innertext)) > 50) {
		if(strlen(trim($p->innertext)) > 200)
		    return true;
		$validPara += 1;
		if($validPara >= 10)
		    return true; 
	    }
	}
	if($validPara < 10) return false;
	return true;   
    }
}

//-----------------------------------------It is an article, start parsing-----------------
$startNode = find_start_node();
debug($title);
// Get first start tag, from which to start parsing.
function isMatchTitle($h, $t) {
   $hwords = explode(' ', $h); 
   $match = 0;
   foreach($hwords as $hword) {
	$isIntitle = strpos($t, $hword);
	if($isIntitle === false) continue;
	$match++;
   }
   if($match >= count($hwords) * 0.9)
	return true;
   return false;
}

function find_start_node() {
    global $html, $paras, $title, $titleTagText, $startNode;
    /*$node = $firstP->prev_sibling();
    while(isset($node)) {
        debug($node->tag);
        $node = $node->prev_sibling();
    }*/
    $hs = $html->find('h1,h2,h3,h4,h5,h6,h7');
    //debug($titleTagText.'<br>');
    if(is_array($hs)) {
	foreach($hs as $h)
	{
	    //debug($h->plaintext);
	    if(isMatchTitle($h->plaintext, $titleTagText)) 
	    {
		$title = $h->plaintext;
		return $h;
	    }
	}
    }
    else {
	//There is no h tags
	return $paras[0];
    }
}

//----------------------------parsing from startNode
 
//echo SUCCESS;
cleanUp();
function cleanUp() {
    global $html;
    $html->clear(); 
    unset($GLOBALS['paras']); 
    unset($html);
}
?>
