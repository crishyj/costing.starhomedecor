<?php require_once('header.php'); ?>
<div class="page-header">
<h1>Users <small>data grid</small></h1>
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
    
<div id="UsersTableContainer"></div>
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
			$('#UsersTableContainer').jtable({
            title: 'Users List',
				paging: true,
				pageSize: 50,
				sorting: true,
                defaultSorting: 'FirstName ASC',			
				actions: {
					listAction:   'actions/userdg-crud.php?action=list',
					updateAction: 'actions/userdg-crud.php?action=update',
				},
				fields: {
                ID: {
                    key: true,
					list: false
                },
				TestColumn: {
    			title: 'Name',
				width: 'auto',
    			display: function (data) { if (data.record.FirstName !="" || data.record.LastName !="") {
        				return ((data.record.FirstName+' '+data.record.LastName)) ;} else { return("-")}
				},
				    create: false,
                    edit: false,
                    list: true
					},
                FirstName: {
                    title: 'First Name',
                    width: 'auto',
					inputClass: 'validate[required]',
					list: false
	
                },
				LastName: {
                    title: 'Last Name',
                    width: 'auto',
					inputClass: 'validate[required]',
					list: false
	
                },
				EmailAddress: {
                    title: 'Email',
                    width: 'auto',
					inputClass: 'validate[required]',
					edit: false
	
                },
				Password: {
                    title: 'Password',
                    width: 'auto',
					inputClass: 'validate[required] showpassword',
					list: false,
					type:'password'
	
                },
				Privilege: {
                    title: 'Type',
                    width: 'auto',
					options: { '1': 'User', '2': 'Administrator' },
					inputClass: 'validate[required]',
                    edit: false
					
	
                },
				CreateDate: {
                    title: 'CreateDate',
                    type: 'date',
                    displayFormat: 'yy-mm-dd',
                    create: false,
                    edit: false,
					list: false
                },
				AlterDate: {
                    title: 'AlterDate',
                    type: 'date',
                    displayFormat: 'yy-mm-dd',
                    create: false,
                    edit: false,
					list: false
                }				
				},
				            //Initialize validation logic when a form is created
            //formCreated: function (event, data) {
            //    data.form.validationEngine();
            //},
            //Validate form when it is being submitted
            formSubmitting: function (event, data) {
                return data.form.validationEngine('validate');
            },
            //Dispose validation logic when form is closed
            formClosed: function (event, data) {
                data.form.validationEngine('hide');
                data.form.validationEngine('detach');
            },
			formCreated: function (event, data) {
               		$('.showpassword').each(function(index,input) {
						var $input = $(input);
						var $myphp =  '<?php echo("test") ?>';
						$('<span>').append(
							$("<input type='checkbox' class='showpasswordcheckbox  '+ $myphp +'' />").click(function() {
								var change = $(this).is(":checked") ? "text" : "password";
								var rep = $("<input type='" + change + "' />")
									.attr("id", $input.attr("id"))
									.attr("name", $input.attr("name"))
									.attr('class', $input.attr('class'))
									.val($input.val())
									.insertBefore($input);
								$input.remove();
								$input = rep;
							 })
						).append($("<span/>").text("pw")).insertAfter($input);
				});
			data.form.validationEngine();	
            }	
			});

			//Load person list from server
 
        //Load product list from server
        //$('#ProductTableContainer').jtable('load');
        $('#Filter_Button').click(function (e) {
        e.preventDefault();
            $('#UsersTableContainer').jtable('load', {
                pfilter: $('#pfilter').val()
            });
    });
    $('#Filter_Button').click();
    });
 
</script>



<?php require_once('footer.php'); ?>

