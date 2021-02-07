<?php require_once('../Connections/StarCreations.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
	function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
	{
		if (PHP_VERSION < 6) {
			$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
		}

		$theValue = function_exists("mysql_real_escape_string") ? htmlspecialchars(stripslashes($theValue)) : htmlspecialchars(stripslashes($theValue));

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

	mysqli_select_db($StarCreations,$database_StarCreations);

	$query_plist1 = sprintf("SELECT COUNT(*) AS RecordCount FROM Customers WHERE (Company LIKE %s)", GetSQLValueString("%" . $colname_plist . "%", "text"));
	$plist1 = mysqli_query($StarCreations,$query_plist1) or die(mysqli_error($StarCreations,E_ALL));
	$row_plist1 = mysqli_fetch_assoc($plist1);


	$query_plist2 = sprintf("SELECT * FROM Customers WHERE (Company LIKE %s) ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "", GetSQLValueString("%" . $colname_plist . "%", "text"));
	$plist2 = mysqli_query($StarCreations,$query_plist2) or die(mysqli_error($StarCreations));

	$totalRows_plist2 = mysqli_num_rows($plist2);


	$rows = array();
	while($row = mysqli_fetch_array($plist2))
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

	mysqli_select_db($StarCreations,$database_StarCreations);
	$Result1 = mysqli_query($StarCreations,$insertSQL) or die(mysqli_error($StarCreations,E_ALL));

	$result = mysqli_query($StarCreations,"SELECT * FROM Customers WHERE CustomersID = LAST_INSERT_ID();");
	$row = mysqli_fetch_array($result);

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

	mysqli_select_db($StarCreations,$database_StarCreations);
	$Result1 = mysqli_query($StarCreations,$updateSQL) or die(mysqli_error($StarCreations));


	//Return result to jTable
	$jTableResult = array();
	$jTableResult['Result'] = "OK";
	print json_encode($jTableResult);

}
if($_GET["action"] == "delete")
{

	$deleteSQL = sprintf("DELETE FROM Customers WHERE CustomersID=%s", GetSQLValueString($_POST['CustomersID'], "int"));

	mysqli_select_db($StarCreations,$database_StarCreations);
	$result = mysqli_query($StarCreations,$deleteSQL) or die(mysqli_error($StarCreations));

	//Return result to jTable
	$jTableResult = array();
	$jTableResult['Result'] = "OK";
	print json_encode($jTableResult);
}
