<?php
include 'simple_html_dom.php';
include 'return_code.php';
if (!class_exists('S3')) require_once 'S3.php';

if(!isset($_COOKIE['userid'])) { header('Location: ../../login.php'); }

if(!isset($_GET['url'])){ die(''.FAIL.'No GET Received'); }

function upload_to_s3($urlid, $userid) {
    // AWS access info
    if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAIXSCBXNJH42EW6PQ');
    if (!defined('awsSecretKey')) define('awsSecretKey', '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');

    $uploadFile = "tmp/$urlid.html"; // File to save
    $bucketName = "ec2-67-202-55-42-stratos-userid-$userid"; // Bucket name

    // Check if our upload file exists
    if (!file_exists($uploadFile) || !is_file($uploadFile)) die(''.S3_ERROR);

    // Instantiate the s3 class
    $s3 = new S3(awsAccessKey, awsSecretKey);

    // Create bucket with public read access
    $s3->putBucket($bucketName, S3::ACL_PUBLIC_READ);
	
    // Save file with public read access
    $s3->putObjectFile($uploadFile, $bucketName, baseName($uploadFile), S3::ACL_PUBLIC_READ);
}


ini_set('display_errors',1);
error_reporting(E_ALL);

//define('DEBUG', 0);
define('FILE', 1);

$userid = $_COOKIE['userid'];
$url = $_GET['url'];
//-------------------------------------------------Connect to the database
$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") 
		or die(''.DB_ERROR.mysql_error());
mysql_select_db('stratosphere') or die(''.DB_ERROR.mysql_error());
$max_query = "select max(urlid) from Url";
$max_result = mysql_query($max_query) or die(''.DB_ERROR.mysql_error());
$max_row=mysql_fetch_row($max_result);
                            
//Insert New User Info
				
$q_urldup = "select * from Url where url = '$url'";
$r_urldup = mysql_query($q_urldup) or die('Query failed:'.mysql_error());

if(mysql_num_rows($r_urldup)!=0){
$row_urldup = mysql_fetch_row($r_urldup);
$urlid = $row_urldup[0];

$q_userdup = "select * from Store where urlid = '$urlid' and uid = '$userid'";
$r_userdup = mysql_query($q_userdup) or die('Query failed:'.mysql_error());

if(mysql_num_rows($r_userdup)==0){	
$insertstore = "INSERT INTO Store (uid, urlid, time, is_public, favorite) values ('$userid','$urlid', now(), 0,0);";
mysql_query($insertstore) or die('first insert'.DB_ERROR.mysql_error());
	}
}else{
//New ID = MAX ID + 1;
$urlid = intval($max_row[0])+1;

$insert_url = "INSERT INTO Url (urlid, url,is_readable) VALUES ('$urlid','$url',0);";
mysql_query($insert_url) or die(''.DB_ERROR.mysql_error());

$insert_store = "INSERT INTO Store (uid, urlid, time, is_public, favorite) values ('$userid','$urlid', now(), 0,0);";
mysql_query($insert_store) or die(''.DB_ERROR.mysql_error());
}

// --------------------------------------------Create file
$file = false;
$filename = "";
function create_file() {
    global $file,$urlid, $filename;
    $filename = "tmp/$urlid.html";
    $file = fopen($filename, "w");
    if($file === false) {
        die(''.FAIL);
    }
}

function output($msg) {
    global $file;
    if(defined('DEBUG'))
	echo $msg;
    else if(defined('FILE')) {
	fwrite($file, $msg);	
    }
}
//--------------------------------------------------get host name
$host_name = get_host_name($url);
$protocol;
//debug($host_name.'<br>');

function get_host_name($url) {
    // Extract the host name from the usrl
    global $protocol;
    $start = strpos($url, '://');
    if($start === false) {
        die(''.NOT_VALID_URL);
    }
    $protocol = substr($url, 0, $start+3);
    $start += 3;
    $end = strpos($url, '/', $start);
    if($end === false) 
 	return substr($url, $start);
    else 
	return substr($url, $start, $end-$start);
}

$url = htmlspecialchars_decode($url);
echo $url;
$html = file_get_html($url);
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
   cleanUp(); 
   die(''.SUCCESS); 
} else {
    create_file();
}

