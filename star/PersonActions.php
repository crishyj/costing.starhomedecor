<?php

include('inc/functions.php');


try
{
	//Open database connection
	$con = mysql_connect("localhost","starcreations","MySQL4StarCreations");
	mysql_select_db("app_starcreations", $con);
	$MyTable = "Products";
	$UniqueID = "ProductID";
	//Getting records (listAction)
	if($_GET["action"] == "list")
	{
		

//Get record count
		$result = mysql_query("SELECT COUNT(*) AS RecordCount FROM ".$MyTable.";");
		$row = mysql_fetch_array($result);
		$recordCount = $row['RecordCount'];

		//Get records from database
		$result = mysql_query("SELECT * FROM ".$MyTable." ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . ";");
		
		//Add all records to an array
		$rows = array();
		while($row = mysql_fetch_array($result))
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
		$result = mysql_query(sprintf("INSERT INTO Products (SupplierIDs, ProductCode, ProductName, `Description`, StandardCost, ListPrice, ReorderLevel, TargetLevel, QuantityPerUnit, Discontinued, MinimumReorderQuantity, Category, RecordDate) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, now())",
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
		$result = mysql_query("SELECT * FROM ".$MyTable." WHERE ".$UniqueID." = LAST_INSERT_ID();");
		$row = mysql_fetch_array($result);

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
		$result = mysql_query(sprintf("UPDATE Products SET SupplierIDs=%s, ProductCode=%s, ProductName=%s, `Description`=%s, StandardCost=%s, ListPrice=%s, ReorderLevel=%s, TargetLevel=%s, QuantityPerUnit=%s, Discontinued=%s, MinimumReorderQuantity=%s, Category=%s, RecordDate=%s WHERE ProductID=%s",
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
		$result = mysql_query("DELETE FROM ".$MyTable." WHERE ".$UniqueID." = " . $_POST["".$UniqueID.""] . ";");

		//Return result to jTable
		$jTableResult = array();
		$jTableResult['Result'] = "OK";
		print json_encode($jTableResult);
	}

	//Close database connection
	mysql_close($con);

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