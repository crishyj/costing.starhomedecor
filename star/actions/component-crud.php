<?php 

try
{
require_once('../Connections/StarCreations.php'); ?>
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
	
$colname_c_list = "-1";
if (isset($_GET['ProductID'])) {
  $colname_c_list = $_GET['ProductID'];
}
mysql_select_db($database_StarCreations, $StarCreations);
$query_c_list = sprintf("SELECT * FROM Components WHERE ProductID = %s", GetSQLValueString($colname_c_list, "int"));
$c_list = mysql_query($query_c_list, $StarCreations) or die(mysql_error());
//$row_c_list = mysql_fetch_assoc($c_list);
//$totalRows_c_list = mysql_num_rows($c_list);

$rows = array();
while($row = mysql_fetch_array($c_list))
{
    $rows[] = $row;
}
 
//Return result to jTable
$jTableResult = array();
$jTableResult['Result'] = "OK";
$jTableResult['Records'] = $rows;
print json_encode($jTableResult);

	}
	//Creating a new record (createAction)
	else if($_GET["action"] == "create")
	{
		
		
$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

  $insertSQL = sprintf("INSERT INTO Components (ProductID, ComponentName, DimentionsModifier, Quantity, UnitOfMeasure, UnitCost, Scrap, ComponentInfo, ExtendedCost, Report, CreateDate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, now())",
                       GetSQLValueString($_GET['ProductID'], "int"),
                       GetSQLValueString($_POST['ComponentName'], "text"),
                       GetSQLValueString($_POST['DimentionsModifier'], "double"),
					    GetSQLValueString($_POST['Quantity'], "double"),
                       GetSQLValueString($_POST['UnitOfMeasure'], "text"),
                       GetSQLValueString($_POST['UnitCost'], "double"),
					   GetSQLValueString($_POST['Scrap'], "double"),
					   GetSQLValueString($_POST['ComponentInfo'], "text"),
                       GetSQLValueString($_POST['ExtendedCost'], "double"),
					   GetSQLValueString(isset($_POST['Report']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['CreateDate'], "date"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($insertSQL, $StarCreations) or die(mysql_error());
  
$result = mysql_query("SELECT * FROM Components WHERE ComponentID = LAST_INSERT_ID();");
$row = mysql_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);
		
		
		
			}
	//Creating a new record (createAction)
	else if($_GET["action"] == "update")
	{
		


  $updateSQL = sprintf("UPDATE Components SET  ComponentName=%s, DimentionsModifier=%s, Quantity=%s, UnitOfMeasure=%s, UnitCost=%s, Scrap=%s, ExtendedCost=%s, ComponentInfo=%s, Report=%s, AlterDate=now() WHERE ComponentID=%s",

                       GetSQLValueString($_POST['ComponentName'], "text"),
					   GetSQLValueString($_POST['DimentionsModifier'], "double"),
                       GetSQLValueString($_POST['Quantity'], "double"),
                       GetSQLValueString($_POST['UnitOfMeasure'], "text"),
                       GetSQLValueString($_POST['UnitCost'], "double"),
					   GetSQLValueString($_POST['Scrap'], "double"),
                       GetSQLValueString($_POST['ExtendedCost'], "double"),
					   GetSQLValueString($_POST['ComponentInfo'], "text"),
					   GetSQLValueString(isset($_POST['Report']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['ComponentID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($updateSQL, $StarCreations) or die(mysql_error());


		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
		
			}
	//Creating a new record (createAction)
	else if($_GET["action"] == "delete")
	{
				
  $deleteSQL = sprintf("DELETE FROM Components WHERE ComponentID=%s", GetSQLValueString($_POST['ComponentID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $result = mysql_query($deleteSQL, $StarCreations) or die(mysql_error());
  
		//Return result to jTable
$jTableResult = array();
$jTableResult['Result'] = "OK";
print json_encode($jTableResult);
		
		}


//Close database connection

}


catch(Exception $ex)
{
    //Return error message
	$jTableResult = array();
	$jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = $ex->getMessage();
	print json_encode($jTableResult);
}
	
?>
