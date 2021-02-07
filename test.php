<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>

        <script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript" charset="utf-8"></script>
		<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/tags/jquery.fcbkcomplete.js" type="text/javascript" charset="utf-8"></script>
        <script type="text/javascript" src="js/bootstrap.min.js"></script>
		<script src="js/generator.js"></script>
<script tyype="text/javascript">
jQuery.noConflict();
jQuery(document).ready(function(){

  // Load Results
 jQuery.getJSON("data.php", 
    function(data) {
    drawTable(data);
 });
});

function drawTable(data) {

 jQuery("#taskList").empty();
 jQuery("#taskList").append('<tbody>');
 jQuery.each(data, function(key,row){
    if( key%2 == 0 ) oddeven=0; else oddeven=1;
    jQuery("#taskList").find('tbody')
    .append(jQuery( '<tr>' )
       .attr( 'id', row.id )
       .attr( 'class', 'row' + oddeven )
          .append(jQuery('<td>')
          .attr('class','dragHandle')
          .attr('style','height:32px;width:32px;')
          .html(' ') )    
             .append(jQuery('<td>')
             .append(jQuery('<span>')
             .attr('id', 'task' + row.id)
             .text( row.notes) 
             .attr('onclick','editTask("' + row.id + '");') )
             .append(jQuery('<input>')
             .attr('type', 'hidden')
             .attr('style', 'width:195px;')                    
             .attr('value', row.notes)
             .attr('id', 'taskNotes' + row.id) ) )
          .append(jQuery('<td>')
          .attr('class', 'tdDelete') 
             .append(jQuery('<a>')
             .attr('class', 'icon-16-save')
             .attr('href', 'javascript: void(0)')
             .attr('onclick', 'saveTask("' + row.id + '");')
             .append(jQuery('<span>')
             .text('Save') ) ) )                                        
         .append(jQuery('<td>')
         .attr('class', 'tdDelete') 
         .append(jQuery('<a>')
         .attr('class', 'icon-16-delete')
         .attr('href', 'javascript: void(0)')
         .attr('onclick', 'deleteTask("' + row.id + '");')
         .append(jQuery('<span>')
         .text('Save') ) ) )                                        
    );

 });

}

/* Edit Task : How to change using jQuery the type from hidden to text */
function editTask( id ) {
 marker = jQuery('span#task'+id).insertBefore('#taskNotes' + id);
 jQuery('#taskNotes'+id).detach().attr('type','text').insertAfter(marker);
 marker.remove();

}

/* Save Task */
function saveTask( id ) {
 jQuery.getJSON("savetask.php?id=" + id + "&notes=" + escape(jQuery("#taskNotes" + id).val() ), 
 function(data) {
 drawTable(data);
 });
}

/* Delete Task */
function deleteTask( id ) {
 jQuery.getJSON("deletetask.php?id=" + id , 
 function(data) {
 drawTable(data);
 });
}

</script>
</head>

<body>
<table id="taskList" class="taskList">
<tbody /> 
</table>

</body>
</html>