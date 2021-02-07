<?php require_once('header.php'); ?>
<div class="page-header">
<h1>Components <small>data grid</small></h1>
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
    
<div id="ComponentTableContainer"></div>
<!--Sidebar content-->

 </div>
<div class="span1">

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
 
		    //Prepare jTable
			$('#ComponentTableContainer').jtable({
            title: 'Components List',
				paging: true,
				pageSize: 50,
				sorting: true,
            defaultSorting: 'ItemNumber ASC',			
				actions: {
					listAction:   'actions/componentdg-crud.php?action=list',
					createAction: 'actions/componentdg-crud.php?action=create',
					updateAction: 'actions/componentdg-crud.php?action=update',
					deleteAction: 'actions/componentdg-crud.php?action=delete'
				},
				fields: {
                ComponentID: {
                    key: true,
					list: false
                },
                ItemNumber: {
                    title: 'Item Number',
                    width: 'auto',
					inputClass: 'validate[required]'
	
                },
                ComponentName: {
                    title: 'Component',
                    width: 'auto',
					inputClass: 'validate[required]'
                },
                ComponentClass: {
                    title: 'Component Class',
                    width: 'auto',
					inputClass: 'validate[required]'
                },
				DimentionsModifier: {
                    title: 'Width (in)',
                    width: 'auto',
					list: false
                },
				UnitOfMeasure: {
                    title: 'Unit',
                    width: 'auto'
                },
				UnitCost: {
                    title: 'Unit Cost',
                    width: 'auto',
					inputClass: 'validate[required,custom[number]]'	
                },
				CreateDate: {
                    title: 'CreateDate',
                    type: 'date',
                    displayFormat: 'yy-mm-dd',
                    create: false,
                    edit: false,
					list: true
                },
				AlterDate: {
                    title: 'AlterDate',
                    type: 'date',
                    displayFormat: 'yy-mm-dd',
                    create: false,
                    edit: false,
					list: true
                },
				DefaultComponent: {
                    title: 'Template',
                    type: 'checkbox',
                    values: { '0': '', '1': 'Yes' },
                    defaultValue: '0',
					list: true
                }
				},
				            //Initialize validation logic when a form is created
            formCreated: function (event, data) {
                data.form.validationEngine();
            },
            //Validate form when it is being submitted
            formSubmitting: function (event, data) {
                return data.form.validationEngine('validate');
            },
            //Dispose validation logic when form is closed
            formClosed: function (event, data) {
                data.form.validationEngine('hide');
                data.form.validationEngine('detach');
            }
				
			});

			//Load person list from server
 
        //Load product list from server
        //$('#ProductTableContainer').jtable('load');
        $('#Filter_Button').click(function (e) {
        e.preventDefault();
            $('#ComponentTableContainer').jtable('load', {
                pfilter: $('#pfilter').val()
            });
    });
    $('#Filter_Button').click();
    });
 
</script>



<?php require_once('footer.php'); ?>
<?php
mysql_free_result($productList);
?>
