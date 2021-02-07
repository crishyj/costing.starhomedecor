<?php

include('inc/functions.php');


try
{
	//Open database connection
	$con = mysqli_connect("localhost","starcreations","MySQL4StarCreations");
	mysqli_select_db($con,"app_starcreations");
	$MyTable = "Products";
	$UniqueID = "ProductID";
	//Getting records (listAction)
	if($_GET["action"] == "list")
	{
		

//Get record count
		$result = mysqli_query($con,"SELECT COUNT(*) AS RecordCount FROM ".$MyTable.";");
		$row = mysqli_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysqli_query($con,"SELECT * FROM ".$MyTable." ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . ";");
		
		//Add all records to an array
		$rows = array();
		while($row = mysqli_fetch_array($result))
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
		//Insert record into database
		$result = mysqli_query($con,sprintf("INSERT INTO Products (SupplierIDs, ProductCode, ProductName, `Description`, StandardCost, ListPrice, ReorderLevel, TargetLevel, QuantityPerUnit, Discontinued, MinimumReorderQuantity, Category, RecordDate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, now())",
                       GetSQLValueString($_POST['SupplierIDs'], "text"),
                       GetSQLValueString($_POST['ProductCode'], "text"),
                       GetSQLValueString($_POST['ProductName'], "text"),
                       GetSQLValueString($_POST['Description'], "text"),
                       GetSQLValueString($_POST['StandardCost'], "double"),
                       GetSQLValueString($_POST['ListPrice'], "double"),
                       GetSQLValueString($_POST['ReorderLevel'], "int"),
                       GetSQLValueString($_POST['TargetLevel'], "int"),
                       GetSQLValueString($_POST['QuantityPerUnit'], "text"),
                       GetSQLValueString(isset($_POST['Discontinued']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['MinimumReorderQuantity'], "int"),
                       GetSQLValueString($_POST['Category'], "text"))
					   );
		
		//Get last inserted record (to return to jTable)
		$result = mysqli_query($con,"SELECT * FROM ".$MyTable." WHERE ".$UniqueID." = LAST_INSERT_ID();");
		$row = mysqli_fetch_array($result);

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
		print json_encode($jTableResult);
	}
	//Updating a record (updateAction)
	else if($_GET["action"] == "update")
	{
		//Update record in database
		$result = mysqli_query($con,sprintf("UPDATE Products SET SupplierIDs=%s, ProductCode=%s, ProductName=%s, `Description`=%s, StandardCost=%s, ListPrice=%s, ReorderLevel=%s, TargetLevel=%s, QuantityPerUnit=%s, Discontinued=%s, MinimumReorderQuantity=%s, Category=%s, RecordDate=%s WHERE ProductID=%s",
                       GetSQLValueString($_POST['SupplierIDs'], "text"),
                       GetSQLValueString($_POST['ProductCode'], "text"),
                       GetSQLValueString($_POST['ProductName'], "text"),
                       GetSQLValueString($_POST['Description'], "text"),
                       GetSQLValueString($_POST['StandardCost'], "double"),
                       GetSQLValueString($_POST['ListPrice'], "double"),
                       GetSQLValueString($_POST['ReorderLevel'], "int"),
                       GetSQLValueString($_POST['TargetLevel'], "int"),
                       GetSQLValueString($_POST['QuantityPerUnit'], "text"),
                       GetSQLValueString(isset($_POST['Discontinued']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['MinimumReorderQuantity'], "int"),
                       GetSQLValueString($_POST['Category'], "text"),
                       GetSQLValueString($_POST['RecordDate'], "date"),
                       GetSQLValueString($_POST['ProductID'], "int"))
					   );

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}
	//Deleting a record (deleteAction)
	else if($_GET["action"] == "delete")
	{
		//Delete from database
		$result = mysqli_query($con,"DELETE FROM ".$MyTable." WHERE ".$UniqueID." = " . $_POST["".$UniqueID.""] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}

	//Close database connection
	mysqli_close($con);

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