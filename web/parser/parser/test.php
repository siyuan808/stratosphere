<?php
include 'simple_html_dom.php';
ini_set('display_errors',1);
error_reporting(E_ALL);

//$userid = $_GET['userid'];
#echo $userid;
//$url = $_GET['url'];
//$html = file_get_html($url);
//echo $html->plaintext;
// Find all images 
//foreach($html->find('img') as $element) 
//       echo $element->src . '<br>';
//echo $html->size. '<br>';
//echo $html->original_size;

//$t[] = array(100, 10, 9);
//for($i = 0; $i < 5; $i++) 
//    $t[] = $i;
//var_dump($t);

//foreach($html->find('p') as $div) {
//    $div->dump_node();
//    echo $div->makeup();
//    var_dump($div->_);
//    break;
//    echo $div->innertext . '<br>';
//}

//echo "Traversal DOM Tree <br>";
//echo $html->root->outertext;
//traversal all the tags
//traversal($html->root)
//function traversal($node) {
    //if(isset($node)) 
//	echo $node->outertext;
    //else 
//	return;
    //foreach($node->children() as $child)
    //	traversal($child);
//}

//echo '<img src="facebook.jpg">';
// Find all links 
//foreach($html->find('a') as $element) 
//       echo $element->href . '<br>';
//$exe = shell_exec('ls -la');
//$exe = `ls -la`;
//echo "<pre>$exe</pre>";
//echo __FILE__."\n";
//$filename = "tmp/test.txt";
//echo $filename;

//file_put_contents($filename, 'hello world');

//if(file_exists($filename)) 
//    echo "file exist";
//else echo "file not exist";

//$e = `touch test.txt`;
//$e = `chmod 777 test.txt`;
$filename = "tmp/test.html";
$file = fopen( $filename, "w" );

if( $file == false )
{
   echo ( "Error in opening new file" );
   exit();
}

fwrite( $file, "<h1>test</h1>");
fwrite( $file, "<title>TEST FILE</title>");
fwrite($file, "hello");
fclose( $file );
#identify whether this page is an article or not
//$html->clear(); 
//unset($html);
echo 200;
?>
