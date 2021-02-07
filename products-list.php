<?php require_once('header.php'); ?>
<div class="page-header">
<h1>Products <small>data grid</small></h1>
</div>

<div class="container-fluid">
<div class="row-fluid">
<div class="span11">

<form class="form-search">
<div class="input-append">
 <input type="text" name="pfilter" id="pfilter" class="search-query"  />
        <button  class="btn" type="submit" id="Filter_Button">Search</button>
        </div>
    </form>   

<div id="ProductTableContainer"></div>


<?php 

if ($_SESSION['MM_UserGroup']=="2") { ?>
<div id="myModalDeleteAll"  class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Delete Selected Products</h3>
    </div>
    <div class="modal-body">
        <p>Are you sure you want to Delete the following Products?</p>
        <div id="SelectedRowList"> </div>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
        <button class="btn " role="button" type="submit" id="DeleteAllButton">Delete Selected</button>
    </div>
</div>
<?php } ?>

    
<!--Sidebar content-->


<!--Body content-->
</div>
</div>
</div>

<script type="text/javascript">
function addDecimal(string){
	if(!/[a-z]/gi.test(string)){
		if(!/\w*\.[0-9]{,2}/i.test(string)){
			string=Math.round(Number(string)*100)/100;
		}
		if(!/^\$/i.test(string)){
			string = "$"+string;
		}
		if(!/^\$[0-9]*\./i.test(string)){
			string+=".00";	
		}
		while(!/^\$[0-9]*\.[0-9]{2,}/i.test(string)){
			string+="0";
		}
		return string;
	}else{
		return false;
	}
};
 
    $(document).ready(function () {
 
        $('#ProductTableContainer').jtable({
            title: 'Product List',
				paging: true,
				pageSize: 50,
				sorting: true,
            defaultSorting: 'ProductName ASC',
			selecting: true, //Enable selecting
            multiselect: true, //Allow multiple selecting
            selectingCheckboxes: true, //Show checkboxes on first column
            //selectOnRowClick: false, //Enable this to only select using checkboxes
            //openChildAsAccordion: true, //Enable this line to show child tabes as accordion style
            actions: {
                listAction: 'actions/productdg-crud.php?action=list',
				deleteAction: 'actions/productdg-crud.php?action=delete'
            },
            fields: {
                ProductID: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
 
				ProductCode: {
                    title: 'Code'
                },
                ProductName: {
                    title: 'Name',
					width: '50px',
					display: function (data) { return (('<a href="/products-add.php?ProductID='+data.record.ProductID+'">'+data.record.ProductName+'</a>')) ; }
                },
				PrintNumber: {
                    title: 'Print&nbsp;Number'
                },
				PrintTitle: {
                    title: 'Print&nbspTitle'
                },
				Molding: {
                    title: 'Molding'
                },
               
				TestColumn: {
    			title: 'Size',
				visibility: 'hidden',
    			display: function (data) { if (data.record.PWidth !="" || data.record.PHeight !="") {
        				return ((data.record.PHeight+'x'+data.record.PWidth)) ;} else { return("-")}
				},
				    create: false,
                    edit: false,
                    list: true
					},
                PHeight: {
                    title: 'Height',
					list: false
                },
                PWidth: {
                    title: 'Width',
					list: false
                },
                Cost: {
                    title: 'Cost',
					display:function (data) { return (addDecimal(data.record.Cost)); }
                },
                Price: {
                    title: 'Price',
					visibility: 'hidden',
					display:function (data) {
        				return (addDecimal(data.record.Price));}
				},
                Percentage: {
                    title: '%',
					visibility: 'hidden',
					
                },
                CustomerIDs: {
                    title: 'Customer&nbspIDs',
					list: false
                },
                Discontinued: {
                    title: 'Discontinued',
                    type: 'checkbox',
                    values: { '0': 'Passive', '1': 'Active' },
                    defaultValue: '0',
					list: false
                },
                CreateDate: {
                    title: 'Create&nbspDate',
                    type: 'date',
                    displayFormat: 'yy-mm-dd',
                    create: false,
                    edit: false,
					list: true,
					visibility: 'hidden'					
                },
				AlterDate: {
                    title: 'Alter&nbspDate',
                    type: 'date',
                    displayFormat: 'yy-mm-dd',
                    create: false,
                    edit: false,
					list: true
                }
            },
			
			            //Register to selectionChanged event to hanlde events
            selectionChanged: function () {
                //Get all selected rows
                var $selectedRows = $('#ProductTableContainer').jtable('selectedRows');
 
                $('#SelectedRowList').empty();
                if ($selectedRows.length > 0) {
                    //Show selected rows
                    $selectedRows.each(function () {
                        var record = $(this).data('record');
                        $('#SelectedRowList').append(
                            '<b>Code</b>: ' + record.ProductCode +
                            ' - <b>Name</b>:' + record.ProductName + '<br />'
                            );
                    });
                } else if ($selectedRows.length = 0) {
                    //No rows selected
                    $('#SelectedRowList').append('No row selected! Select rows to see here...');
                }
            },
            rowInserted: function (event, data) {
                if (data.record.ProductCode.indexOf('Test') >= 0) {
                    $('#ProductTableContainer').jtable('selectRows', data.row);
                }
            },
			toolbar: {
				items: [{
        		// text: '<a href="#myModalDeleteAll" role="button" class="btn">Delete Selection</a>'
                text: '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModalDeleteAll"> Delete Selection </button>'
    			}]
},
			
        });
 
        //Load product list from server
        //$('#ProductTableContainer').jtable('load');
        $('#Filter_Button').click(function (e) {
        e.preventDefault();
            $('#ProductTableContainer').jtable('load', {
                pfilter: $('#pfilter').val()
            });
			
    });
    $('#Filter_Button').click();
	        //Delete selected students

        $('#DeleteAllButton').button().click(function () {
            var $selectedRows = $('#ProductTableContainer').jtable('selectedRows');
            $('#ProductTableContainer').jtable('deleteRows', $selectedRows);
            $('#myModalDeleteAll').removeClass('in');
        });

    });
 
</script>



<?php require_once('footer.php');
//mysqli_free_result($productList);
