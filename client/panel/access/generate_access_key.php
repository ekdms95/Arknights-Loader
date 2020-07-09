<?php
include_once("../structure.php");
if (!isset($_SESSION))
{
    session_start();
}
if (!$_SESSION["auth"])
{
    header("Location: authorization.php", true, 301);
    die();
}
if ($_SESSION["job"] != $_SESSION["admin"])
{
    header("Location: index.php", true, 301);
    die();    
}
?>

<!doctype html>
<html lang="en" dir="ltr">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
		<meta http-equiv="X-UA-Compatible" content="ie=edge">
		<meta http-equiv="Content-Language" content="en" />
		<meta name="msapplication-TileColor" content="#2d89ef">
		<meta name="theme-color" content="#4188c9">
		<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		
		<link rel="icon" href="../style/img/iconsite.ico" type="image/x-icon"/>
		<link rel="shortcut icon" type="image/x-icon" href="/assets/images/favicon.ico" />
		
		
		<title>Generation access keys</title>
		
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,300i,400,400i,500,500i,600,600i,700,700i&amp;subset=latin-ext">
		
		<!-- Styles Core -->
		<link href="style/css/dashboard.css" rel="stylesheet" />
    	<link id="color_scheme" href="style/css/home.css" rel="stylesheet">
	    <link href="style/css/themecss/so_megamenu.css" rel="stylesheet">
    	<link href="style/css/themecss/so-categories.css" rel="stylesheet">
    	<link href="style/css/themecss/so-listing-tabs.css" rel="stylesheet">
    	<link href="style/css/header.css" rel="stylesheet">
    	<!-- Styles libs -->
        <link href="style/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="style/js/owl-carousel/assets/owl.carousel.css" rel="stylesheet">
        <link href="style/js/owl-carousel/assets/owl.theme.default.min.css" rel="stylesheet">
        <link href="style/css/themecss/lib.css" rel="stylesheet">
        <link href="style/js/jquery-ui/jquery-ui.min.css" rel="stylesheet">
		
		<!-- Script Core -->
        <script type="text/javascript" src="style/js/jquery-2.2.4.min.js"></script>
        <script type="text/javascript" src="style/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="style/js/owl-carousel/owl.carousel.js"></script>
        <script type="text/javascript" src="style/js/themejs/libs.js"></script>
        <script type="text/javascript" src="style/js/unveil/jquery.unveil.js"></script>
        <script type="text/javascript" src="style/js/countdown/jquery.countdown.min.js"></script>
        <script type="text/javascript" src="style/js/dcjqaccordion/jquery.dcjqaccordion.2.8.min.js"></script>
        <script type="text/javascript" src="style/js/datetimepicker/moment.js"></script>
        <script type="text/javascript" src="style/js/datetimepicker/bootstrap-datetimepicker.min.js"></script>
        <script type="text/javascript" src="style/js/jquery-ui/jquery-ui.min.js"></script>
        <script type="text/javascript" src="style/js/modernizr/modernizr-2.6.2.min.js"></script>
        
        <script type="text/javascript" src="style/js/themejs/application.js"></script>
        <script type="text/javascript" src="style/js/themejs/homepage.js"></script>
        <script type="text/javascript" src="style/js/themejs/so_megamenu.js"></script>
        <script type="text/javascript" src="style/js/themejs/pathLoader.js"></script>	
        <script type="text/javascript" src="style/js/themejs/cpanel.js"></script>
        <link href="style/js/minicolors/miniColors.css" rel="stylesheet">
        <script type="text/javascript" src="style/js/minicolors/jquery.miniColors.min.js"></script>
        <script type="text/javascript" src="style/js/particles/particle.js"></script>
        <script type="text/javascript" src="style/js/particles/app.js"></script>
        <!-- Style in main -->
        <style>
        #particles-js 
        {
            position: absolute;
            width: 100%;
            height: 100%;
          }
      </style>
</head>	
<body>
    <div id="particles-js"></div>
	<div class="page">
		<div class="page-single">
			<div class="container">
				<div class="row">
					<div class="card">
					    <button type="submit" onClick='location.href="index.php"' class="btn btn-danger btn-block">Go to main</button>
						<div class="card-body p-6">
						    <form name="input" action="" method="post">
						        <center><div class="card-title">Generation access keys</div></center>
            					<div class="spacing"></div>
								<center><div class='text-muted'> Job <br/></div></center>
								<div class="spacing"></div>            					
								<div class="form-group">
                					<select style="text-align: center; text-align-last: center;" name="job" class="form-control">
                					    <option style="text-align: left;" class="form-control"> admin </option>
                					    <option style="text-align: left;" class="form-control"> reseller </option>
                					</select>
                				</div>
								<div class="form-footer">
									<button type="submit" name="sub" class="btn btn-danger btn-block">Generate</button>
								</div>										
					        </form>
                        </div>
					</div>
						<div class="card">
							<div class="card-body p-12">	
					            <center><div class="card-title">Generated access keys</div></center>
                                <div class="spacing"></div>								
						        <?php 
                                    if (isset($_POST["job"])) 
                                    {
                                        $generated_access_key = $panel_admin::generate_access_key($_POST["job"]);
                                        echo "<center><div class='text-muted'>1 . $generated_access_key \n</div></center>";
                                    }
						        ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>