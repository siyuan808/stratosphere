<?php
include 'simple_html_dom.php';
ini_set('display_errors',1);
error_reporting(E_ALL);

$t = microtime();

$userid = $_GET['userid'];
#echo $userid;
$url = $_GET['url'];
$html = file_get_html($url);
//echo $html->plaintext;
// Find all images 
//foreach($html->find('img') as $element) 
//       echo $element->src . '<br>';

foreach($html->find('p') as $div)
    echo $div->innertext . '<br>';

echo microtime() - $t;
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
//$file = fopen( $filename, "w" );

//if( $file == false )
//{
//   echo ( "Error in opening new file" );
//   exit();
//}
//fwrite( $file, "This is a test\n");
//fclose( $file );
#identify whether this page is an article or not
$html->clear(); 
unset($html);
?>
