
<div class="settings-pv">



    <h1>Prove Source Plugin Settings</h1>
    <!-- <p>If you need a form just insert this code on your page: <h3>[form-ps]</h3></p> -->

    <div class="sl__flex sl__sb setting-filters">
        <input id="admin-search-table-pv" class="" type="search" placeholder="Search a table...">

        <button type="button" class="btn btn-primary"> Users <span class="badge badge-light users-count"></span> </button> 
    </div>

    <select id="rowPerPage">
        <option value="5">Per page...</option>
        <option value="10">10</option>
        <option value="15">15</option>
        <option value="20">20</option>
        <option value="*">All</option>
    </select>
    

    <table id="table-admin-ps" class="table table-striped table-bordered" cellspacing="0" cellpadding="1" border="1" width="300">

        <thead class="thead-dark"> 
            <tr>
                <th>Name</th>
                <th>Email</th> 
                <th>Phone Number</th>
                <th>Date/Time</th>
                <th>Delete</th>
            </tr>
        </thead>

        <tbody id="table-body-ps">

        </tbody>

    </table> 

</div>



<script type="text/javascript">

jQuery(document).ready(function() {
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

    // =========== Show table on admin setting page ===========

    // ***** Functions before the user logs in *****
    // Array with set time
    var DURATION_IN_SECONDS = {
        epochs: ['year', 'month', 'day', 'hour', 'minute'],
        year: 31536000,
        month: 2592000,
        day: 86400,
        hour: 3600,
        minute: 60
    };

    // Returns the value in seconds and converts to the value that is currently set
    function getDuration(seconds) {
        var epoch, interval;

        for (var i = 0; i < DURATION_IN_SECONDS.epochs.length; i++) {
            epoch = DURATION_IN_SECONDS.epochs[i];
            interval = Math.floor(seconds / DURATION_IN_SECONDS[epoch]);
            if (interval >= 1) {
                return {
                    interval: interval,
                    epoch: epoch
                };
            }
        }

    };

    // Takes a date value and displays the date in hours or minutes format
    function timeSince(date) {
        var seconds = Math.floor((new Date() - new Date(date)) / 1000);
        var duration = getDuration(seconds);
        var suffix = (duration.interval > 1 || duration.interval === 0) ? 's' : '';
        return duration.interval + ' ' + duration.epoch + suffix;
    };
    // ***** END: Functions before the user logs in *****


    jQuery.ajax({    //create an ajax request to display.php
        type: "POST",
        url: ajaxurl,             
        dataType: "JSON",   //expect html to be returned   
        data: { 
            'action' : 'informationDB', 
            // 'rowPerPage' : this.value
        },             
        success: function(response){  
            jQuery("#table-body-ps").find("tr").remove().end();
            for (var i = 0; i < response.length; i++) {
                jQuery("#table-body-ps").append(    '<tr>' +
                                                        '<td>' + response[i].name + '</td>' +
                                                        '<td>' + response[i].email + '</td>' +
                                                        '<td>' + response[i].number + '</td>' +
                                                        '<td>' + response[i].date_time + '</td>' +
                                                        '<td>' + '<button type="button" class="delete-row-ps btn btn btn-danger" data-id="'+ response[i].id +'">Delete</button>'+ '</td>' +
                                                    '</tr>'
                                                );
                
            }

            var lengthRows = jQuery("#table-body-ps").find("tr").length;
            jQuery(".users-count").append(lengthRows);

            // =========== Delete row on click from table - admin setting page ===========
            jQuery(document).on("click", ".delete-row-ps", function() {

                if(confirm('Are you sure to remove this row ?')) {
                    var deleteEle = jQuery(this).parent().parent().css('background','tomato');
                    jQuery.ajax({
                        type: "GET",
                        url: ajaxurl,
                        dataType: 'JSON',
                        data:{
                            'action' : 'deleteRow',
                            'delete_id': jQuery(this).attr("data-id")
                        },
                        
                        success: function(response) {
                            // Removing row from HTML Table
                            deleteEle.fadeOut().remove();
                        },
                        error: function(response) {
                            console.log(response);
                        }
                    });
                }


            });
            // =========== END: Delete row on click from table - admin setting page ===========
           
            
        }
    }); 

    // =========== END: Show table on admin setting page ===========


});

</script>