<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "1,2";
$MM_donotCheckaccess = "false";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && false) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) { 
	if (isset($_COOKIE['username']) && isset($_COOKIE['password']) && isset($_COOKIE['UserGroup']) && isset($_COOKIE['FullName'])) {
		$_SESSION['MM_Username'] = $_COOKIE['username'];
		$_SESSION['MM_UserGroup'] = $_COOKIE['UserGroup'];	
		$_SESSION['MM_UserFullName'] = $_COOKIE['FullName'];  
	}
}

$MM_restrictGoTo = "/login.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Star Creations Costing Application</title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- Le styles -->

    <style type="text/css">
      body {
        padding-top: 60px;
        padding-bottom: 40px;
      }
    </style>



    <!-- Fav and touch icons -->
<link rel="stylesheet" type="text/css" href="/css/normalize.css"/>
<link rel="stylesheet" type="text/css" href="/css/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="/css/jquery-ui.custom.css">
<link rel="stylesheet" type="text/css" href="/css/FB.css"/>
<link rel="stylesheet" type="text/css" href="/css/styles.css"> 
<link rel="stylesheet" type="text/css" href="/js/jtable/themes/basic/jtable_basic.min.css">
<link rel="stylesheet" type="text/css" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="/js/validationengine.css">
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
    
        <script src="//code.jquery.com/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
		<script src="//code.jquery.com/ui/1.10.2/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
        <script src="/js/tags/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="/js/bootstrap.min.js"></script>
		<script src="/js/jtable/jquery.jtable.js"></script>
        <script src="/js/validationengine.js"></script>
        <script src="/js/validationengine-en.js"></script>
		<script src="/js/tagsinput.js"></script>
        
    </head>
 <body>
    <div class="navbar navbar-fixed-top navbar-inverse">
      <div class="navbar-inner">
        <div class="container">          
          <a class="brand" href="/">Star Creations</a>
            <ul class="nav">
              <li><a href="/">Dashboard</a></li>
              <li class="dropdown"><a href="#Products"  class="dropdown-toggle" data-toggle="dropdown">Products<b class="caret"></b></a>
              <ul class="dropdown-menu">
              	  <li><a href="/products-list.php">List</a></li>
                  <li><a href="/products-add.php">Add</a></li>
                  <li class="divider"></li>
                  <li class="nav-header">Reports</li>
                  <li><a href="/reports/allproducts.php">All By Customer</a></li>
                  <li><a href="/reports/report_02.php">Meetings</a></li>
                </ul>
              </li>
              <li class="dropdown"><a href="#clients" class="dropdown-toggle" data-toggle="dropdown">Clients<b class="caret"></b></a>
              <ul class="dropdown-menu">
                  <li><a href="/clients-list.php">List</a></li>
                  <li class="divider"></li>
                </ul>
              </li>
              <?php if ($_SESSION['MM_UserGroup']=="2") { ?>
              <li class="dropdown"><a href="#Components" class="dropdown-toggle" data-toggle="dropdown">Components<b class="caret"></b></a>
              <ul class="dropdown-menu">
                  <li><a href="/components-list.php">List</a></li>
              </ul>
              </li>
              <? } ?>
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">Reports<b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li class="nav-header">Products</li>
                  <li><a href="/reports/allproducts.php">All By Customer</a></li>
                  <li><a href="/reports/report_02.php">Meetings</a></li>
                  
                </ul>
              </li>              
            </ul>
             <div class="pull-right">
<ul class="nav pull-right">
<li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Welcome, <?php echo $_SESSION['MM_UserFullName']; ?> <b class="caret"></b></a>
<ul class="dropdown-menu">
<?php if ($_SESSION['MM_UserGroup']=="2") { ?>
<li><a href="/users-list.php"><i class="icon-cog"></i> Manage Users</a></li>
<?php } else { ?>
<li><a href="/user-list.php"><i class="icon-cog"></i> Manage Account</a></li>
<?php }  ?>
<li><a href="mailto:support@edotsolutions.com?&cc=marco@edotsolutions.com&subject=Star Creations Application"><i class="icon-envelope"></i> Contact Support</a></li>
<li class="divider"></li>
<li><a href="/logout.php"><i class="icon-off"></i> Logout</a></li>
</ul>
</li>
</ul>
</div>
        </div>
      </div>
    </div>

    <div class="container">