//detect whether this page is an article or not
function is_article() {
    global $paras;
    if(!is_array($paras) || count($paras) <= 0) return false;
    else {
   	$validPara = 0;
	foreach($paras as $p) {
	    // echo $p->innertext.'<br>';
	    if(isset($p->innertext) && strlen(trim($p->innertext)) > 50) {
		if(strlen(trim($p->innertext)) > 500)
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
//echo $title;
//echo $startNode->outertext;
// Get first start tag, from which to start parsing.
function isMatchTitle($h, $t) {
   if(!isset($h)) return false;
   $hwords = explode(' ', $h); 
   $match = 0;
   if(strlen($h) <= strlen($t) * 0.3) return false;
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
        echo $node->tag;
        $node = $node->prev_sibling();
    }*/
    $hs = $html->find('h1,h2,h3,h4,h5,h6');
    //echo $titleTagText.'<br>';
    $titleTagText = utf8_encode($titleTagText);
    if(is_array($hs)) {
        foreach($hs as $h) {
	    $h->plaintext = utf8_encode($h->plaintext);
	    //echo $h->plaintext."<br>".$titleTagText."<br><br>";
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
	output($paras[0]->outertext);
	return $paras[0];
    }
    else {
	//There is no h tags
	output($paras[0]->outertext);
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
		output($node->outertext);
	    break;
	case "p":
	    if(checkPara($node) === true)
		output($node->outertext);
	    break;
	case "pre":
 	    output($node->outertext);
	    break;
	case "img":
	    parseSrc($node);
	    break;
	//case "hr":
	//    output('<hr>');
	//    break;
        case "h1":
	case "h2":
	case "h3":
	case "h4":
	case "h5":
	case "h6":
	    if(checkPara($node) === true)
	        output('<h3>'.$node->innertext.'</h3>');
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
	    parseTable($node);
	    break;
	default:
	    break;
    }
    parseNode($node->next_sibling());
} 

//--------------parse an image tag
function parseTable($node) {
    //echo $node->outertext;
    foreach($node->children() as $tr) {
	foreach($tr->children() as $td) {
	    //if(checkDiv($td) === true) 
	    //	output('<p>'.$td->innertext.'</p>');
	}
    } 
}
function parseSrc($img) {
    //chage the src into hostname + path if possible
    //echo $img->src;    
    global $url,$protocol,$host_name;
    if(strpos($img->src, "://") != false)
    {
	output($img->outertext);
	return;
    } else {
	if(strcmp(substr($img->src, 0, 1), '/') == 0) {
	    //add host name to the src
//	    echo $procotol;
	    $img->src = $protocol.$host_name.$img->src;
//            echo "!!!!".$img->outertext."!!!";
	    output($img->outertext);    
	}
	else {
	    //add the prarent
	    $img->src = substr($url, 0, strrpos($url, '/')).'/'.$img->src;
	    output($img->outertext);
	}
    }
    output('<br>');
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
    $r = 0;
    $cnt = 0;
    foreach($div->children() as $e) {
	switch($e->tag) {
	    case "p":
		if(checkPara($e) === false) $r++;
		$cnt++;
		break;
	    case "div":
		//nesty div, the parent one is true
		if(checkDiv($e) === false) $r++;
		$cnt+= count($e->children());
		break;
	    //case "a":
	    //	$r++;
	    //	break;
	    case "script":
	    case "a":
		if($cnt == 1) return false;
		else $r++;
		$cnt++;
		break;
	    default:
		break;
	}
    }
    if($r >= $cnt) return false;
    return true;
}

echo SUCCESS;
cleanUp();
function cleanUp() {
    global $filename,$html,$file,$title,$urlid, $userid;
    $html->clear(); 

    //add title to db 
    $update_title = "UPDATE Url set title='".mysql_real_escape_string($title)."' where urlid=$urlid";
    mysql_query($update_title) or die(''.DB_ERROR.mysql_error());
    if($file !== false) 
    {
	fclose($file);
        //update the db.Ur
        if(filesize($filename) > 1000) {
	    $update_url = "UPDATE Url set is_readable=1 where urlid=$urlid;";
            mysql_query($update_url) or die(''.DB_ERROR.mysql_error());
	    //upload to s3
            upload_to_s3($urlid, $userid);
	}
	//delete it from server
	unlink("tmp/$urlid.html");
    }
    if(!isset($_GET['extension'])) {header('Location: ../../user.php');} else { header('location: ../../extensiondisplay.php'); }
    unset($GLOBALS['paras']); 
    unset($html);
}
?>
