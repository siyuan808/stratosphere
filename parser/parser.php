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
$protocol;
//debug($host_name.'<br>');

function get_host_name($url) {
    // Extract the host name from the usrl
    $start = strpos($url, '://');
    if($start === false) {
        die(''.NOT_VALID_URL);
    }
    $GLOBAL['protocol'] = substr($url, 0, $start+3);
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
//debug($title);
//debug($startNode->outertext);
// Get first start tag, from which to start parsing.
function isMatchTitle($h, $t) {
   if(!isset($h)) return false;
   $hwords = explode(' ', $h); 
   $match = 0;
   foreach($hwords as $hword) {
        //echo $hword.'<br>';
        if(!isset($hword) || empty($hword)) continue;
	$isIntitle = strpos($t, $hword);
	if($isIntitle === false) continue;
	else $match++;
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
    $hs = $html->find('h1,h2,h3,h4,h5,h6');
    //debug($titleTagText.'<br>');
    $titleTagText = utf8_encode($titleTagText);
    if(is_array($hs)) {
        foreach($hs as $h) {
	    $h->plaintext = utf8_encode($h->plaintext);
	    //debug($h->plaintext."<br>".$titleTagText."<br><br>");
	    if(strcasecmp($h->plaintext, $titleTagText) == 0) {
		return $h;
	    }
	}
	foreach($hs as $h)
	{
	    if(isMatchTitle($h->plaintext, $titleTagText)) 
	    {
		$title = $h->plaintext;
		return $h;
	    }
	}
        // there is no match h tags
	echo $paras[0]->outertext;
	return $paras[0];
    }
    else {
	//There is no h tags
	echo $paras[0]->outertext;
	return $paras[0];
    }
}

//----------------------------parsing from startNode
/*while(isset($startNode) && is_object($startNode)) {
    echo $startNode->tag;
    $startNode = $startNode->next_sibling();
}*/
while(isset($startNode)) {
    parseNode($startNode->next_sibling());
    $startNode = $startNode->parent();
}

function parseNode($node) {
    //parse individual
    if(!isset($node) || !is_object($node)) return;
//    echo $node->tag.'<br>';
    //first figure out if this div is an input div

    switch($node->tag) {
        case "ol":
        case "ul":
	    if(checkList($node) === true)
		echo $node->outertext;
	    break;
	case "p":
	    if(checkPara($node) === true)
		echo $node->outertext;
	    break;
	case "pre":
 	    echo $node->outertext;
	    break;
	case "img":
	    parseSrc($node);
	    break;
	case "hr":
	    echo '<hr>';
	    break;
        case "h1":
	case "h2":
	case "h3":
	case "h4":
	case "h5":
	case "h6":
	    if(checkPara($node) === true)
	        echo $node->outertext;
	    break;
 	case "iframe":
	    parseSrc($node);
	    break;
    //in case of block
 	case "span":
	case "div":
	    if(checkDiv($node) === true) 
		parseNode($node->first_child());	    
 	    break;
        case "table":
	    //echo $node->outertext;
	    break;
	default:
	    break;
    }
    parseNode($node->next_sibling());
} 

//--------------parse an image tag
function parseSrc($img) {
    //chage the src into hostname + path if possible
    //echo $img->src;    
    global $url;
    if(strpos($img->src, "://") != false)
    {
	echo $img->outertext;
	return;
    } else {
	if(strcmp(substr($img->src, 0, 1), '/') == 0) {
	    //add host name to the src
	    $img->src = $protocol.$img->src;
	    echo $img->outertext;    
	}
	else {
	    //add the prarent
	    $img->src = substr($url, 0, strrpos($url, '/')).'/'.$img->src;
	    echo $img->outertext;
	}
    }
}

function checkList($list) {
    $f = 0;
    foreach($list->children() as $l) {
	if(checkPara($l) === false)
	    $f++;
    }
    return ($f < count($list->children()) * 0.5);
}

function checkPara($para) {
    if(is_array($para->children())) {
	//echo "has children";
	$sublen = 0;
//	echo $para->plaintext.'<br>';
  	foreach($para->children() as $chk) {
	    if(strcmp($chk->tag, "div")===0) return false;
	    if(strcmp($chk->tag, "iframe") === 0) return true;
	    if(strpos("label#a#input#button#textarea#", $chk->tag.'#') !== false || isset($chk->onclick)) {
//		echo str_replace(' ', '',$chk->plaintext).'#<br>';
		$sublen += strlen(str_replace(' ','',trim($chk->plaintext)));
	    }
	}	
//	echo str_replace(' ', '',$para->plaintext).'#<br>';
	//echo "len:".strlen(str_replace(' ','',$para->plaintext))." sublen: ".$sublen.'<br>';
	if(strlen(str_replace(' ','',trim($para->plaintext))) <= $sublen) return false;
	else return true;
    }
    return true;
}

function checkDiv($div) {
    if(isset($div->onclick)) {
	return false;
    }
    $r = true;
    foreach($div->children() as $e) {
	switch($e->tag) {
	    case "p":
		if(checkPara($e) === false) $r = false;
		break;
	    case "div":
		//nesty div, the parent one is true
		if(checkDiv($e) === false) $r = false;
		break;
	    case "a":
		$r = false;
		break;
	    case "script":
	    case "a":
		if(count($div->children) == 1) return false;
		break;
	    default:
		break;
	}
    }
    return true;
}

echo SUCCESS;
cleanUp();
function cleanUp() {
    global $html;
    $html->clear(); 
    unset($GLOBALS['paras']); 
    unset($html);
}
?>
