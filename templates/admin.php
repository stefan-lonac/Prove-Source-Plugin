
<div class="settings-pv">



    <h1>Prove Source Plugin Settings</h1>
    <!-- <p>If you need a form just insert this code on your page: <h3>[form-ps]</h3></p> -->

    <div class="sl__flex sl__sb setting-filters">
        <input id="admin-search-table-pv" class="" type="search" placeholder="Search a table...">

        <button type="button" class="btn btn-primary"> Users <span class="badge badge-light users-count"></span> </button> 
    </div>
    

    <table id="table-admin-ps" class="table table-striped">

        <thead class="thead-dark"> 
            <tr>
                <th>Name</th>
                <th>Email</th> 
                <th>Phone Number</th>
                <th>Date/time</th>
                <th>Delete</th>
            </tr>
        </thead>

        <tbody id="table-body-ps">

        </tbody>

    </table> 

</div>




<script type="text/javascript">

jQuery(document).ready(function() {

    // =========== Show table on admin setting page ===========
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
    console.log(ajaxurl);
    jQuery.ajax({    //create an ajax request to display.php
        type: "POST",
        url: ajaxurl,             
        dataType: "JSON",   //expect html to be returned   
        data: { 
            'action' : 'informationDB', 
        },             
        success: function(response){  
            
            jQuery.each(response, function(key, value){
                jQuery("#table-body-ps").append(    '<tr>' +
                                                        '<td>' + value.name + '</td>' +
                                                        '<td>' + value.email + '</td>' +
                                                        '<td>' + value.number + '</td>' +
                                                        '<td>' + value.date_time + '</td>' +
                                                        '<td>' + '<button type="button" class="delete-row-ps btn btn btn-danger" data-id="'+ value.id +'">Delete</button>'+ '</td>' +
                                                    '</tr>'
                                                );
                                                lengthRows = response.length;
                
            });
            jQuery(".users-count").append(lengthRows);
        }
    }); // =========== END: Show table on admin setting page ===========





    // =========== Delete row on click from table - admin setting page ===========
    jQuery(document).on("click", ".delete-row-ps", function() { 


        var deleteEle = jQuery(this).parent().parent().css('background','tomato');

        jQuery.ajax({
            type: "GET",
            url: ajaxurl,
            dataType: 'JSON',
            data:{
                'action' : 'deleteRow',
                'delete_id': jQuery(this).attr("data-id")
            },
            
            success: function(response){
                // Removing row from HTML Table
                console.log(response);
                deleteEle.fadeOut().remove();
            },
            error: function(response) {
                console.log(response);
            }
        });

    }); // =========== END: Delete row on click from table - admin setting page ===========


});

</script>