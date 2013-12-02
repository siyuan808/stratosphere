<?php
if(isset($_POST['username'])){
	$username = $_POST['username'];
	$password = $_POST['password'];
	$name = $_POST['name'];
	$email = $_POST['email'];

	$dbhandle = mysql_connect("stratosinstance.cq9eo0agv4tp.us-west-2.rds.amazonaws.com", "stratos", "stratoscloud") or die("Unable to connect to MySQL");
	mysql_select_db('stratosphere') or die('Could not select database');

	$query = "select * from User where username='$username'";
	$result = mysql_query($query) or die('Query failed:'.mysql_error());
       $row=mysql_fetch_row($result);
	if(!$row){ //Valid username and password
		
		//Find Max User ID
		$max_query = "select max(uid) from User";
		$max_result = mysql_query($max_query) or die('Query failed:'.mysql_error());
		$max_row=mysql_fetch_row($max_result);
		
		//New ID = MAX ID + 1;
		$new_id = intval($max_row[0])+1;
		
		//Insert New User Info
		$insert_query = "INSERT INTO `stratosphere`.`User` (`uid`, `username`, `password`, `name`, `email`) VALUES ('$new_id', '$username', '$password', '$name', '$email')";
		mysql_query($insert_query) or die('Query failed:'.mysql_error());
		
		//Set Cookie
		setcookie('userid', $new_id, time()+60*60);
		setcookie('name', $name, time()+60*60);
		header('Location: user.php');
	     //header("Location:user.html?uname=$name");
	     //echo "<meta http-equiv=refresh content=10;URL=http://ec2-67-202-55-42.compute-1.amazonaws.com/user.html>";

        }
}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>Sign Up - Stratosphere</title>
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
									<h2><a href="#">Sign Up</a></h2>
                                    <form action="signup.php" method="post"> 
<?php if(isset($_POST['username'])) echo "<br/><p\"><b><font color=red>Username Already Exists!</font></b></p>" ?>
                                    
										Username*:<br/>
                                        <input type="text" name="username" size="60" value="" /><br/>
                                        </p>
										Password*:<br/>
                                        <input type="password" name="password" size="60" value="" /><br/>
                                        </p>
								Name*:<br/>
                                         <input type="text" name="name" size="60" value="" /><br/>
									</p>
                                        Email*:<br/>
                                         <input type="text" name="email" size="60" value="" /><br/>
									</p>	
                                    <input class="button" type="submit" name="submit" value="Creat Account" /><br/>
                                    </p>
								   Already have an account?&nbsp;&nbsp;&nbsp;<a href="login.php">Login!</a>
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
										<li><a href="#" class="fa fa-twitter solo"><span>Twitter</span></a></li>
										<li><a href="#" class="fa fa-facebook solo"><span>Facebook</span></a></li>
										<li><a href="#" class="fa fa-google-plus solo"><span>Google+</span></a></li>
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