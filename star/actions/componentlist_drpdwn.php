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
//GET THE COMPONENT NAME
if ($_GET['action']=="compname"){
$colname_plist = "";
if (isset($_GET['term'])) {
  $colname_plist = $_GET['term'];
}	
mysql_select_db($database_StarCreations, $StarCreations);
$query_rsComp = sprintf("SELECT Trim(ComponentName) FROM ComponentsList WHERE (ComponentName LIKE %s) ORDER BY ItemNumber ASC", GetSQLValueString("%" . $colname_plist . "%", "text"));
$rsComp = mysql_query($query_rsComp, $StarCreations) or die(mysql_error());


$rows = array();
while($row = mysql_fetch_array($rsComp)) {
    $element = array();
    $rows[] = $row[0];
}

print json_encode($rows);
}

//GET THE COMPONENT COMPONENTCOST
if ($_GET['action']=="compcost"){
$colname_plist = "";
if (isset($_GET['myterm'])) {
  $colname_plist = $_GET['myterm'];
}	
mysql_select_db($database_StarCreations, $StarCreations);
$query_rsComp = sprintf("SELECT UnitCost FROM ComponentsList WHERE (ComponentName LIKE %s) ORDER BY ItemNumber ASC", GetSQLValueString("%" . $colname_plist . "%", "text"));
$rsComp = mysql_query($query_rsComp, $StarCreations) or die(mysql_error());

	
    $rows = array();
    while ($row = mysql_fetch_array($rsComp)) {
        $eil = array();
        $eil["label"] = $row[0];
        $eil["value"] = $row[0];
        $rows[] = $eil;
        }
    $jTableResult = array();
    $jTableResult = $rows;  
    print json_encode($jTableResult);  
}

//GET THE COMPONENT UNIT OF MEASURE
if ($_GET['action']=="compunit"){
$colname_plist = "";
if (isset($_GET['myterm'])) {
  $colname_plist = $_GET['myterm'];
}	
mysql_select_db($database_StarCreations, $StarCreations);
$query_rsComp = sprintf("SELECT UnitOfMeasure FROM ComponentsList WHERE (ComponentName LIKE %s) GROUP BY UnitOfMeasure ORDER BY UnitOfMeasure ASC", GetSQLValueString("%" . $colname_plist . "%", "text"));
$rsComp = mysql_query($query_rsComp, $StarCreations) or die(mysql_error());

	
    $rows = array();
    while ($row = mysql_fetch_array($rsComp)) {
        $eil = array();
        $eil["label"] = $row[0];
        $eil["value"] = $row[0];
        $rows[] = $eil;
        }
    $jTableResult = array();
    $jTableResult = $rows;  
    print json_encode($jTableResult);  
}
//GET THE COMPONENT Dimentions Modifier
if ($_GET['action']=="compwidth"){
$colname_plist = "";
if (isset($_GET['myterm'])) {
  $colname_plist = $_GET['myterm'];
}	
mysql_select_db($database_StarCreations, $StarCreations);
$query_rsComp = sprintf("SELECT DimentionsModifier FROM ComponentsList WHERE (ComponentName LIKE %s) GROUP BY DimentionsModifier ORDER BY DimentionsModifier ASC", GetSQLValueString("%" . $colname_plist . "%", "text"));
$rsComp = mysql_query($query_rsComp, $StarCreations) or die(mysql_error());

	
    $rows = array();
    while ($row = mysql_fetch_array($rsComp)) {
        $eil = array();
        $eil["label"] = $row[0];
        $eil["value"] = $row[0];
        $rows[] = $eil;
        }
    $jTableResult = array();
    $jTableResult = $rows;  
    print json_encode($jTableResult);  
}
?>



