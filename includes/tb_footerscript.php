<script type="text/javascript">
// check all checkboxes in table
jQuery('.checkall').click(function(){
   var parentTable = jQuery(this).parents('table');										   
   var ch = parentTable.find('tbody input[type=checkbox]');
   if(jQuery(this).is(':checked')) {			
      //check all rows in table
      ch.each(function(){ 
         jQuery(this).attr('checked',true);
         jQuery(this).parent().addClass('checked');	//used for the custom checkbox style
         jQuery(this).parents('tr').addClass('selected'); // to highlight row as selected
      });			
   } else {		
      //uncheck all rows in table
      ch.each(function(){ 
         jQuery(this).attr('checked',false); 
         jQuery(this).parent().removeClass('checked');	//used for the custom checkbox style
         jQuery(this).parents('tr').removeClass('selected');
      });	
   }
});
</script>

<script>
   $(document).ready(function()
   {
      var a = $(".rightpanel").height();
      $(".leftpanel").height(a);
   });

   jQuery('.expand-one').click(function(){
      jQuery('.content-one').slideToggle('slow');
   });
</script>