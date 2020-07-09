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
		
		
		<title>SECRETSENSE - Generation keys</title>
		
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
							        <center><div class="card-title">Generation keys</div></center>
									<div class="spacing"></div>
									<center><div class='text-muted'> Cheat <br/></div></center>
									<div class="form-group">
                    					<select style="text-align: center; text-align-last: center;" name="cheat" class="form-control">
                        					<?php
                        					    $mysqli = mysqli_connect(HOST, USER, PASS, DB);
                        						$sql = "SELECT * FROM cheats";
                        						$result = mysqli_query($mysqli,$sql);
                        						if ($result->num_rows > 0)
                        						{
                        							while($row = $result->fetch_assoc()) 
                        							{
                        								if ($row["name"] != null) 
                        								{
                        									echo '<option style="text-align: left;" class="form-control">'.$row["name"] . "</option>";
                        								}
                        							}
                        						}
                        						else
                        						{
                        							echo '<option style="text-align: left;" class="form-control"> No cheat found. </option>';
                        						}
                        					?>
                    					</select>                    					
                    				</div>
                					<div class="spacing"></div>	
									<center><div class='text-muted'> Days <br/></div></center>
									<div class="form-group">
										<input type="text" name="days" id="days" class="form-control days" placeholder="Days">
									</div>
									<div class="spacing"></div>
									<center><div class='text-muted'> Seller <br/></div></center>
									<div class="form-group">
                    					<select style="text-align: center; text-align-last: center;" name="seller" class="form-control">
                        					<?php
                        					    $mysqli = mysqli_connect(HOST, USER, PASS, DB);
                        						$sql = "SELECT * FROM panel";
                        						$result = mysqli_query($mysqli,$sql);
                        						if ($result->num_rows > 0)
                        						{
                        							while($row = $result->fetch_assoc()) 
                        							{
                        								if ($row["login"] != null) 
                        								{
                        									echo '<option style="text-align: left;" class="form-control">' . $row["login"] . " - " . $row["job"] . "</option>";
                        									$seller = $row['job']; //pcode :(
                        									$_POST["seller"] = str_replace(" - $seller", "" , $_POST["seller"]);
                        								}
                        							}
                        						}
                        						else
                        						{
                        							echo '<option style="text-align: left;" class="form-control"> No resellers found. </option>';
                        						}
                        					?>
                    					</select>                    					
                    				</div>
                					<div class="spacing"></div>
									<center><div class='text-muted'> Ammount keys <br/></div></center>
									<div class="form-group">
										<input type="text" name="ammount_keys" id="ammount_keys" class="form-control ammount_keys" placeholder="Ammount keys">
									</div>	
									<div class="form-footer">
										<button type="submit" name="sub" class="btn btn-danger btn-block">Generate</button>
									</div>									
						        </form>
                            </div>
						</div>
    					<div class="card">
    						<div class="card-body p-6">	
        				        <center><div class="card-title">Generated keys</div></center>
            					<div class="spacing"></div>								
    					        <?php 
                                    if (isset($_POST["cheat"]) && isset($_POST["days"]) && isset($_POST["seller"]) && isset($_POST["ammount_keys"])) 
                                    {
                                        if ($_POST["ammount_keys"] == null)
                                            die("<center><div class='text-muted'>Ooops.. Please enter correct ammount keys</div></center>");   
                                            
                                        for ($number_key = 1; $number_key <= $_POST["ammount_keys"]; $number_key++) 
                                        {
                                            if ($_POST["days"] == null)
                                                die("<center><div class='text-muted'>Ooops.. Please enter correct subscribe days</div></center>");
                                            
                                            $calculate_time_to_days = $_POST["days"] * 24;
                                            $accept_format_time_to_days = $calculate_time_to_days . ":00" . ":00";
                                            $generated_keys = $panel_admin::generate_license_keys($_POST["cheat"], $accept_format_time_to_days, $_POST["seller"]);
                                            
                                            echo "<center><div class='text-muted'>$number_key . $generated_keys \n</div></center>";
                                        }                                    
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