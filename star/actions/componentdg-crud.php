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

$query_plist1 = sprintf("SELECT COUNT(*) AS RecordCount FROM ComponentsList WHERE (ItemNumber LIKE %s) OR (ComponentName LIKE %s) OR (ComponentClass LIKE %s)", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"));
$plist1 = mysql_query($query_plist1, $StarCreations) or die(mysql_error());
$row_plist1 = mysql_fetch_assoc($plist1);


$query_plist2 = sprintf("SELECT * FROM ComponentsList WHERE (ItemNumber LIKE %s) OR (ComponentName LIKE %s)  OR (ComponentClass LIKE %s) ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"));
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


  $insertSQL = sprintf("INSERT INTO ComponentsList (ItemNumber, ComponentName, ComponentClass, DimentionsModifier, UnitOfMeasure, UnitCost, DefaultComponent, CreateDate) VALUES (%s, %s, %s, %s, %s, %s, %s, now())",
                       GetSQLValueString($_POST['ItemNumber'], "text"),
                       GetSQLValueString($_POST['ComponentName'], "text"),
					   GetSQLValueString($_POST['ComponentClass'], "text"),
					   GetSQLValueString($_POST['DimentionsModifier'], "double"),
                       GetSQLValueString($_POST['UnitOfMeasure'], "text"),
                       GetSQLValueString($_POST['UnitCost'], "double"),
					   GetSQLValueString(isset($_POST['DefaultComponent']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['CreateDate'], "date"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($insertSQL, $StarCreations) or die(mysql_error());
  
$result = mysql_query("SELECT * FROM ComponentsList WHERE ComponentID = LAST_INSERT_ID();");
$row = mysql_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);
		

	}
		if($_GET["action"] == "update")
	{
		  $updateSQL = sprintf("UPDATE ComponentsList SET  ItemNumber=%s, ComponentName=%s, ComponentClass=%s, DimentionsModifier=%s, UnitOfMeasure=%s, UnitCost=%s, DefaultComponent=%s, AlterDate=now() WHERE ComponentID=%s",
                       GetSQLValueString($_POST['ItemNumber'], "text"),
                       GetSQLValueString($_POST['ComponentName'], "text"),
					   GetSQLValueString($_POST['ComponentClass'], "text"),
					   GetSQLValueString($_POST['DimentionsModifier'], "double"),
                       GetSQLValueString($_POST['UnitOfMeasure'], "text"),
                       GetSQLValueString($_POST['UnitCost'], "double"),
					   GetSQLValueString(isset($_POST['DefaultComponent']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['ComponentID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($updateSQL, $StarCreations) or die(mysql_error());


		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
		
	}
		if($_GET["action"] == "delete")
	{
		
  $deleteSQL = sprintf("DELETE FROM ComponentsList WHERE ComponentID=%s", GetSQLValueString($_POST['ComponentID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $result = mysql_query($deleteSQL, $StarCreations) or die(mysql_error());
  
		//Return result to jTable
$jTableResult = array();
$jTableResult['Result'] = "OK";
print json_encode($jTableResult);
	}
?>
