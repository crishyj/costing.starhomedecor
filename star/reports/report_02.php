<?php require_once('../header.php'); ?>
<div class="page-header">
<h1>Report <small>by report number</small></h1>
</div>

<div class="container-fluid">
<div class="row-fluid">
<div class="span11">

<form class="form-search">
<div class="input-append">
 <input type="text" name="pfilter" id="pfilter" class="search-query" placeholder="Code & Name"  />
        <button  class="btn" type="submit" id="Filter_Button">Search</button>
        </div>
    </form>   

<div id="ProductTableContainer"></div>



    
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
			selecting: false, //Enable selecting
            multiselect: false, //Allow multiple selecting
            selectingCheckboxes: false, //Show checkboxes on first column
            //selectOnRowClick: false, //Enable this to only select using checkboxes
            //openChildAsAccordion: true, //Enable this line to show child tabes as accordion style
            actions: {
                listAction: '/actions/reportdg-crud.php?action=list',
            },
            fields: {
                ProductID: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
 
				Reports: {
                    title: 'Report',
					display: function (data) { 
					var vidor = data.record.Reports.split(',').join('</li><li><a tabindex="-1" href="javascript:void(0);">');
					var vidorcnt = data.record.Reports.split(',').length;
 
                     return ('<div class="btn-group"><a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0);"><span class="badge">'+vidorcnt+'</span></a><ul class="dropdown-menu"><li><a tabindex="-1" href="javascript:void(0);">'+vidor+'</li></ul></div>')                   
   

					}

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
                    title: 'Print Number'
                },
				PrintTitle: {
                    title: 'Print Title'
                },
				Molding: {
                    title: 'Outer Molding',
					visibility: 'hidden',
					
                },
				InnerMolding: {
                    title: 'Inner Molding',
					visibility: 'hidden',
					
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
        icon: '/images/excel.png',
        text: 'Export to Excel',
        click: function () {
			window.open("report_02_xls.php?rpt=" + $('#pfilter').val());
        }
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
        });

    });
 
</script>



<?php require_once('../footer.php'); ?>
<?php
mysql_free_result($productList);
?>
