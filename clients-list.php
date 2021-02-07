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
 
        $('#ProductTableContainer').jtable({
            title: 'Customer List',
				paging: true,
				pageSize: 50,
				sorting: true,
            defaultSorting: 'Company ASC',
            //openChildAsAccordion: true, //Enable this line to show child tabes as accordion style
            actions: {
                listAction: 'actions/clientsdg-crud.php?action=list',
				createAction: 'actions/clientsdg-crud.php?action=create',
				updateAction: 'actions/clientsdg-crud.php?action=update',
				deleteAction: 'actions/clientsdg-crud.php?action=delete'
            },
            fields: {
                CustomersID: {
                    key: true,
                    create: false,
                    edit: false,
                    list: false
                },
                //CHILD TABLE DEFINITION FOR "PHONE NUMBERS"
//                Phones: {
//                    title: '',
//                    width: '5%',
//                    sorting: false,
//                    edit: false,
//                    create: false,
//                    display: function (productData) {
//                        //Create an image that will be used to open child table
//                        var $img = $('<img src="/Content/images/Misc/phone.png" title="Edit phone numbers" />');
//                        //Open child table when user clicks the image
//                        $img.click(function () {
//                            $('#ProductTableContainer').jtable('openChildTable',
//                                    $img.closest('tr'),
//                                    {
//                                        title: productData.record.Name + ' - Phone numbers',
//                                        actions: {
//                                            listAction: '/Demo/PhoneList?ProductId=' + productData.record.ProductId,
//                                            deleteAction: '/Demo/DeletePhone',
//                                            updateAction: '/Demo/UpdatePhone',
//                                            createAction: '/Demo/CreatePhone'
//                                        },
//                                        fields: {
//                                            ProductId: {
//                                                type: 'hidden',
//                                                defaultValue: productData.record.ProductId
//                                            },
//                                            PhoneId: {
//                                                key: true,
//                                                create: false,
//                                                edit: false,
//                                                list: false
//                                            },
//                                            PhoneType: {
//                                                title: 'Phone type',
//                                                width: '30%',
//                                                options: { '1': 'Home phone', '2': 'Office phone', '3': 'Cell phone' }
//                                            },
//                                            Number: {
//                                                title: 'Phone Number',
//                                                width: '30%'
//                                            },
//                                            RecordDate: {
//                                                title: 'Record date',
//                                                width: '20%',
//                                                type: 'date',
//                                                displayFormat: 'yy-mm-dd',
//                                                create: false,
//                                                edit: false
//                                            }
//                                        }
//                                    }, function (data) { //opened handler
//                                        data.childTable.jtable('load');
//                                    });
//                        });
//                        //Return image to show on the person row
//                        return $img;
//                    }
//                },
//                //CHILD TABLE DEFINITION FOR "EXAMS"
//                Exams: {
//                    title: '',
//                    width: '5%',
//                    sorting: false,
//                    edit: false,
//                    create: false,
//                    display: function (productData) {
//                        //Create an image that will be used to open child table
//                        var $img = $('<img src="/Content/images/Misc/note.png" title="Edit exam results" />');
//                        //Open child table when user clicks the image
//                        $img.click(function () {
//                            $('#ProductTableContainer').jtable('openChildTable',
//                                    $img.closest('tr'), //Parent row
//                                    {
//                                    title: productData.record.Name + ' - Exam Results',
//                                    actions: {
//                                        listAction: '/Demo/ExamList?ProductId=' + productData.record.ProductId,
//                                        deleteAction: '/Demo/DeleteExam',
//                                        updateAction: '/Demo/UpdateExam',
//                                        createAction: '/Demo/CreateExam'
//                                    },
//                                    fields: {
//                                        ProductId: {
//                                            type: 'hidden',
//                                            defaultValue: productData.record.ProductId
//                                        },
//                                        ProductExamId: {
//                                            key: true,
//                                            create: false,
//                                            edit: false,
//                                            list: false
//                                        },
//                                        CourseName: {
//                                            title: 'Course name',
//                                            width: '40%'
//                                        },
//                                        ExamDate: {
//                                            title: 'Exam date',
//                                            width: '30%',
//                                            type: 'date',
//                                            displayFormat: 'yy-mm-dd'
//                                        },
//                                        Degree: {
//                                            title: 'Degree',
//                                            width: '10%',
//                                            options: ["AA", "BA", "BB", "CB", "CC", "DC", "DD", "FF"]
//                                        }
//                                    }
//                                }, function (data) { //opened handler
//                                    data.childTable.jtable('load');
//                                });
//                        });
//                        //Return image to show on the person row
//                        return $img;
//                    }
//                }, Company, EmailAddress, BusinessPhone, FaxNumber, Address, City, StateProvince, ZIPPostal, WebPage, Notes,

                Company: {
                    title: 'Name',
					width: 'auto'
                },
				EmailAddress: {
                    title: 'Email',
                    width: 'auto',
					inputClass: 'validate[required]'
                },
				BusinessPhone: {
                    title: 'Phone',
                    width: 'auto',
					inputClass: 'validate[required]'
                },
				FaxNumber: {
                    title: 'Fax',
                    width: 'auto',
					inputClass: 'validate[required]',
					list: false
                },
				Address: {
                    title: 'Address',
                    width: 'auto',
					inputClass: 'validate[required]',
					list: false
                },
				City: {
                    title: 'City',
                    width: 'auto',
					inputClass: 'validate[required]'
                },
				StateProvince: {
                    title: 'State',
                    width: 'auto',
					inputClass: 'validate[required]'
                },
				ZIPPostal: {
                    title: 'ZIP',
                    width: 'auto',
					inputClass: 'validate[required]',
					list: false
                },
				WebPage: {
                    title: 'Web Page',
                    width: 'auto',
					inputClass: 'validate[required]',
					list: false
                },
				Notes: {
                    title: 'Notes',
                    width: 'auto',
					inputClass: 'validate[required]',
					list: false,
					type: 'textarea'
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
            }
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
    });
 
</script>



<?php require_once('footer.php'); ?>
