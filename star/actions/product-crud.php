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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ($_GET['action']=="add"){

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "productForm") && (isset($_POST["ProductID"])) && ($_POST["ProductID"] =="")) {
	
  $insertSQL = sprintf("INSERT INTO Products (ProductID, ProductCode, ProductName, InnerMolding, PHeight, PWidth, Dimensions, Cost, Price, TotalCost, TotalPrice, Percentage, Multiplier, PrintPublisher, PrintSize, Molding, PrintNumber, PrintTitle, PrintArtist, Mat1, Mat2, Mat3, Mat4, CreatedBy, ModifiedBy, CustomerIDs, Discontinued, Confirmed, ProductInfo, Tags, Reports, CreateDate, AlterDate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, now(), now())",
                       GetSQLValueString($_POST['ProductID'], "int"),
                       GetSQLValueString($_POST['ProductCode'], "text"),
                       GetSQLValueString($_POST['ProductName'], "text"),
					   GetSQLValueString($_POST['InnerMolding'], "text"),
                       GetSQLValueString($_POST['PHeight'], "text"),
                       GetSQLValueString($_POST['PWidth'], "text"),
                       GetSQLValueString($_POST['Dimensions'], "text"),
					   GetSQLValueString($_POST['Cost'], "double"),
                       GetSQLValueString($_POST['Price'], "double"),
					   GetSQLValueString($_POST['TotalCost'], "double"),
                       GetSQLValueString($_POST['TotalPrice'], "double"),
                       GetSQLValueString($_POST['Percentage'], "double"),
                       GetSQLValueString($_POST['Multiplier'], "int"),
                       GetSQLValueString($_POST['PrintPublisher'], "text"),
                       GetSQLValueString($_POST['PrintSize'], "text"),
					   GetSQLValueString($_POST['Molding'], "text"),
					   GetSQLValueString($_POST['PrintNumber'], "text"),
					   GetSQLValueString($_POST['PrintTitle'], "text"),
					   GetSQLValueString($_POST['PrintArtist'], "text"),
					   GetSQLValueString($_POST['Mat1'], "text"),
					   GetSQLValueString($_POST['Mat2'], "text"),
					   GetSQLValueString($_POST['Mat3'], "text"),
					   GetSQLValueString($_POST['Mat4'], "text"),
					   GetSQLValueString($_SESSION['MM_UserFullName'], "text"),
					   GetSQLValueString($_SESSION['MM_UserFullName'], "text"),
                       GetSQLValueString(implode(",", $_POST['CustomerIDs']), "text"),
                       GetSQLValueString(isset($_POST['Discontinued']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(isset($_POST['Confirmed']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['ProductInfo'], "text"),
                       GetSQLValueString($_POST['Tags'], "text"),
					   GetSQLValueString($_POST['Reports'], "text"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($insertSQL, $StarCreations) or die(mysql_error());


mysql_select_db($database_StarCreations, $StarCreations);
$query_rsProdList = "SELECT * FROM Products WHERE ProductID = LAST_INSERT_ID();";
$rsProdList = mysql_query($query_rsProdList, $StarCreations) or die(mysql_error());
$row_rsProdList = mysql_fetch_assoc($rsProdList);
$totalRows_rsProdList = mysql_num_rows($rsProdList);

  $insertSQL2 = sprintf("INSERT INTO Components (ProductID, ComponentName, Quantity, UnitOfMeasure, UnitCost, ExtendedCost, DimentionsModifier) 
  						Select '%s', ComponentName, '1', UnitOfMeasure, UnitCost, '0', DimentionsModifier 
						FROM ComponentsList 
						WHERE DefaultComponent = 1",
                       GetSQLValueString($row_rsProdList['ProductID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result2 = mysql_query($insertSQL2, $StarCreations) or die(mysql_error());


  $insertGoTo = "/products-add.php?ProductID=".$row_rsProdList['ProductID'];
  header(sprintf("Location: %s", $insertGoTo));

}

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "productForm") && (isset($_POST["ProductID"])) && ($_POST["ProductID"] <> "")) {
  $updateSQL = sprintf("UPDATE Products SET ProductCode=%s, CustomerIDs=%s, ProductName=%s, InnerMolding=%s, PHeight=%s, PWidth=%s, Cost=%s, Price=%s, TotalCost=%s, TotalPrice=%s, Percentage=%s, Discontinued=%s, Confirmed=%s, ProductInfo=%s, Multiplier=%s, Tags=%s, Reports=%s, PrintPublisher=%s, PrintSize=%s, Molding=%s, PrintNumber=%s, PrintTitle=%s, PrintArtist=%s, Mat1=%s, Mat2=%s, Mat3=%s, Mat4=%s,ModifiedBy=%s WHERE ProductID=%s",
                       GetSQLValueString($_POST['ProductCode'], "text"),
                       GetSQLValueString(implode(",", $_POST['CustomerIDs']), "text"),
                       GetSQLValueString($_POST['ProductName'], "text"),
					   GetSQLValueString($_POST['InnerMolding'], "text"),
                       GetSQLValueString($_POST['PHeight'], "text"),
					   GetSQLValueString($_POST['PWidth'], "text"),
                       GetSQLValueString($_POST['Cost'], "double"),
                       GetSQLValueString($_POST['Price'], "double"),
					   GetSQLValueString($_POST['TotalCost'], "double"),
                       GetSQLValueString($_POST['TotalPrice'], "double"),
                       GetSQLValueString($_POST['Percentage'], "double"),
                       GetSQLValueString(isset($_POST['Discontinued']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString(isset($_POST['Confirmed']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($_POST['ProductInfo'], "text"),
					   GetSQLValueString($_POST['Multiplier'], "int"),
					   GetSQLValueString($_POST['Tags'], "text"),
					   GetSQLValueString($_POST['Reports'], "text"),
					   GetSQLValueString($_POST['PrintPublisher'], "text"),
					   GetSQLValueString($_POST['PrintSize'], "text"),
					   GetSQLValueString($_POST['Molding'], "text"),
					   GetSQLValueString($_POST['PrintNumber'], "text"),
					   GetSQLValueString($_POST['PrintTitle'], "text"),
					   GetSQLValueString($_POST['PrintArtist'], "text"),
					   GetSQLValueString($_POST['Mat1'], "text"),
					   GetSQLValueString($_POST['Mat2'], "text"),
					   GetSQLValueString($_POST['Mat3'], "text"),
					   GetSQLValueString($_POST['Mat4'], "text"),
					   
					   
					   GetSQLValueString($_SESSION['MM_UserFullName'], "text"),
                       GetSQLValueString($_POST['ProductID'], "int"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($updateSQL, $StarCreations) or die(mysql_error());
  



  $insertGoTo = "/products-add.php?ProductID=".$_POST['ProductID'];
  header(sprintf("Location: %s", $insertGoTo));
}
}
if ($_GET['action']=="delete"){
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "productForm") && (isset($_POST["ProductID"]))) {
		  $deleteSQL = sprintf("DELETE FROM Products WHERE ProductID=%s",
							   GetSQLValueString($_POST	['ProductID'], "int"));
		
		  mysql_select_db($database_StarCreations, $StarCreations);
		  $Result1 = mysql_query($deleteSQL, $StarCreations) or die(mysql_error());
		  
		  echo "Product has been successfully removed";
	}
}

if ($_GET['action']=="duplicate"){
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "productForm") && (isset($_POST["ProductID"]))) {
	$duplicateSQL1 = sprintf("CREATE TEMPORARY TABLE tmp SELECT * from Products WHERE ProductID=%s",
							GetSQLValueString($_POST['ProductID'], "int"));
	$duplicateSQL2 = sprintf("ALTER TABLE tmp drop ProductID");	
	$duplicateSQL3 = sprintf("UPDATE tmp SET ProductCode=%s",
							GetSQLValueString($_POST['ProductID'], "int"));	  
	$duplicateSQL4 = sprintf("INSERT INTO Products SELECT 0,tmp.* FROM tmp");	 							
	$duplicateSQL5 = sprintf("DROP TABLE tmp;"); 
	  mysql_select_db($database_StarCreations, $StarCreations);
	  $Result1 = mysql_query($duplicateSQL1, $StarCreations) or die(mysql_error());
	  $Result2 = mysql_query($duplicateSQL2, $StarCreations) or die(mysql_error());
	  $Result3 = mysql_query($duplicateSQL3, $StarCreations) or die(mysql_error());
	  $Result4 = mysql_query($duplicateSQL4, $StarCreations) or die(mysql_error());
	  $Result5 = mysql_query($duplicateSQL5, $StarCreations) or die(mysql_error());
	  

mysql_select_db($database_StarCreations, $StarCreations);
$query_rsProdList = "SELECT * FROM Products WHERE ProductID = LAST_INSERT_ID();";
$rsProdList = mysql_query($query_rsProdList, $StarCreations) or die(mysql_error());
$row_rsProdList = mysql_fetch_assoc($rsProdList);
$totalRows_rsProdList = mysql_num_rows($rsProdList);

	$duplicateSQL6 = sprintf("CREATE TEMPORARY TABLE tmp SELECT * from Components WHERE ProductID=%s",
							GetSQLValueString($_POST['ProductID'], "int"));
	$duplicateSQL7 = sprintf("ALTER TABLE tmp drop ComponentID");	
	$duplicateSQL8 = sprintf("UPDATE tmp SET ProductID=%s",
							GetSQLValueString($row_rsProdList['ProductID'], "int"));	  
	$duplicateSQL9 = sprintf("INSERT INTO Components SELECT 0,tmp.* FROM tmp");	 							
	$duplicateSQL10 = sprintf("DROP TABLE tmp;"); 
	  mysql_select_db($database_StarCreations, $StarCreations);
	  $Result1 = mysql_query($duplicateSQL6, $StarCreations) or die(mysql_error());
	  $Result2 = mysql_query($duplicateSQL7, $StarCreations) or die(mysql_error());
	  $Result3 = mysql_query($duplicateSQL8, $StarCreations) or die(mysql_error());
	  $Result4 = mysql_query($duplicateSQL9, $StarCreations) or die(mysql_error());
	  $Result5 = mysql_query($duplicateSQL10, $StarCreations) or die(mysql_error());


$insertGoTo = "products-add.php?ProductID=".$row_rsProdList['ProductID'];
//header(sprintf("Location: %s", $insertGoTo));
print json_encode($insertGoTo);

	}
}
 ?>
