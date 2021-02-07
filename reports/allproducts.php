<?php require_once('../Connections/StarCreations.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "")
    {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysqli_real_escape_string($mysqli,$theValue) : mysqli_escape_string($mysqli,$theValue);

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
$query_allCustomers = "SELECT CustomersID, Company FROM Customers ORDER BY Company ASC";
$allCustomers = mysqli_query($StarCreations,$query_allCustomers) or die(mysqli_error($mysqli));
$row_allCustomers = mysqli_fetch_assoc($allCustomers);
$totalRows_allCustomers = mysqli_num_rows($allCustomers);


mysqli_select_db($StarCreations,$database_StarCreations);
$query_allCustomersTab = "SELECT CustomersID, Company FROM Customers ORDER BY Company ASC";
$allCustomersTab = mysqli_query($StarCreations,$query_allCustomersTab) or die(mysqli_error($mysqli));
$row_allCustomersTab = mysqli_fetch_assoc($allCustomersTab);
$totalRows_allCustomersTab = mysqli_num_rows($allCustomersTab);

?>
<?php require_once('../header.php'); ?>

    <div class="page-header">
        <h1>All Products <small>By Customer</small></h1>
    </div>

    <div class="container-fluid">
        <div class="row-fluid">
            <div class="span12">

                <ul class="nav nav-tabs" id="myTab">
                    <?php do { ?>
                        <li><a href="#<?php echo $row_allCustomersTab['CustomersID']; ?>"><?php echo $row_allCustomersTab['Company']; ?></a></li>
                    <?php } while ($row_allCustomersTab = mysqli_fetch_assoc($allCustomersTab)); ?>
                </ul>

                <div class="tab-content">
                    <?php do { ?>
                        <?php
                        mysqli_select_db($StarCreations,$database_StarCreations);
                        $query_allproducts = "SELECT * FROM Products WHERE CustomerIDs REGEXP '(^|,)". $row_allCustomers['CustomersID'] . "(,|$)' AND Discontinued= '0' ORDER BY ProductCode ASC";
                        $allproducts = mysqli_query($StarCreations,$query_allproducts) or die(mysqli_error($mysqli));
                        $row_allproducts = mysqli_fetch_assoc($allproducts);
                        $totalRows_allproducts = mysqli_num_rows($allproducts);
                        ?>
                        <div class="tab-pane" id="<?php echo $row_allCustomers['CustomersID']; ?>">
                            <?php do { ?>



                                <?php
                                mysqli_select_db($StarCreations,$database_StarCreations);
                                $query_allcomponents = "SELECT * FROM Components WHERE ProductID = '". $row_allproducts['ProductID'] . "' ORDER BY ComponentName ASC";
                                $allcomponents = mysqli_query($StarCreations,$query_allcomponents) or die(mysqli_error($mysqli));
                                $row_allcomponents = mysqli_fetch_assoc($allcomponents);
                                $totalRows_allcomponents = mysqli_num_rows($allcomponents);
                                ?>


                                <table class="table table-bordered">
                                    <caption><a href="/products-add.php?ProductID=<?php echo $row_allproducts['ProductID']; ?>"><?php echo $row_allproducts['ProductCode']; ?> - <?php echo $row_allproducts['ProductName']; ?> - <?php echo $row_allproducts['PHeight']; ?>x<?php echo $row_allproducts['PWidth']; ?></a><br></caption>
                                    <thead>
                                    <tr>
                                        <th>Component</th>
                                        <th>Width</th>
                                        <th>Qty</th>
                                        <th>Unit of Measure</th>
                                        <th>Scrap</th>
                                        <th>Cost</th>
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
                                    <?php } while ($row_allcomponents = mysqli_fetch_assoc($allcomponents)); ?>
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

                            <?php } while ($row_allproducts = mysqli_fetch_assoc($allproducts)); ?>
                        </div>

                    <?php } while ($row_allCustomers = mysqli_fetch_assoc($allCustomers)); ?>
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
mysqli_free_result($allCustomers);

mysqli_free_result($allproducts);

mysqli_free_result($allcomponents);


?>