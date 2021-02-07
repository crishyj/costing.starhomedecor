

(function($){

var table = {
	"add": function ( rows ) {
		for ( var i=0 ; i<rows ; i++ ) {
			table._addRow();
		}

		$('#column_count').val( $('#sci tbody tr').length );
	},

	"_addRow": function () {
		var tbody = $('#sci tbody')[0];
		var order = $('#sci tbody tr').length;
		var tr = $(
			'<tr class="sci_row">'+
				'<td class="order">'+(order+1)+'</td>'+
				'<td><input type="text" name="component_'+order+'" class="sci_component input-medium" placeholder="Component"></td>'+
				'<td><input type="text" name="qty_'+order+'" class="sci_qty input-mini" placeholder="Qty"></td>'+
				'<td><input type="text" name="unitofmeasure_'+order+'" class="sci_unitofmeasure input-mini" placeholder="Type"></td>'+
				'<td><input type="text" name="unicost_'+order+'" class="sci_unitcost input-mini" placeholder="Unit Cost"></td>'+
				'<td><input type="text" name="extendedcost_'+order+'" class="sci_extendedcost input-mini" placeholder="Cost">'+
				'<input type="hidden" name="componentid_'+order+'" class="sci_componentid input-mini" placeholder="ID">'+
				'<input type="text" name="productid_'+order+'" value="'+ $('#ProductID').val() +'" class="sci_productid input-mini" placeholder="PID"></td>'+
				'<td><a href="#" class="close sci_delete" data-dismiss="alert">&times;</a></td>'+
			'</tr>')[0];
		
		tbody.appendChild( tr );
		$('select.sci_type', tr).change();
	},


	"reorder": function () {
		$('#sci tbody tr').each( function (i) {
			$('td:eq(0)', this).html( i+1 );

			$('.sci_label', this).attr('name', 'label_'+i);
			$('.sci_column', this).attr('name', 'column_'+i);
			$('.sci_type', this).attr('name', 'type_'+i);
			$('.sci_default', this).attr('name', 'default_'+i);
			$('.sci_required', this).attr('name', 'required_'+i);
			$('.sci_validation', this).attr('name', 'validation_'+i);
			$('.sci_get', this).attr('name', 'get_'+i);
			$('.sci_set', this).attr('name', 'set_'+i);
		} );
	},


	"remove": function ( tr ) {
		$(tr).remove();
		table.reorder();

		$('#column_count').val( $('#sci tbody tr').length );
	},


	"selectType": function ( type, tr ) {
		var cell_validation = $('td.row_validation', tr).empty()[0];
		var cell_options = $('td.row_options', tr).empty()[0];

		if ( type === 'text' || type === "textarea" || type === "password" ) {
			cell_validation.innerHTML = 
				'<select class="text_options">'+
					'<option value="none">None</option>'+
					'<option value="minLen">Min length:</option>'+
					'<option value="maxLen">Max length:</option>'+
					'<option value="minMaxLen">Min|max length:</option>'+
					'<option value="email">E-mail</option>'+
					'<option value="ip">IP address</option>'+
					'<option value="url">URL</option>'+
				'</select>'+
				'<span class="text_options"></span>';
			$('select', cell_validation).change();
		}
		else if ( type === "radio" || type === "select" || type === "checkbox" ) {
			cell_validation.innerHTML = "None";
			cell_options.innerHTML = '<input type="text">'+
				'<br><span class="small">Use | to separate options</span>';
			$('input', cell_options).keyup( function () {
				$('input.sci_validation', tr).val(this.value);
			} );
			$('input', cell_options).keyup();
		}
		else if ( type === "date" ) {
			cell_validation.innerHTML = "Date format";
			cell_options.innerHTML = 
				'Format:<br>'+
				'<select class="date_options">'+
					'<option value="D, j M y">Fri, 9 Mar 12</option>'+
					'<option value="D, j M Y">Fri, 9 Mar 2012</option>'+
					'<option value="l, d-M-y">Friday, 09-Mar-12</option>'+
					'<option value="Y-m-d">2012-03-09 (yyyy-mm-dd)</option>'+
					'<option value="m-d-y">03-09-2012 (mm-dd-yy)</option>'+
					'<option value="m/d/y">03/09/2012 (mm/dd/yy)</option>'+
					'<option value="d-m-y">09-03-2012 (dd-mm-yy)</option>'+
					'<option value="d/m/y">09/03/2012 (dd/mm/yy)</option>'+
					'<option value="d.m.y">09.03.2012 (dd.mm.yy)</option>'
				'</select>';
			$('select', cell_options).change( function () {
				$('input.sci_validation', tr).val( $(this).val() );
			} );
			$('select', cell_options).change();
		}
		else if ( type === 'hidden' || type === 'readonly' ) {
			cell_validation.innerHTML = "None";
		}
	},

	"selectTypeText": function ( type, tr ) {
		var opts = $('span.text_options', tr).empty()[0];
		$('input.sci_validation', tr).val(type);

		if ( type === "minLen" || type === "maxLen" ) {
			opts.innerHTML = '<br><input type="text">';
			$('input', opts).keyup( function () {
				$('input.sci_validation', tr).val(type+'|'+this.value);
			} );
			$('input', opts).keyup();
		}
		else if ( type === "minLen" || type === "maxLen" || type === "minMaxLen" ) {
			opts.innerHTML = '<br><input type="text"><br><span class="small">Use | to separate min and max</span>';
			$('input', opts).keyup( function () {
				$('input.sci_validation', tr).val(type+'|'+this.value);
			} );
			$('input', opts).keyup();
		}
	}
};



$(document).ready( function () {
	
$('form').bind("keyup", function(e) {
  var code = e.keyCode || e.which; 
  if (code  == 13) {               
    e.preventDefault();
    return false;
  }
});
	
	$('#add_button').click( function (e) {
		if ( $('#ProductID').val() === "" ) {
			e.preventDefault();
			alert( "Create product before adding components." );
			$('input[name=ProductName]').focus();
			return;
		} else {
			e.preventDefault();
		table.add( $('#add_input').val() );
		$('#add_input').val(1);
		table.reorder();
		return;
	}} );

	$('#sci tbody').on('click', '.sci_delete', function () {
		table.remove( $(this).parents('tr')[0] );
	} );
	 
	$( "#sci tbody" ).sortable( {
		"helper": function(e, ui) {
			ui.children().each(function() {
				$(this).width($(this).width());
			});
			return ui;
		},
		"handle": "td:eq(0)",
		"stop": function ( e, ui ) {
			table.reorder();
		}
	} );

	$('#sci tbody').on('change', 'select.sci_type', function () {
		table.selectType( $(this).val(), $(this).parents('tr')[0] );
	} );

	$('#sci tbody').on('change', 'select.text_options', function () {
		table.selectTypeText( $(this).val(), $(this).parents('tr')[0] );
	} );

	table.add( 0 );

	$('#sci_form').submit( function (e) {
		var error = null;
		//e.preventDefault();

		if ( $('.sci_component').val() === "" ) {
			e.preventDefault();
			//alert( "A database table name is required." );
			$('.sci_component').focus();
			return;
		}
//		else if ( $('input[name=db_table_name]').val().match(/[^a-zA-Z0-9_\-]/) ) {
//			e.preventDefault();
//			//alert( "The database table name can only contain letters, numbers underscores and dashes." );
//			$('input[name=db_table_name]').focus();
//			return;
//		}

		$('input.sci_label').each( function () {
			if ( this.value === "" ) {
				error = "All fields must have a label associated with them. This is used for the table header and form label.";
				this.focus();
				return false;
			}
		} );
		if ( error ) {
			e.preventDefault();
			alert( error );
			return;
		}

		$('input.sci_column').each( function () {
			if ( this.value === "" ) {
				error = "All fields must have a DB name. This is used to generate the DB table and populate the DataTables and Editor fields.";
				this.focus();
				return false;
			}
			else if ( this.value.match(/[^a-zA-Z0-9_\-]/) ) {
				error = "Database column names must only contain letters, numbers, underscores or dashes.";
				this.focus();
				return false;
			}
		} );
		if ( error ) {
			e.preventDefault();
			alert( error );
			return;
		}

		$('select.text_options').each( function () {
			var tr = $(this).parents('tr')[0];
			var val = $(this).val();
			var options_val = $('span.text_options input').val();

			if ( val === "minLen" || val === "maxLen" ) {
				if ( ! $.isNumeric(options_val) ) {
					error = "Length must be a numeric value";
					$('span.text_options input').focus();
					return false;
				}
			}
			else if ( val === "minMaxLen" ) {
				var a = options_val.split('|');
				if ( a.length !== 2 || !$.isNumeric(a[0]) || !$.isNumeric(a[1]) ) {
					error = "Min / max length must be given as two numbers, separated by a pipe (|). Currently: '"+options_val+"' - Len: "+a.length;
					$('span.text_options input').focus();
					return false;
				}
			}
		} );
		if ( error ) {
			e.preventDefault();
			alert( error );
			return;
		}
	} );
	
	$('#button_complete').click( function () {
		$('#sci_form')[0].target = "_self";
		$('#action').val('complete');
	} );
	
	$('#button_sql').click( function () {
		$('#sci_form')[0].target = "_self";
		$('#action').val('sql');
	} );
	
	$('#button_html').click( function () {
		$('#sci_form')[0].target = "_blank";
		$('#action').val('html');
	} );
	
	$('#button_js').click( function () {
		$('#sci_form')[0].target = "_blank";
		$('#action').val('js');
	} );
	
	$('#button_sss').click( function () {
		$('#sci_form')[0].target = "_blank";
		$('#action').val('sss');
	} );
} );





}(jQuery));