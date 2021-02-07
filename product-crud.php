<?php require_once('Connections/StarCreations.php');
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  if (PHP_VERSION < 6) {
    $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
  }

  $theValue = function_exists("mysql_real_escape_string") ?htmlspecialchars(stripslashes($theValue)) : htmlspecialchars(stripslashes($theValue));

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($_GET['action']=="add"){

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "productForm") && (isset($_POST["ProductID"])) && ($_POST["ProductID"] =="")) {
	
  $insertSQL = sprintf("INSERT INTO Products (ProductCode, CustomerIDs, ProductName, PHeight, PWidth, Cost, Price, Percentage, Discontinued, CreateDate, AlterDate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, now(), now())",
                       GetSQLValueString($_POST['ProductCode'], "text"),
                       GetSQLValueString(implode(",", $_POST['CustomerIDs']), "text"),
                       GetSQLValueString($_POST['ProductName'], "text"),
                       GetSQLValueString($_POST['PHeight'], "text"),
					   GetSQLValueString($_POST['PWidth'], "text"),
                       GetSQLValueString($_POST['Cost'], "double"),
                       GetSQLValueString($_POST['Price'], "double"),
                       GetSQLValueString($_POST['Percentage'], "double"),
                       GetSQLValueString(isset($_POST['Discontinued']) ? "true" : "", "defined","1","0"));

  mysqli_select_db($StarCreations,$database_StarCreations);
  $result = mysqli_query($StarCreations,$insertSQL) or die(mysqli_error());


mysqli_select_db($StarCreations,$database_StarCreations);
$query_rsProdList = "SELECT * FROM Products WHERE ProductID = LAST_INSERT_ID();";
$rsProdList = mysqli_query($StarCreations,$query_rsProdList) or die(mysqli_error());
$row_rsProdList = mysqli_fetch_assoc($rsProdList);
$totalRows_rsProdList = mysqli_num_rows($rsProdList);

$json = array('key' => 'ProductID', 
              'value' => $row_rsProdList['ProductID']);
echo json_encode($json);

}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "productForm") && (isset($_POST["ProductID"])) && ($_POST["ProductID"] <> "")) {
  $updateSQL = sprintf("UPDATE Products SET ProductCode=%s, CustomerIDs=%s, ProductName=%s, PHeight=%s, PWidth=%s, Cost=%s, Price=%s, Percentage=%s, Discontinued=%s WHERE ProductID=%s",
                       GetSQLValueString($_POST['ProductCode'], "text"),
                       GetSQLValueString(implode(",", $_POST['CustomerIDs']), "text"),
                       GetSQLValueString($_POST['ProductName'], "text"),
                       GetSQLValueString($_POST['PHeight'], "text"),
					   GetSQLValueString($_POST['PWidth'], "text"),
                       GetSQLValueString($_POST['Cost'], "double"),
                       GetSQLValueString($_POST['Price'], "double"),
                       GetSQLValueString($_POST['Percentage'], "double"),
                       GetSQLValueString(isset($_POST['Discontinued']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['ProductID'], "int"));

  mysqli_select_db($StarCreations,$database_StarCreations);
  $result = mysqli_query($StarCreations,$updateSQL) or die(mysqli_error());
  



$json = array('key' => 'ProductID', 
              'value' => $_POST['ProductID']);
echo json_encode($json );
}
}
if ($_GET['action']=="delete"){
if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "productForm") && (isset($_POST["ProductID"]))) {
  $deleteSQL = sprintf("DELETE FROM Products WHERE ProductID=%s",
                       GetSQLValueString($_POST	['ProductID'], "int"));

  mysqli_select_db($StarCreations,$database_StarCreations);
  $result = mysqli_query($StarCreations,$deleteSQL) or die(mysqli_error());

}
}
