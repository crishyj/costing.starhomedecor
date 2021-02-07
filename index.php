<?php
require_once('Connections/StarCreations.php');
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

mysqli_select_db($StarCreations,$database_StarCreations);
$query_productRecentM = "SELECT * FROM Products ORDER BY AlterDate DESC LIMIT 5";
$productRecentM = mysqli_query($StarCreations,$query_productRecentM) or die(mysqli_error($StarCreations));
$row_productRecentM = mysqli_fetch_assoc($productRecentM);
$totalRows_productRecentM = mysqli_num_rows($productRecentM);

mysqli_select_db($StarCreations,$database_StarCreations);
$query_productRecentC = "SELECT * FROM Products ORDER BY CreateDate DESC LIMIT 5";
$productRecentC = mysqli_query($StarCreations,$query_productRecentC) or die(mysqli_error($StarCreations));
$row_productRecentC = mysqli_fetch_assoc($productRecentC);
$totalRows_productRecentC = mysqli_num_rows($productRecentC);



?>
<?php require_once('header.php'); ?>
<div class="page-header">
<h1>Dashboard <small>Welcome</small></h1>
</div>

<div class="container-fluid">
<div class="row-fluid">
<div class="span6">
  <h3>Recently Added Products</h3>
  <table  class="table table-striped">
  <thead>
    <tr>
    <th>Code</th>
      <th>Name</th>
      <th>LxW</th>
      <th>Cost</th>
      <th>Price</th>
      <th>Confirmed</th>
    </tr>
    </thead>
    <tbody>
    <?php do { ?>
      <tr>
        <td><a href="products-add.php?ProductID=<?php echo $row_productRecentC['ProductID']; ?>"><?php echo $row_productRecentC['ProductCode']; ?>&nbsp; </a></td>
        <td><?php echo $row_productRecentC['ProductName']; ?>&nbsp; </td>
        <td><?php echo $row_productRecentC['PHeight']; ?>x<?php echo $row_productRecentC['PWidth']; ?>&nbsp; </td>
        <td title="<?php echo $row_productRecentC['Cost']; ?>">$<?php echo number_format($row_productRecentC['Cost'],2); ?></td>
        <td title="<?php echo $row_productRecentC['Price']; ?>">$<?php echo number_format($row_productRecentC['Price'],2); ?></td>
        <td><span style="width:45px; text-align:center;" class="label <?php if ($row_productRecentC['Confirmed']==0 ) {; ?>label-important"><i class="icon-remove icon-white"></i><?php } else {; ?>label-success"><i class="icon-ok icon-white"></i><?php } ?><span></td>
      </tr>
      <?php } while ($row_productRecentC = mysqli_fetch_assoc($productRecentC)); ?>
      </tbody>
  </table>
</div>
<div class="span6">
  <h3>Recently Modified</h3>
  <table  class="table table-striped">
  <thead>
    <tr>
    <th>Code</th>
      <th>Name</th>
      <th>HxW</th>
      <th>Cost</th>
      <th>Price</th>
      <th>Status</th>
    </tr>
    </thead>
    <tbody>
    <?php do { ?>
      <tr>
        <td><a href="products-add.php?ProductID=<?php echo $row_productRecentM['ProductID']; ?>"><?php echo $row_productRecentM['ProductCode']; ?>&nbsp; </a></td>
        <td><?php echo $row_productRecentM['ProductName']; ?>&nbsp; </td>
        <td><?php echo $row_productRecentM['PHeight']; ?>x<?php echo $row_productRecentM['PWidth']; ?>&nbsp; </td>
        <td title="<?php echo $row_productRecentM['Cost']; ?>">$<?php echo number_format($row_productRecentM['Cost'],2); ?></td>
        <td title="<?php echo $row_productRecentM['Price']; ?>">$<?php echo number_format($row_productRecentM['Price'],2); ?></td>
        <td><span style="width:45px; text-align:center;" class="label <?php if ($row_productRecentM['Confirmed']==0 ) {; ?>label-important"><i class="icon-remove icon-white"></i><?php } else {; ?>label-success"><i class="icon-ok icon-white"></i><?php } ?><span></td>
      </tr>
      <?php } while ($row_productRecentM = mysqli_fetch_assoc($productRecentM)); ?>
      </tbody>
  </table>
</div>
</div>
</div>
<?php require_once('footer.php'); ?>
<?php
mysqli_free_result($productRecentM);

mysqli_free_result($productRecentC);

//mysqli_free_result($productRecentM);
