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
if (isset($_POST['pfilter'])) {
  $colname_plist = $_POST['pfilter'];
}	

$colname_plist2 = "";
if (isset($_POST['pfilter'])) {
  $colname_plist2 = $_POST['pfilter'];
}	

$colname_plist3 = "";
if (isset($_POST['pfilter'])) {
  $colname_plist3 = $_POST['pfilter'];
}	
	
mysql_select_db($database_StarCreations, $StarCreations);

$query_plist1 = sprintf("SELECT COUNT(*) AS RecordCount FROM Employees WHERE (FirstName LIKE %s) OR (LastName LIKE %s) OR (EmailAddress LIKE %s)", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"));
$plist1 = mysql_query($query_plist1, $StarCreations) or die(mysql_error());
$row_plist1 = mysql_fetch_assoc($plist1);


$query_plist2 = sprintf("SELECT ID, FirstName, LastName, EmailAddress, AES_DECRYPT(Password, '@s5fs356g7$7&85e') AS Password, Privilege, CreateDate, AlterDate FROM Employees WHERE (FirstName LIKE %s) OR (LastName LIKE %s)  OR (EmailAddress LIKE %s) ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"));
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
	
	if($_GET["action"] == "create")
	{


  $insertSQL = sprintf("INSERT INTO Employees (FirstName, LastName, EmailAddress, Password, Privilege, CreateDate) VALUES (%s, %s, %s, AES_ENCRYPT(%s,'@s5fs356g7$7&85e'), %s, now())",
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
					   GetSQLValueString($_POST['EmailAddress'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                        GetSQLValueString($_POST['Privilege'], "text"),
                       GetSQLValueString($_POST['CreateDate'], "date"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($insertSQL, $StarCreations) or die(mysql_error());
  
$result = mysql_query("SELECT ID, FirstName, LastName, EmailAddress, AES_DECRYPT(Password, '@s5fs356g7$7&85e') AS Password, Privilege, CreateDate, AlterDate FROM Employees WHERE ID = LAST_INSERT_ID();");
$row = mysql_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);
		

	}
		if($_GET["action"] == "update")
	{
		  $updateSQL = sprintf("UPDATE Employees SET  FirstName=%s, LastName=%s, EmailAddress=%s, Password=AES_ENCRYPT(%s,'@s5fs356g7$7&85e'), Privilege=%s WHERE ID=%s",
                       GetSQLValueString($_POST['FirstName'], "text"),
                       GetSQLValueString($_POST['LastName'], "text"),
					   GetSQLValueString($_POST['EmailAddress'], "text"),
                       GetSQLValueString($_POST['Password'], "text"),
                        GetSQLValueString($_POST['Privilege'], "text"),
                       GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($updateSQL, $StarCreations) or die(mysql_error());


		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
		
	}
		if($_GET["action"] == "delete")
	{
		
  $deleteSQL = sprintf("DELETE FROM Employees WHERE ID=%s", GetSQLValueString($_POST['ID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $result = mysql_query($deleteSQL, $StarCreations) or die(mysql_error());
  
		//Return result to jTable
$jTableResult = array();
$jTableResult['Result'] = "OK";
print json_encode($jTableResult);
	}
?>
