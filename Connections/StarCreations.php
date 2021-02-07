<?php
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"
$hostname_StarCreations = "localhost";
$database_StarCreations = "app_starcreations";
$username_StarCreations = "root";
$password_StarCreations = "";
//  $StarCreations = new mysqli("p:$hostname_StarCreations","$username_StarCreations","$password_StarCreations") or trigger_error(mysqli_error(),E_USER_ERROR);
$StarCreations = mysqli_connect("P:$hostname_StarCreations","$username_StarCreations","$password_StarCreations") or trigger_error("Error " . mysqli_error($StarCreations),E_USER_ERROR);

?>
