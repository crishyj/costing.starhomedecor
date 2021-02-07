<?php
require_once('../Connections/StarCreations.php');
if (!function_exists("GetSQLValueString"))
{
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

    $colname_plist2 = "";
    if (isset($_POST['pfilter'])) {
        $colname_plist2 = $_POST['pfilter'];
    }

    $colname_plist3 = "";
    if (isset($_POST['pfilter'])) {
        $colname_plist3 = $_POST['pfilter'];
    }

    mysqli_select_db($StarCreations,$database_StarCreations);

    $query_plist1 = sprintf("SELECT COUNT(*) AS RecordCount FROM Employees WHERE (FirstName LIKE %s) OR (LastName LIKE %s) OR (EmailAddress LIKE %s)", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"));
    $plist1 = mysqli_query($StarCreations,$query_plist1) or die(mysqli_error($StarCreations));
    $row_plist1 = mysqli_fetch_assoc($plist1);


    $query_plist2 = sprintf("SELECT ID, FirstName, LastName, EmailAddress, AES_DECRYPT(Password, '@s5fs356g7$7&85e') AS Password, Privilege, CreateDate, AlterDate FROM Employees WHERE (FirstName LIKE %s) OR (LastName LIKE %s)  OR (EmailAddress LIKE %s) ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"));
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


    $insertSQL = sprintf("INSERT INTO Employees (FirstName, LastName, EmailAddress, Password, Privilege, CreateDate) VALUES (%s, %s, %s, AES_ENCRYPT(%s,'@s5fs356g7$7&85e'), %s, now())",
        GetSQLValueString($_POST['FirstName'], "text"),
        GetSQLValueString($_POST['LastName'], "text"),
        GetSQLValueString($_POST['EmailAddress'], "text"),
        GetSQLValueString($_POST['Password'], "text"),
        GetSQLValueString($_POST['Privilege'], "text"),
        GetSQLValueString($_POST['CreateDate'], "date"));

    mysqli_select_db($StarCreations,$database_StarCreations);
    $Resultl = mysqli_query($StarCreations,$insertSQL) or die(mysqli_error($StarCreations));

    $result = mysqli_query($StarCreations, "SELECT ID, FirstName, LastName, EmailAddress, AES_DECRYPT(Password, '@s5fs356g7$7&85e') AS Password, Privilege, CreateDate, AlterDate FROM Employees WHERE ID = LAST_INSERT_ID();");
    $row = mysqli_fetch_array($result);

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $row;
    print json_encode($jTableResult);


}
if($_GET["action"] == "update")
{
    $updateSQL = sprintf("UPDATE Employees SET  FirstName=%s, LastName=%s, EmailAddress=%s, Password=AES_ENCRYPT(%s,'@s5fs356g7$7&85e'), Privilege=%s WHERE ID=%s",
        GetSQLValueString($_POST['FirstName'], "text"),
        GetSQLValueString($_POST['LastName'], "text"),
        GetSQLValueString($_POST['EmailAddress'], "text"),
        GetSQLValueString($_POST['Password'], "text"),
        GetSQLValueString($_POST['Privilege'], "text"),
        GetSQLValueString($_POST['ID'], "int"));

    mysqli_select_db($StarCreations,$database_StarCreations);
    $result = mysqli_query($StarCreations,$updateSQL) or die(mysqli_error($StarCreations));


    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    print json_encode($jTableResult);

}
if($_GET["action"] == "delete")
{

    $deleteSQL = sprintf("DELETE FROM Employees WHERE ID=%s", GetSQLValueString($_POST['ID'], "int"));

    mysqli_select_db($StarCreations,$database_StarCreations);
    $result = mysqli_query($StarCreations,$deleteSQL) or die(mysqli_error($StarCreations));

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    print json_encode($jTableResult);
}
?>
