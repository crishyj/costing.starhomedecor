<?php require_once('Connections/StarCreations.php'); ?>
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

if ((isset($_POST["MM_insert"])) && ($_POST["MM_insert"] == "form1")) {
  $insertSQL = sprintf("INSERT INTO Products (SupplierIDs, ProductName) VALUES (%s, %s)",
                       GetSQLValueString($_POST['tess'], "text"),
                       GetSQLValueString($_POST['test'], "text"));

  mysql_select_db($database_StarCreations, $StarCreations);
  $Result1 = mysql_query($insertSQL, $StarCreations) or die(mysql_error());
}

mysql_select_db($database_StarCreations, $StarCreations);
$query_rs = "SELECT * FROM Products";
$rs = mysql_query($query_rs, $StarCreations) or die(mysql_error());
$row_rs = mysql_fetch_assoc($rs);
$totalRows_rs = mysql_num_rows($rs);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<form id="form1" name="form1" method="POST" action="<?php echo $editFormAction; ?>">
  <p>
    <label for="test"></label>
    <input type="text" name="test" id="test" />
  </p>
  <p>
    <label for="tess"></label>
    <select name="tess" size="4" multiple="multiple" id="tess">
      <option value="1" <?php if (!(strcmp(1, $row_rs['SupplierIDs']))) {echo "selected=\"selected\"";} ?>>1</option>
      <option value="2" <?php if (!(strcmp(2, $row_rs['SupplierIDs']))) {echo "selected=\"selected\"";} ?>>2</option>
      <option value="3" <?php if (!(strcmp(3, $row_rs['SupplierIDs']))) {echo "selected=\"selected\"";} ?>>3</option>
      <?php
do {  
?>
      <option value="<?php echo $row_rs['SupplierIDs']?>"<?php if (!(strcmp($row_rs['SupplierIDs'], $row_rs['SupplierIDs']))) {echo "selected=\"selected\"";} ?>><?php echo $row_rs['SupplierIDs']?></option>
      <?php
} while ($row_rs = mysql_fetch_assoc($rs));
  $rows = mysql_num_rows($rs);
  if($rows > 0) {
      mysql_data_seek($rs, 0);
	  $row_rs = mysql_fetch_assoc($rs);
  }
?>
    </select>
  </p>
  <input type="hidden" name="MM_insert" value="form1" />
  <input type="submit" name="button" id="button" value="Submit" />
</form>
</body>
</html>
<?php
mysql_free_result($rs);
?>
