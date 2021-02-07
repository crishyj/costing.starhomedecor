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

mysql_select_db($database_StarCreations, $StarCreations);
$query_allCustomers = "SELECT CustomersID, Company FROM Customers ORDER BY Company ASC";
$allCustomers = mysql_query($query_allCustomers, $StarCreations) or die(mysql_error());
$row_allCustomers = mysql_fetch_assoc($allCustomers);
$totalRows_allCustomers = mysql_num_rows($allCustomers);


mysql_select_db($database_StarCreations, $StarCreations);
$query_allCustomersTab = "SELECT CustomersID, Company FROM Customers ORDER BY Company ASC";
$allCustomersTab = mysql_query($query_allCustomersTab, $StarCreations) or die(mysql_error());
$row_allCustomersTab = mysql_fetch_assoc($allCustomersTab);
$totalRows_allCustomersTab = mysql_num_rows($allCustomersTab);

?>
<?php require_once('../header.php'); ?>
 
 <div class="page-header">
<h1>All Products <small>By Customer</small></h1>
</div>

<div class="container-fluid">
<div class="row-fluid">
<div class="span6">

 <ul class="nav nav-tabs" id="myTab">
  <?php do { ?>
    <li><a href="#<?php echo $row_allCustomersTab['CustomersID']; ?>"><?php echo $row_allCustomersTab['Company']; ?></a></li>
    <?php } while ($row_allCustomersTab = mysql_fetch_assoc($allCustomersTab)); ?>
</ul>
 
<div class="tab-content">
	<?php do { ?>
    <?php 
    mysql_select_db($database_StarCreations, $StarCreations);
    $query_allproducts = "SELECT * FROM Products WHERE CustomerIDs REGEXP '(^|,)". $row_allCustomers['CustomersID'] . "(,|$)' ORDER BY ProductCode ASC";
    $allproducts = mysql_query($query_allproducts, $StarCreations) or die(mysql_error());
    $row_allproducts = mysql_fetch_assoc($allproducts);
    $totalRows_allproducts = mysql_num_rows($allproducts);
    ?>
    <div class="tab-pane" id="<?php echo $row_allCustomers['CustomersID']; ?>">
		<?php do { ?>
        
        
        
        <?php 
        mysql_select_db($database_StarCreations, $StarCreations);
        $query_allcomponents = "SELECT * FROM Components WHERE ProductID = '". $row_allproducts['ProductID'] . "' ORDER BY ComponentName ASC";
        $allcomponents = mysql_query($query_allcomponents, $StarCreations) or die(mysql_error());
        $row_allcomponents = mysql_fetch_assoc($allcomponents);
        $totalRows_allcomponents = mysql_num_rows($allcomponents);
        ?>
        
       
        <table class="table table-bordered">
         <caption><?php echo $row_allproducts['ProductCode']; ?> - <?php echo $row_allproducts['ProductName']; ?> - <?php echo $row_allproducts['PHeight']; ?>x<?php echo $row_allproducts['PWidth']; ?><br>Print Publisher: <?php echo $row_allproducts['PrintPublisher']; ?></caption>
            <thead>
            <tr>
            <th>Component</th>
            <th>Width</th>
            <th>Qty</th>
            <th>Unit of Measure</th>
            <th>Cost</th>
            <th>Scrap</th>
            <th>Extended Cost</th>
            
            </tr>
            </thead>
            <tbody>
        <?php do { ?>
        <tr>
        <td><?php echo $row_allcomponents['ComponentName']; ?></td>
		<td><?php echo $row_allcomponents['DimentionsModifier']; ?></td>
        <td><?php echo $row_allcomponents['Quantity']; ?></td>
        <td><?php echo $row_allcomponents['UnitOfMeasure']; ?></td>
        <td><?php echo $row_allcomponents['Scrap']; ?></td>
        <td><?php echo $row_allcomponents['UnitCost']; ?></td>
        <td><?php echo $row_allcomponents['ExtendedCost']; ?></td>
        </tr>
        <?php } while ($row_allcomponents = mysql_fetch_assoc($allcomponents)); ?>
        <tr>
        <td colspan="5">
        <td>Total Cost</td>
        <td>$<?php echo $row_allproducts['TotalCost']; ?></td>
        </tr>
        <tr>
        <td colspan="5">
        <td>Total</td>
        <td>$<?php echo $row_allproducts['TotalPrice']; ?></td>
        </tr>
        <tr>
        <td colspan="6">
        <td><?php echo $row_allproducts['Percentage']; ?>%</td>
        </tr>
        </tbody>
        </table>
        
        <?php } while ($row_allproducts = mysql_fetch_assoc($allproducts)); ?>
    </div>
    
    <?php } while ($row_allCustomers = mysql_fetch_assoc($allCustomers)); ?>
</div>
    <script>
$(function () {
    $('#myTab a').click(function (e) {
    e.preventDefault();
    $(this).tab('show');
    })
$('#myTab a:first').tab('show');
})
</script>
</div>
</div>
</div>
<?php require_once('../footer.php'); ?>
<?php
mysql_free_result($allCustomers);

mysql_free_result($allproducts);

mysql_free_result($allcomponents);


?>
