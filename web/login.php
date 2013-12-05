<?php
if(isset($_POST['username'])){
	$username = $_POST['username'];
	$password = $_POST['password'];

	$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
	mysql_select_db('stratosphere') or die('Could not select database');


	$query = "select * from User where username='$username' and password='$password'";
	$result = mysql_query($query) or die('Query failed:'.mysql_error());
       $row=mysql_fetch_row($result);
	if($row){ //Correct username and password
		//Set Cookie
		setcookie('userid', $row[0], time()+60*60*5); //Login valid for 5 hours
		setcookie('name', $row[3], time()+60*60*5); //Login valid for 5 hours
		header('Location: user.php');
	     //header("Location:user.html?uname=$name");
	     //echo "<meta http-equiv=refresh content=10;URL=http://ec2-67-202-55-42.compute-1.amazonaws.com/user.html>";

        }
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Login - Stratosphere</title>
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
		<!--[if lte IE 8]><link rel="stylesheet" href="css/ie8.css" /><![endif]-->
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
							<li><a href="index.php">Home</a></li>
                            <!--
							<li>
								<span>Dropdown</span>
								<ul>
									<li><a href="#">Lorem ipsum dolor</a></li>
									<li><a href="#">Magna phasellus</a></li>
									<li><a href="#">Etiam dolore nisl</a></li>
									<li>
										<span>And a submenu &hellip;</span>
										<ul>
											<li><a href="#">Lorem ipsum dolor</a></li>
											<li><a href="#">Phasellus consequat</a></li>
											<li><a href="#">Magna phasellus</a></li>
											<li><a href="#">Etiam dolore nisl</a></li>
										</ul>
									</li>
									<li><a href="#">Veroeros feugiat</a></li>
								</ul>
							</li>
                            -->
							<li><a href="signup.php">Sign Up</a></li>
							<li><a href="login.php">Login</a></li>
							<li><a href="user.php">My Stratosphere</a></li>
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
									<h2><a href="login.php">Login</a></h2>
                                   	
                                    <form action="login.php" method="post">
<?php if(isset($_POST['username'])) echo "<br/><p\"><b><font color=red>Wrong Username Or Password!</font></b></p>" ?>
                                    <p">

										Username:<br/>
                                        <input type="text" name="username" size="60" value="" /><br/>
										Password:<br/>
                                        <input type="password" name="password" size="60" value="" />
									</p>	
                                    <input class="button" type="submit" name="submit" value="Sign In" /><br/>
                                    </p>
								    Don't Have An Account?&nbsp;&nbsp;&nbsp;<a href="signup.php">Sign Up Now!</a>
                                </form>
									
								</header>
								
							</article>
						</div>
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