<?php 

// Show all information form database table
global $wpdb;
$table_name = $wpdb->prefix . "prove_source";
$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );

?>



<h1>Prove Source Plugin Settings</h1>

<p>If you need a form just insert this code on your page: <h3>[form-ps]</h3></p>




 <table>
    
    <tr>
        <th>Name</th>
        <th>Email</th> 
        <th>Phone Number</th>
    </tr>

    <?php foreach ($retrieve_data as $retrieved_data){ ?>

        <tr>
            <td><?php echo $retrieved_data->name;?></td>
            <td><?php echo $retrieved_data->email;?></td>
            <td><?php echo $retrieved_data->number;?></td>
        </tr>

    <?php } ?>

 </table> 


