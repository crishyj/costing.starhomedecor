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

		$theValue = function_exists("mysql_real_escape_string") ? htmlspecialchars(stripslashes($theValue)) :htmlspecialchars(stripslashes($theValue));

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

		mysqli_select_db($StarCreations,$database_StarCreations);
		$Result1 = mysqli_query($StarCreations,$insertSQL) or trigger_error(mysqli_error($StarCreations));


		mysqli_select_db($StarCreations,$database_StarCreations);
		$query_rsProdList = "SELECT * FROM Products WHERE ProductID = LAST_INSERT_ID();";
		$rsProdList = mysqli_query($StarCreations,$query_rsProdList) or trigger_error(mysqli_error($StarCreations));
		$row_rsProdList = mysqli_fetch_assoc($rsProdList);
		$totalRows_rsProdList = mysqli_num_rows($rsProdList);

		$insertSQL2 = sprintf("INSERT INTO Components (ProductID, ComponentName, Quantity, UnitOfMeasure, UnitCost, ExtendedCost, DimentionsModifier), Select '%s', ComponentName, '1', UnitOfMeasure, UnitCost, '0', DimentionsModifier, FROM ComponentsList, WHERE DefaultComponent = 1", GetSQLValueString($row_rsProdList['ProductID'], "int"));

		mysqli_select_db($StarCreations,$database_StarCreations);
		$Result2 = mysqli_query($StarCreations,$insertSQL2) or trigger_error(mysqli_error($StarCreations));

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

		mysqli_select_db($StarCreations,$database_StarCreations);
		$Result1 = mysqli_query($StarCreations,$updateSQL) or trigger_error(mysqli_error($StarCreations));

		$insertGoTo = "/products-add.php?ProductID=".$_POST['ProductID'];
		header(sprintf("Location: %s", $insertGoTo));
	}
}
if ($_GET['action']=="delete"){
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "productForm") && (isset($_POST["ProductID"]))) {
		$deleteSQL = sprintf("DELETE FROM Products WHERE ProductID=%s",
			GetSQLValueString($_POST	['ProductID'], "int"));

		mysqli_select_db($StarCreations,$database_StarCreations);
		$Result1 = mysqli_query($StarCreations,$deleteSQL) or trigger_error(mysqli_error($StarCreations));

		echo "Product has been successfully removed";
	}
}

if ($_GET['action']=="duplicate"){
	
	if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "productForm") && (isset($_POST["ProductID"]))) {
		$ProductCode1 = $_POST['ProductCode1'];
		$sql = "SELECT * from Products WHERE ProductCode = '$ProductCode1'";
		
		mysqli_select_db($StarCreations,$database_StarCreations);
		$Result0 = mysqli_query($StarCreations,$sql) or trigger_error(mysqli_error($StarCreations));
		$row0 = mysqli_fetch_assoc($Result0);
		$temp_id = $row0['ProductID'];
				
		$duplicateSQL1 = sprintf("CREATE TEMPORARY TABLE tmp SELECT * from Products WHERE ProductID = %s",
			GetSQLValueString($_POST['ProductID'], "int"));			
		$duplicateSQL2 = sprintf("ALTER TABLE tmp drop ProductID");
		$duplicateSQL3 = sprintf("UPDATE tmp SET ProductCode=%s",GetSQLValueString($_POST['ProductID'], "int"));
		$duplicateSQL4 = sprintf("INSERT INTO Products SELECT 0,tmp.* FROM tmp");
		$duplicateSQL5 = sprintf("DROP TABLE tmp;");
		mysqli_select_db($StarCreations,$database_StarCreations);
		$Result1 = mysqli_query($StarCreations,$duplicateSQL1) or trigger_error(mysqli_error($StarCreations));		
		$Result2 = mysqli_query($StarCreations,$duplicateSQL2) or trigger_error(mysqli_error($StarCreations));
		$Result3 = mysqli_query($StarCreations,$duplicateSQL3) or trigger_error(mysqli_error($StarCreations));
        $NewID = sprintf(GetSQLValueString($_POST['ProductID'], "int"));
		$Result4 = mysqli_query($StarCreations,$duplicateSQL4) or trigger_error(mysqli_error($StarCreations));
		$Result5 = mysqli_query($StarCreations,$duplicateSQL5) or trigger_error(mysqli_error($StarCreations));		
		mysqli_select_db($StarCreations,$database_StarCreations);
		$query_rsProdList = "SELECT * FROM Products WHERE ProductID = '$NewID'";
		$rsProdList = mysqli_query($StarCreations,$query_rsProdList) or die(mysqli_error($StarCreations));		
		$row_rsProdList = mysqli_fetch_assoc($rsProdList);
		$totalRows_rsProdList = mysqli_num_rows($rsProdList);		

		// $duplicateSQL6 = sprintf("CREATE TEMPORARY TABLE tmp1 SELECT * from Components WHERE ProductID = %s",
		// 	GetSQLValueString($_POST['ProductID'], "int"));
		// $duplicateSQL7 = sprintf("ALTER TABLE tmp1 drop ComponentID");
		// $duplicateSQL8 = sprintf("UPDATE tmp1 SET ProductID=%s",GetSQLValueString($_POST['ProductID'], "int"));		
		// $duplicateSQL9 = sprintf("INSERT INTO Components SELECT 0,tmp1.* FROM tmp1");
		// $duplicateSQL10 = sprintf("DROP TABLE tmp1;");
		// mysqli_select_db($StarCreations,$database_StarCreations);
		// $Result1 = mysqli_query($StarCreations,$duplicateSQL6) or die(mysqli_error($StarCreations));
		// $Result2 = mysqli_query($StarCreations,$duplicateSQL7) or die(mysqli_error($StarCreations));
		// $Result3 = mysqli_query($StarCreations,$duplicateSQL8) or die(mysqli_error($StarCreations));
		// $Result4 = mysqli_query($StarCreations,$duplicateSQL9) or die(mysqli_error($StarCreations));
		// $Result5 = mysqli_query($StarCreations,$duplicateSQL10) or die(mysqli_error($StarCreations));

		$insertGoTo = "products-add.php?ProductID=".$row_rsProdList['ProductID'];
//header(sprintf("Location: %s", $insertGoTo));
		print json_encode($insertGoTo);

	}
}