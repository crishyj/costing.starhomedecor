<?php

try {
    require_once('../Connections/StarCreations.php'); ?>
    <?php
    if (!function_exists("GetSQLValueString")) {
        function GetSQLValueString(
            $theValue,
            $theType,
            $theDefinedValue = "",
            $theNotDefinedValue = ""
        ) {
            if (PHP_VERSION < 6) {
                $theValue = get_magic_quotes_gpc() ? stripslashes($theValue)
                    : $theValue;
            }

            $theValue = function_exists("mysql_real_escape_string")
                ? mysqli_real_escape_string($mysqli, $theValue)
                : mysqli_escape_string($mysqli, $theValue);

            switch ($theType) {
                case "text":
                    $theValue = ($theValue != "") ? "'".$theValue."'" : "NULL";
                    break;
                case "long":
                case "int":
                    $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                    break;
                case "double":
                    $theValue = ($theValue != "") ? doubleval($theValue)
                        : "NULL";
                    break;
                case "date":
                    $theValue = ($theValue != "") ? "'".$theValue."'" : "NULL";
                    break;
                case "defined":
                    $theValue = ($theValue != "") ? $theDefinedValue
                        : $theNotDefinedValue;
                    break;
            }

            return $theValue;
        }
    }
    if ($_GET["action"] == "list") {

        $colname_c_list = "-1";
        if (isset($_GET['ProductID'])) {
            $colname_c_list = $_GET['ProductID'];
        }
        mysqli_select_db($StarCreations, $database_StarCreations);
        $query_c_list = "SELECT * FROM Components WHERE ProductID = {$colname_c_list}";
        $c_list = mysqli_query($StarCreations, $query_c_list) or die(mysqli_error($StarCreations));
//$row_c_list = mysql_fetch_assoc($c_list);
//$totalRows_c_list = mysqli_num_rows($c_list);

        $rows = array();
        while ($row = mysqli_fetch_array($c_list)) {
            $rows[] = $row;
        }

//Return result to jTable
        $jTableResult = array();
        $jTableResult['Result'] = "OK";
        $jTableResult['Records'] = $rows;
        print json_encode($jTableResult);

    } //Creating a new record (createAction)
    else {
        if ($_GET["action"] == "create") {


            $editFormAction = $_SERVER['PHP_SELF'];
            if (isset($_SERVER['QUERY_STRING'])) {
                $editFormAction .= "?".htmlentities($_SERVER['QUERY_STRING']);
            }
            $report = "";
            if (isset($_POST['Report'])) {
                if ($_POST['Report'] == 'true') {
                    $report = "1";
                } else {
                    $report = "0";
                }
            }
            $insertSQL
                = "INSERT INTO Components (ProductID, ComponentName, DimentionsModifier, Quantity, UnitOfMeasure, UnitCost, Scrap, ComponentInfo, ExtendedCost, Report, CreateDate, AlterDate)  VALUES ('{$_GET['ProductID']}', '{$_POST['ComponentName']}', '{$_POST['DimentionsModifier']}', '{$_POST['Quantity']}', '{$_POST['UnitOfMeasure']}', '{$_POST['UnitCost']}', '{$_POST['Scrap']}', '{$_POST['ComponentInfo']}', '{$_POST['ExtendedCost']}', '{$report}', now(), now() )";

            mysqli_select_db($StarCreations, $database_StarCreations);
            $Result1 = mysqli_query($StarCreations, $insertSQL) or die(mysqli_error($StarCreations));
            $lastInsertId = mysqli_insert_id($StarCreations);
           
            // $Result1 = mysqli_query("SELECT * FROM Components WHERE ComponentID = '{$lastInsertId}'");

            $getSQL = "SELECT * FROM Components WHERE ComponentID = '{$lastInsertId}'";
            $Result1 = mysqli_query($StarCreations, $getSQL) or die(mysqli_error($StarCreations));
            $row = mysqli_fetch_array($Result1);
            //Return result to jTable
            $jTableResult = array();
            $jTableResult['Result'] = "OK";
            $jTableResult['Record'] = $row;
            print json_encode($jTableResult);
        } //Creating a new record (createAction)
        else {
            if ($_GET["action"] == "update") {

                $report = "";
                if (isset($_POST['Report'])) {
                    if ($_POST['Report'] == 'true') {
                        $report = "1";
                    } else {
                        $report = "0";
                    }
                }

                $updateSQL
                    = "UPDATE Components SET  ComponentName='{$_POST['ComponentName']}', DimentionsModifier='{$_POST['DimentionsModifier']}', Quantity='{$_POST['Quantity']}', UnitOfMeasure='{$_POST['UnitOfMeasure']}', UnitCost='{$_POST['UnitCost']}', Scrap='{$_POST['Scrap']}', ExtendedCost='{$_POST['ExtendedCost']}', ComponentInfo='{$_POST['ComponentInfo']}', Report='{$report}', AlterDate=now() WHERE ComponentID='{$_POST['ComponentID']}'";
                mysqli_select_db($StarCreations, $database_StarCreations);
                $Result1 = mysqli_query($StarCreations, $updateSQL)or die(mysqli_error($StarCreations));

                //Return result to jTable
                $jTableResult = array();
                $jTableResult['Result'] = "OK";
                print json_encode($jTableResult);

            } //Creating a new record (createAction)
            else {
                if ($_GET["action"] == "delete") {

                    $deleteSQL = sprintf(
                        "DELETE FROM Components WHERE ComponentID=%s",
                        $_POST['ComponentID']
                    );

                    mysqli_select_db($StarCreations, $database_StarCreations);
                    $Result1 = mysqli_query($StarCreations, $deleteSQL)
                    or die(mysqli_error($StarCreations));

                    //Return result to jTable
                    $jTableResult = array();
                    $jTableResult['Result'] = "OK";
                    print json_encode($jTableResult);

                }
            }
        }
    }


//Close database connection

} catch (Exception $ex) {
    //Return error message
    $jTableResult = array();
    $jTableResult['Result'] = "ERROR";
    $jTableResult['Message'] = $ex->getMessage();
    print json_encode($jTableResult);
}
