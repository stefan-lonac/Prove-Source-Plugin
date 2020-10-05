// Admin Plugin Panel (Settings) table script FILTER

jQuery(document).ready(function(){

    // Search table
    jQuery("#admin-search-table-pv").on("keyup", function() {

        var value = jQuery(this).val().toLowerCase();
        jQuery("#table-admin-ps tbody tr").filter(function() {
            jQuery(this).toggle(jQuery(this).text().toLowerCase().indexOf(value) > -1)
        });

    });


    // Sorting table
    var table = jQuery('#table-admin-ps');
    jQuery('#name-ps, #email-ps')
        .wrapInner('<span title="sort this column"/>')
        .each(function(){
          
            var th = jQuery(this),
                thIndex = th.index(),
                inverse = false;
          
            th.click(function(){
              
                table.find('td').filter(function(){
                  
                    return jQuery(this).index() === thIndex;
                  
                }).sortElements(function(a, b){
                  
                    return $.text([a]) > $.text([b]) ?
                        inverse ? -1 : 1
                        : inverse ? 1 : -1;
                  
                }, function(){
                  
                    // parentNode is the element we want to move
                    return this.parentNode; 
                  
                });
              
                inverse = !inverse;
                  
          });
              
      });


      
});