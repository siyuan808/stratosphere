<?php
if (!isset($_COOKIE['userid'])) {
	header('Location: login.php');
}
if (!isset($_GET['urlid'])){
	header('Location: user.php');
}

$urlid = $_GET['urlid'];
$userid = $_GET['uid'];
//$userid = $_COOKIE['userid'];
$pagetodisplay = file_get_contents('https://s3.amazonaws.com/ec2-67-202-55-42-stratos-userid-'.$userid.'/'.$urlid.'.html');
$title = '';
	//$username = $_POST['username'];
	//$password = $_POST['password'];
	//$name = $_POST['name'];
	//$email = $_POST['email'];

	$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
	mysql_select_db('stratosphere') or die('Could not select database');

	$query = "select * from Url where urlid='$urlid'";
	$result = mysql_query($query) or die('Query failed:'.mysql_error());
       $row=mysql_fetch_row($result);
	if($row){	//echo $row;	
		$title=$row[3];	
		$is_readable=$row[2];
		$urladdress=$row[1];
        }else {echo "No Matching Title";}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>My Stratosphere - Stratosphere</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600" rel="stylesheet" type="text/css" />
		<!--[if lte IE 8]><script src="js/html5shiv.js"></script><![endif]-->
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.dropotron.min.js"></script>
		<script src="js/skel.min.js"></script>
		<script src="js/skel-panels.min.js"></script>
		<script src="js/init.js"></script>
		<noscript>
			<link rel="stylesheet" href="css/skel-noscript.css" />
			<link rel="stylesheet" href="css/style.css" />
			<link rel="stylesheet" href="css/style-desktop.css" />
			<link rel="stylesheet" href="css/style-noscript.css" />
		</noscript>
		<script type="text/javascript">
		function submitform(tosubmit)
		{
		  document.getElementById(tosubmit).submit();
		}
		</script>
	</head>
	<body class="no-sidebar">

		<!-- Header -->
			<div id="header">

				<!-- Inner -->
					<div class="inner">
						<header>
							<h1><a href="index.php" id="logo">Stratosphere</a></h1>
						</header>
					</div>
				
				<!-- Nav -->
					<nav id="nav">
						<ul>
							<li>
								<span>Actions</span>
								<ul>
									<li><a href="add_url.php">Add URL</a></li>
                                    				<li><a href="sharedwithme.php">Shared With Me</a></li>
									<li> 
<form id="favorite" action="userfunction.php" method="POST">
<input type="hidden" name="urlid" value="<?php echo $urlid; ?>" />
<input type="hidden" name="action" value="Favorite" />
<a href="javascript: submitform('favorite')">Make Favorite</a>
</form>
									</li>
									<li> 
<form id="public" action="userfunction.php" method="POST">
<input type="hidden" name="urlid" value="<?php echo $urlid; ?>" />
<input type="hidden" name="action" value="Public" />
<a href="javascript: submitform('public')">Make Public</a>
</form>
									</li>
									<li> 
<form id="share" action="share.php" method="POST">
<input type="hidden" name="urlid" value="<?php echo $urlid; ?>" />
<input type="hidden" name="action" value="Share" />
<a href="javascript: submitform('share')">Share</a>
</form>
									</li>
									<li> 
<form id="delete" action="userfunction.php" method="POST">
<input type="hidden" name="urlid" value="<?php echo $urlid; ?>" />
<input type="hidden" name="action" value="Delete" />
<a href="javascript: submitform('delete')">Delete</a>
</form>
									</li>

                                    
                                   
                                   
                                    
                                    <!--
									<li>
										<span>Options</span>
										<ul>

											 <li><a href="share.php">Share</a></li>
										</ul>
									</li>
                                    -->
								</ul>
							</li>
                            <li><a href="friend.php">Friends</a></li>
                            <li><a href="user.php">My Stratosphere</a></li>
							<li><a href="logout.php">Log Out</a></li>
							
						</ul>
					</nav>

			</div>
			
		<!-- Main -->
			<div class="wrapper style1">

				<div class="container">
					<div class="row">
						<div class="12u skel-cell-important" id="content">
							<article id="main" class="special">
								<header>
									
								<?php
								    echo "<h2><a href=\"".$urladdress."\">".$title."</a></h2>";
									if( $is_readable == 1){
										echo "<section style=\"text-align:left\"><p>".$pagetodisplay."</p></section>";
									}else{
										echo"<br/><h3>Not readable. Redirecting you to the origianl page...</h3>";
										echo "<meta http-equiv=\"refresh\" content=\"2;url=".$urladdress."\">";
										
										}
									
								?>

							</article>
						</div>
					</div>

			</div>

		<!-- Footer -->
			<div id="footer">
				<div class="container">
					<div class="row">
						
						<!-- Tweets -->
							

						<!-- Posts -->
							

						<!-- Photos -->
							

					</div>
					
					<div class="row">
						<div class="12u">
							
							<!-- Contact -->
								<section class="contact">
									<header>
										<h3>Want to contact us?</h3>
									</header>
									<p>You can following us.</p>
									<ul class="icons">
										<li><a href="https://twitter.com/StratosphereUFL" class="fa fa-twitter solo"><span>Twitter</span></a></li>
										<li><a href="https://www.facebook.com/profile.php?id=100007212336523" class="fa fa-facebook solo"><span>Facebook</span></a></li>
										<li><a href="https://plus.google.com/u/0/112539309182150323730/posts" class="fa fa-google-plus solo"><span>Google+</span></a></li>
									</ul>
								</section>
							
							<!-- Copyright -->
								<div class="copyright">
									<ul class="menu">
										<li>&copy; 2013 Stratosphere. All rights reserved.</li>
										<li>Design: <a href="http://html5up.net/">HTML5 UP</a></li>
									</ul>
								</div>
							
						</div>
					
					</div>
				</div>
			</div>

	</body>
</html>