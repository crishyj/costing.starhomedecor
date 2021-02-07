<?php
if (!isset($_SESSION)) {
  session_start();
} ?>

<?php require_once('../Connections/StarCreations.php'); ?>

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
	if($_GET["action"] == "list")
	{
		
$colname_plist = "";
if (isset($_SESSION['MM_Username'])) {
  $colname_plist = $_SESSION['MM_Username'];
}	
	
mysql_select_db($database_StarCreations, $StarCreations);

$query_plist1 = sprintf("SELECT COUNT(*) AS RecordCount FROM Employees WHERE (EmailAddress = %s)", GetSQLValueString("%" . $colname_plist . "%", "text"));
$plist1 = mysql_query($query_plist1, $StarCreations) or die(mysql_error());
$row_plist1 = mysql_fetch_assoc($plist1);


$query_plist2 = sprintf("SELECT ID, FirstName, LastName, EmailAddress, AES_DECRYPT(Password, '@s5fs356g7$7&85e') AS Password, Privilege, CreateDate, AlterDate FROM Employees WHERE  (EmailAddress LIKE %s) ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "", GetSQLValueString("%" . $colname_plist . "%", "text"));
$plist2 = mysql_query($query_plist2, $StarCreations) or die(mysql_error());

$totalRows_plist2 = mysql_num_rows($plist2);


$rows = array();
while($row = mysql_fetch_array($plist2))
{
    $rows[] = $row;
}
 
//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['TotalRecordCount'] = $row_plist1['RecordCount'];
		$jTableResult['Records'] = $rows;
		print json_encode($jTableResult);
	}
	
		if($_GET["action"] == "update")
	{
		  $updateSQL = sprintf("UPDATE Employees SET  FirstName=%s, LastName=%s, Password=AES_ENCRYPT(%s,'@s5fs356g7$7&85e'), WHERE ID=%s",
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($updateSQL, $StarCreations) or die(mysql_error());


		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
		
	}
?>
