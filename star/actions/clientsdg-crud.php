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
	
mysql_select_db($database_StarCreations, $StarCreations);

$query_plist1 = sprintf("SELECT COUNT(*) AS RecordCount FROM Customers WHERE (Company LIKE %s)", GetSQLValueString("%" . $colname_plist . "%", "text"));
$plist1 = mysql_query($query_plist1, $StarCreations) or die(mysql_error());
$row_plist1 = mysql_fetch_assoc($plist1);


$query_plist2 = sprintf("SELECT * FROM Customers WHERE (Company LIKE %s) ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "", GetSQLValueString("%" . $colname_plist . "%", "text"));
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


  $insertSQL = sprintf("INSERT INTO Customers (Company, EmailAddress, BusinessPhone, FaxNumber, Address, City, StateProvince, ZIPPostal, WebPage, Notes, CreateDate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, now())",
                       GetSQLValueString($_POST['Company'], "text"),
					   GetSQLValueString($_POST['EmailAddress'], "text"),
                       GetSQLValueString($_POST['BusinessPhone'], "text"),
					   GetSQLValueString($_POST['FaxNumber'], "text"),
					   GetSQLValueString($_POST['Address'], "text"),
					   GetSQLValueString($_POST['City'], "text"),
					   GetSQLValueString($_POST['StateProvince'], "text"),
                       GetSQLValueString($_POST['ZIPPostal'], "text"),
					   GetSQLValueString($_POST['WebPage'], "text"),
					   GetSQLValueString($_POST['Notes'], "text"),
                       GetSQLValueString($_POST['CreateDate'], "date"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($insertSQL, $StarCreations) or die(mysql_error());
  
$result = mysql_query("SELECT * FROM Customers WHERE CustomersID = LAST_INSERT_ID();");
$row = mysql_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);
		

	}
		if($_GET["action"] == "update")
	{
		  $updateSQL = sprintf("UPDATE Customers SET Company=%s, EmailAddress=%s, BusinessPhone=%s, FaxNumber=%s, Address=%s, City=%s, StateProvince=%s, ZIPPostal=%s, WebPage=%s, Notes=%s WHERE CustomersID=%s",
                       GetSQLValueString($_POST['Company'], "text"),
					   GetSQLValueString($_POST['EmailAddress'], "text"),
                       GetSQLValueString($_POST['BusinessPhone'], "text"),
					   GetSQLValueString($_POST['FaxNumber'], "text"),
					   GetSQLValueString($_POST['Address'], "text"),
					   GetSQLValueString($_POST['City'], "text"),
					   GetSQLValueString($_POST['StateProvince'], "text"),
                       GetSQLValueString($_POST['ZIPPostal'], "text"),
					   GetSQLValueString($_POST['WebPage'], "text"),
					   GetSQLValueString($_POST['Notes'], "text"),
                       GetSQLValueString($_POST['CustomersID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($updateSQL, $StarCreations) or die(mysql_error());


		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
		
	}
		if($_GET["action"] == "delete")
	{
		
  $deleteSQL = sprintf("DELETE FROM Customers WHERE CustomersID=%s", GetSQLValueString($_POST['CustomersID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $result = mysql_query($deleteSQL, $StarCreations) or die(mysql_error());
  
		//Return result to jTable
$jTableResult = array();
$jTableResult['Result'] = "OK";
print json_encode($jTableResult);
	}	
?>
