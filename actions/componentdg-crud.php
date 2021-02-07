<?php require_once('../Connections/StarCreations.php');
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

    $colname_plist2 = "";
    if (isset($_POST['pfilter'])) {
        $colname_plist2 = $_POST['pfilter'];
    }

    $colname_plist3 = "";
    if (isset($_POST['pfilter'])) {
        $colname_plist3 = $_POST['pfilter'];
    }

    mysqli_select_db($StarCreations,$database_StarCreations);

    $query_plist1 = sprintf("SELECT COUNT(*) AS RecordCount FROM ComponentsList WHERE (ItemNumber LIKE %s) OR (ComponentName LIKE %s) OR (ComponentClass LIKE %s)", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"));
    $plist1 = mysqli_query($StarCreations,$query_plist1) or die(mysqli_error($StarCreations));
    $row_plist1 = mysqli_fetch_assoc($plist1);


    $query_plist2 = sprintf("SELECT * FROM ComponentsList WHERE (ItemNumber LIKE %s) OR (ComponentName LIKE %s)  OR (ComponentClass LIKE %s) ORDER BY " . $_GET["jtSorting"] . " LIMIT " . $_GET["jtStartIndex"] . "," . $_GET["jtPageSize"] . "", GetSQLValueString("%" . $colname_plist . "%", "text"), GetSQLValueString("%" . $colname_plist2 . "%", "text"), GetSQLValueString("%" . $colname_plist3 . "%", "text"));
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


    $insertSQL = sprintf("INSERT INTO ComponentsList (ItemNumber, ComponentName, ComponentClass, DimentionsModifier, UnitOfMeasure, UnitCost, DefaultComponent, CreateDate) VALUES (%s, %s, %s, %s, %s, %s, %s, now())",
        GetSQLValueString($_POST['ItemNumber'], "text"),
        GetSQLValueString($_POST['ComponentName'], "text"),
        GetSQLValueString($_POST['ComponentClass'], "text"),
        GetSQLValueString($_POST['DimentionsModifier'], "double"),
        GetSQLValueString($_POST['UnitOfMeasure'], "text"),
        GetSQLValueString($_POST['UnitCost'], "double"),
        GetSQLValueString(isset($_POST['DefaultComponent']) ? "true" : "", "defined","1","0"),
        GetSQLValueString($_POST['CreateDate'], "date"));

    mysqli_select_db($StarCreations,$database_StarCreations);
    $Result1 = mysqli_query($StarCreations,$insertSQL) or die(mysqli_error($StarCreations));

    $result = mysqli_query($StarCreations,"SELECT * FROM ComponentsList WHERE ComponentID = LAST_INSERT_ID();");
    $row = mysqli_fetch_array($result);

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

    mysqli_select_db($StarCreations,$database_StarCreations);
    $result = mysqli_query($StarCreations,$updateSQL) or die(mysqli_error($StarCreations));


    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    print json_encode($jTableResult);

}
if($_GET["action"] == "delete")
{

    $deleteSQL = sprintf("DELETE FROM ComponentsList WHERE ComponentID=%s", GetSQLValueString($_POST['ComponentID'], "int"));

    mysqli_select_db($StarCreations,$database_StarCreations);
    $result = mysqli_query($StarCreations,$deleteSQL) or die(mysqli_error($StarCreations));

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    print json_encode($jTableResult);
}
