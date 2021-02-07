<?php require_once('Connections/StarCreations.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}
?>
<?php
// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

$loginFormAction = $_SERVER['PHP_SELF'];
if (isset($_GET['accesscheck'])) {
  $_SESSION['PrevUrl'] = $_GET['accesscheck'];
}

if (isset($_POST['username'])) {
  $loginUsername=TRIM($_POST['username']);
  $password=$_POST['password'];
  $MM_fldUserAuthorization = "Privilege";
  $MM_redirectLoginSuccess = "/";
  $MM_redirectLoginFailed = "/login.php?action=fail";
  $MM_redirecttoReferrer = false;
  mysql_select_db($database_StarCreations, $StarCreations);
  	
  $LoginRS__query=sprintf("SELECT EmailAddress, Password, concat(firstname,' ',lastname) as 'FullName', Privilege FROM Employees WHERE EmailAddress=%s AND Password= AES_ENCRYPT(%s,'@s5fs356g7$7&85e')",
  GetSQLValueString($loginUsername, "text"), GetSQLValueString($password, "text")); 
   
  $LoginRS = mysql_query($LoginRS__query, $StarCreations) or die(mysql_error());
  $loginFoundUser = mysql_num_rows($LoginRS);
  if ($loginFoundUser) {
	  
	$loginStrGroup  = mysql_result($LoginRS,0,'Privilege');
	$loginFullName  = mysql_result($LoginRS,0,'FullName');
	
///BEGIN REMEMBER///

        if (isset($_POST['remember'])) {
            /* Set cookie to last 1 year */
            setcookie('username', $_POST['username'], time()+60*60*24*365, '/', 'app.starcreationsinc.net');
            setcookie('password', md5($_POST['password']), time()+60*60*24*365, '/', 'app.starcreationsinc.net');
			setcookie('UserGroup', $loginStrGroup, time()+60*60*24*365, '/', 'app.starcreationsinc.net');
    	    setcookie('FullName', $loginFullName, time()+60*60*24*365, '/', 'app.starcreationsinc.net');
		
        } else {
            /* Cookie expires when browser closes */
            setcookie('username', $_POST['username'], false, '/', 'app.starcreationsinc.net');
            setcookie('password', md5($_POST['password']), false, '/', 'app.starcreationsinc.net');
			setcookie('UserGroup', $loginStrGroup, false, '/', 'app.starcreationsinc.net');
			setcookie('FullName', $loginFullName, false, '/', 'app.starcreationsinc.net');
        }

///END REMEMBER///
    
	if (PHP_VERSION >= 5.1) {session_regenerate_id(true);} else {session_regenerate_id();}
    //declare two session variables and assign them
    $_SESSION['MM_Username'] = $loginUsername;
    $_SESSION['MM_UserGroup'] = $loginStrGroup;	
	$_SESSION['MM_UserFullName'] = $loginFullName;	      

    if (isset($_SESSION['PrevUrl']) && false) {
      $MM_redirectLoginSuccess = $_SESSION['PrevUrl'];	
    }
    header("Location: " . $MM_redirectLoginSuccess );
  }
  else {
    header("Location: ". $MM_redirectLoginFailed );
  }
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
<link rel="stylesheet" type="text/css" href="css/normalize.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap.css"/>
<link rel="stylesheet" type="text/css" href="css/styles.css"> 
    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.js"></script>
    <![endif]-->
    
        <script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
        
    </head>
 <body>
<div class="container">
    <div class="navbar navbar-fixed-top navbar-inverse">
      <div class="navbar-inner">
        <div class="container">          
          <a class="brand" href="/">Star Creations</a>
        </div>
      </div>
    </div>
    <div class="row">
    <div class="span4 offset4">
    <div class="well">
    <legend>Sign in to Star Creations</legend>
    <form method="POST" action="<?php echo $loginFormAction; ?>" accept-charset="UTF-8">
    <?php if ($_GET['action']=="fail") { ?>
    <div class="alert alert-error">
    <a class="close" data-dismiss="alert" href="#">x</a>Incorrect Email or Password!
    </div>
    <?php }  ?>
    <input class="span3" placeholder="Email" type="text" name="username">
    <input class="span3" placeholder="Password" type="password" name="password">
    <label class="checkbox">
    <input type="checkbox" name="remember" value="1"> Remember Me
    </label>
    <button class="btn-info btn" type="submit">Login</button>
    </form>
    </div>
</div>
    </div>
<?php require_once('footer.php'); ?>