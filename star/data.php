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
mysql_select_db($database_StarCreations, $StarCreations);
$query_rsCustomer = "SELECT * FROM Customers ORDER BY Company ASC";
$rsCustomer = mysql_query($query_rsCustomer, $StarCreations) or die(mysql_error());
$row_rsCustomer = mysql_fetch_assoc($rsCustomer);
$totalRows_rsCustomer = mysql_num_rows($rsCustomer);
?>

<?php 
$json = array('key' => $row_rsCustomer['CustomersID'], 
              'row' => $row_rsCustomer['Company']);
echo json_encode($json);

?>