<?php 

        // Show all information form database table
        global $wpdb;
        $table_name = $wpdb->prefix . "prove_source";
        $retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );

        foreach ($retrieve_data as $retrieved_data){ ?>

            <div>
                <p><?php echo $retrieved_data->name;?></p>
                <p><?php echo $retrieved_data->email;?></p>
                <p><?php echo $retrieved_data->number;?></p>
            </div>
        
        <?php } ?>
        
    