<?php
include 'simple_html_dom.php';
ini_set('display_errors',1);
error_reporting(E_ALL);

define('SUCCESS', 200);
define('EMPTY_PARAMETER', 201);

if(!isset($_GET['userid']) || !isset($_GET['url']))
{
    echo EMPTY_PARAMETER;
    die();
}

$userid = $_GET['userid'];
#echo $userid;
$url = $_GET['url'];

//get host name
$host_name = get_host_name($url);
echo $host_name.'<br>';

function get_host_name($url) {
    // Extract the host name from the usrl
    $start = strpos($url, '://');
    if($start === false) $start = 0;
    else $start += 3;

    $end = strpos($url, '/', $start);
    if($end === false) 
 	return substr($url, $start);
    else 
	return substr($url, $start, $end-$start);
}

$html = file_get_html($url);

$title = $html->getElementByTagName('title')->innertext;
//echo $title;

foreach($html->find('p') as $p) 
    echo $p->innertext;

function is_article($html) {
//detect whether this page is an article or not

}
// Get first p
$firstP = $html->find('p', 0);
$node = $firstP->prev_sibling();
while(isset($node)) {
    echo $node->tag;
    $node = $node->prev_sibling();
}
echo $firstP->innertext;

// start from the firstP or start from the previous h 

$html->clear(); 
unset($html);
echo SUCCESS;
?>
