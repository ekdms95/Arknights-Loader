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
		
		
		<title>Information subscribe</title>
		
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
				    <?php
					if (!isset($_POST["license_key"]))
					{				    
				       echo '<div class="col col-login mx-auto">';
					}
					else
					{
					    echo '<div class="banneragreement">';
					}
				    ?>
						<div class="card">
						    <button type="submit" onClick='location.href="index.php"' class="btn btn-danger btn-block">Go to main</button>
							<div class="card-body p-6">
							    <form name="input" action="" method="post">
							        <center><div class="card-title">Information subscribe</div></center>
									<div class="spacing"></div>
									<?php 
									if (isset($_POST["license_key"]) && $_SESSION["job"] == $_SESSION["admin"])
									{
									    $get_info = $panel_admin::get_data_subscribe($_POST["license_key"]);
									}
									else if (isset($_POST["license_key"]) && $_SESSION["job"] == $_SESSION["reseller"])
									{
									    $get_info = $panel_reseller::get_data_subscribe($_POST["license_key"], $_SESSION["user"]);
									}
									
									if (!isset($_POST["license_key"]))
									{
    									echo '<div class="spacing"></div>
    									<center><div class="text-muted"> License key <br/></div></center>
    									<div class="form-group">
                        					<select style="text-align: center; text-align-last: center;" name="license_key" class="form-control">';
                        					    $mysqli = mysqli_connect(HOST, USER, PASS, DB);
                        					    $user = $_SESSION["user"];
                        					    
                                                if ($_SESSION['admin'] == true)
                        					    {
                        					        $sql = "SELECT * FROM license_keys";
                        					    }                        					    
                        					    else if ($_SESSION['reseller'] == true) 
                        					    {
                        						    $sql = "SELECT * FROM license_keys WHERE seller = '$user'";
                        					    }
                        						$result = mysqli_query($mysqli,$sql);
                        						if ($result->num_rows > 0)
                        						{
                        							while($row = $result->fetch_assoc()) 
                        							{
                        								if ($row["license_key"] != null) 
                        								{
                        									echo '<option style="text-align: left;" class="form-control">'. $row["license_key"] . "</option>";
                        								}
                        							}
                        						}
                        						else
                        						{
                        							echo '<option style="text-align: left;" class="form-control"> No license keys found. </option>';
                        						}
                        					echo '</select>                    					
                        				</div>
                    					<div class="spacing"></div>
                    					
                                        <div class="form-footer">
                                            <button type="submit" name="sub" class="btn btn-danger btn-block">Get information</button>
                                        </div>	';
                                        
									}
									else if (isset($_POST["license_key"]) && $get_info == false)
									{
									    echo "<center><div class='text-muted'>Ooops.. It's not your key, return to main.<br/></div></center>";
									}									
									else
									{
                                        $explode_getted_info = explode(";", $get_info);
                                        
                                        for ($i = 1; $i < 4; $i++)
                                        {
                                            if ($explode_getted_info[$i] == null)
                                            {
                                                $explode_getted_info[$i] = "Not activated";
                                            }
                                        }

                                        echo "<center><div class='text-muted'>License key: $explode_getted_info[0]<br/></div></center>";
                                        echo "<div class='spacing'></div>";
                                        echo "<center><div class='text-muted'>Cheat: $explode_getted_info[1]<br/></div></center>";	
                                        echo "<div class='spacing'></div>";
                                        echo "<center><div class='text-muted'>End: $explode_getted_info[2]<br/></div></center>";	
                                        echo "<div class='spacing'></div>";
                                        echo "<center><div class='text-muted'>Banned: $explode_getted_info[3]<br/></div></center>";
                                        
                                        if ($_SESSION["job"] == $_SESSION["admin"])
                                        {
                                            $launch_log = $panel_admin::get_launch_log($explode_getted_info[0]);
                                            
                                            $explode_getted_launch_log = explode(";", $launch_log);
                                            
                                            for ($i = 0; $i < 2; $i++)
                                            {
                                                if ($explode_getted_launch_log[$i] == null)
                                                {
                                                    $explode_getted_launch_log[$i] = "Not launched";
                                                }
                                            }                                            
                                            
                                            echo "<div class='spacing'></div>";                                            
                                            echo '<center><div class="card-title">Information last launch</div></center>';
                                            echo "<div class='spacing'></div>";
                                            echo "<center><div class='text-muted'>Date: $explode_getted_launch_log[0]<br/></div></center>";
                                            echo "<center><div class='text-muted'>IP: $explode_getted_launch_log[1]<br/></div></center>"; 
                                        }
                                        echo '<center><div class="form-footer"> <button type="submit" class="btn btn-danger" style="width: 250px;">Reset data</button></div></center>';                                        
									}
									?>
						        </form>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>