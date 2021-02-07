<html>
  <head>

    <link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="scripts/jtable/themes/lightcolor/gray/jtable.css">
	

        <script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
	<script type="text/javascript" src="js/jquery.jtable.min.js"></script>
	
  </head>
  <body>
	<div id="PeopleTableContainer" style="width: 600px;"></div>
	<script type="text/javascript">
	

		$(document).ready(function () {
			

		    //Prepare jTable
			$('#PeopleTableContainer').jtable({
				deleteConfirmation: function(data) {data.deleteConfirmMessage = 'Are you sure to delete product ' + data.record.ProductCode + '?';},
				title: 'Products List',
				paging: true,
            	sorting: true,
            	defaultSorting: 'ProductCode ASC',				
				actions: {
					listAction: 'PersonActions.php?action=list',
					createAction: 'PersonActions.php?action=create',
					updateAction: 'PersonActions.php?action=update',
					deleteAction: 'PersonActions.php?action=delete'
				},
				fields: {
					ProductID: {
						key: true,
						create: false,
						edit: false,
						list: false
					},
					ProductCode: {
						title: 'ProductCode',
						width: 'auto'
					},
//					SupplierIDs: {title:'SupplierIDs',create: false,edit: false},
//					Description: {title:'Description',create: false,edit: false},
//					StandardCost: {title:'StandardCost',create: false,edit: false},
//					ListPrice: {title:'ListPrice',create: false,edit: false},
//					ReorderLevel: {title:'ReorderLevel',create: false,edit: false},
//					TargetLevel: {title:'TargetLevel',create: false,edit: false},
//					QuantityPerUnit: {title:'QuantityPerUnit',create: false,edit: false},
//					Discontinued: {title:'Discontinued',create: false,edit: false},
//					MinimumReorderQuantity: {title:'MinimumReorderQuantity',create: false,edit: false},
//					Category: {title:'Category',create: false,edit: false},
//					Discontinued: {title:'Discontinued',create: false,edit: false},
					ProductName: {
						title: 'Product Name',
						width: 'auto'
					},
					RecordDate: {
						title: 'Record date',
						width: 'auto',
						type: 'date',
						create: false,
						edit: false
					}
				}
			});

			//Load person list from server
			$('#PeopleTableContainer').jtable('load');

		});

	</script>
 
  </body>
</html>
