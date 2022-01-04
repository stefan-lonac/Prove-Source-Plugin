
<?php 

global $PSnumberOfUsers;
global $PSreceivetext;

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
    }
 
    // show error/update messages
    settings_errors( 'wporg_messages' );
?>




<div class="settings-pv">


    <h1>Prove Source Plugin Settings</h1>
    <!-- <p>If you need a form just insert this code on your page: <h3>[form-ps]</h3></p> -->

    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="pills-settings-tab" data-toggle="pill" href="#pills-settings" role="tab" aria-controls="pills-settings" aria-selected="true">Settings</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link" id="pills-table-tab" data-toggle="pill" href="#pills-table" role="tab" aria-controls="pills-table" aria-selected="false">Table</a>
        </li>
    </ul>



    <div class="tab-content" id="pills-tabContent">

        <div class="tab-pane fade show active" id="pills-settings" role="tabpanel" aria-labelledby="pills-settings-tab">

            <div>
                <h5>How to use:</h5><p>If you want to collect data from a form, use the <strong style="font-size: 16px;">"ps-form"</strong> class on the popup form.</p>
                
                <div class="wrap">
                    <form method="post" action="options.php">
                
                        <?php 
                            settings_fields( 'prove_source_settings' ); // settings group name
                            do_settings_sections( 'provesource_plugin' ); // just a page slug
                            submit_button();
                        ?>
                
                    </form>
                </div>
            </div>
            
        </div>


        <div class="tab-pane fade" id="pills-table" role="tabpanel" aria-labelledby="pills-table-tab">

            <div class="table-content">
            
                <div class="sl__flex sl__sb setting-filters">
                    <input id="admin-search-table-pv" class="" type="search" placeholder="Search a table...">

                    <button type="button" class="btn btn-primary"> Users <span class="badge badge-light users-count"></span> </button> 
                </div>

            

                <table id="table-admin-ps" class="table table-striped table-bordered" cellspacing="0" cellpadding="1" border="1" width="300">

                    <thead class="thead-dark"> 
                        <tr>
                            <th>Name</th>
                            <th>Email</th> 
                            <th>Country</th>
                            <th>Phone Number</th>
                            <th>Date/Time</th>
                            <th>Delete</th>
                        </tr>
                    </thead>

                    <tbody id="table-body-ps">

                    </tbody>

                </table> 

            </div><!-- END: .table-content -->

        </div><!-- END: #pills-table -->

    </div><!-- END: .tab-content -->


</div>




<script type="text/javascript">


jQuery(document).ready(function() {
    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

    // =========== Show table on admin setting page ===========

    // ***** Little plugin for sorting -> userd function "sortElements" *****
    jQuery.fn.sortElements = (function(){
        
        var sort = [].sort;
        
        return function(comparator, getSortable) {
            
            getSortable = getSortable || function(){return this;};
            
            var placements = this.map(function(){
                
                var sortElement = getSortable.call(this),
                    parentNode = sortElement.parentNode,
                    
                    // Since the element itself will change position, we have
                    // to have some way of storing it's original position in
                    // the DOM. The easiest way is to have a 'flag' node:
                    nextSibling = parentNode.insertBefore(
                        document.createTextNode(''),
                        sortElement.nextSibling                        
                    );

                return function() {
                    
                    if (parentNode === this) {
                        throw new Error(
                            "You can't sort elements if any one is a descendant of another."
                        );
                    }
                    
                    // Insert before flag:
                    parentNode.insertBefore(this, nextSibling);
                    // Remove flag:
                    parentNode.removeChild(nextSibling);
                    
                };

            });
        
            return sort.call(this, comparator).each(function(i){
                placements[i].call(getSortable.call(this));
            });
            
        };
        
    })(); // ***** END: Little plugin for sorting -> userd function "sortElements" *****


    // ***** Sort TABLE on click *****
    jQuery('#table-admin-ps tr th').wrapInner('<span class="sort-this-column"/>').each(function(){
            
            var th = jQuery(this),
                thIndex = th.index(),
                inverse = false;
            
            th.click(function(){
                console.log(this);
                // this.addClass( "myClass yourClass" );
                
                jQuery('#table-admin-ps').find('td').filter(function(){
                    
                    return jQuery(this).index() === thIndex;

                }).sortElements(function(a, b){
                    
                    return jQuery.text([a]) > jQuery.text([b]) ?

                        inverse ? -1 : 1
                        : inverse ? 1 : -1;
                    
                }, function(){ 
                    // parentNode is the element we want to move
                    return this.parentNode; 
                    
                });
                inverse = !inverse;
                    
        });
            
    });// ***** END: Sort TABLE on click *****


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
                                                        '<td>' + response[i].country + '</td>' +
                                                        '<td>' + response[i].number + '</td>' +
                                                        '<td>' + response[i].date_time + '</td>' +
                                                        '<td>' + '<button type="button" class="delete-row-ps btn btn btn-danger" data-id="'+ response[i].id +'">Delete</button>'+ '</td>' +
                                                    '</tr>'
                                                );
                
            }

            var lengthRows = jQuery("#table-body-ps").find("tr").length;
            jQuery(".users-count").html(lengthRows);

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
                            minusUser = lengthRows - 1;
                            jQuery(".users-count").html(minusUser);
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