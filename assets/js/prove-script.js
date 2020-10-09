// Admin Plugin Panel (Settings) table script FILTER

jQuery(document).ready(function(){

    // Search table
    jQuery("#admin-search-table-pv").on("keyup", function() {

        var value = jQuery(this).val().toLowerCase();
        jQuery("#table-admin-ps tbody tr").filter(function() {
            jQuery(this).toggle(jQuery(this).text().toLowerCase().indexOf(value) > -1)
        });

    });
    
      
});