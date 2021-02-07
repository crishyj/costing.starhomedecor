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
$colname_plist4 = "";
if (isset($_POST['pfilter'])) {
  $colname_plist4 = $_POST['pfilter'];
}
$colname_plist5 = "";
if (isset($_POST['pfilter'])) {
  $colname_plist5 = $_POST['pfilter'];
}
	
mysql_select_db($database_StarCreations, $StarCreations);

$query_plist1 = sprintf("
SELECT COUNT(*) AS RecordCount FROM Products WHERE (ProductCode LIKE %s) OR (ProductName LIKE %s) OR (PrintNumber LIKE %s) OR (PrintTitle LIKE %s) OR (Molding LIKE %s) ", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"), GetSQLValueString("%" . $colname_plist4 . "%", "text"), GetSQLValueString("%" . $colname_plist5 . "%", "text"));
$plist1 = mysql_query($query_plist1, $StarCreations) or die(mysql_error());
$row_plist1 = mysql_fetch_assoc($plist1);


$query_plist2 = sprintf("SELECT * FROM Products WHERE (ProductCode LIKE %s) OR (ProductName LIKE %s) OR (PrintNumber LIKE %s) OR (PrintTitle LIKE %s) OR (Molding LIKE %s)  ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"), GetSQLValueString("%" . $colname_plist4 . "%", "text"), GetSQLValueString("%" . $colname_plist5 . "%", "text"));
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

		if($_GET["action"] == "delete")
	{
		
  $deleteSQL = sprintf("DELETE FROM Products WHERE ProductID=%s", GetSQLValueString($_POST	['ProductID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $result = mysql_query($deleteSQL, $StarCreations) or die(mysql_error());
  
		//Return result to jTable
$jTableResult = array();
$jTableResult['Result'] = "OK";
print json_encode($jTableResult);
	}
?>
