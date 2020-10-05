<?php 

// Show all information form database table
global $wpdb;
$table_name = $wpdb->prefix . "prove_source";
$retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );

?>



<h1>Prove Source Plugin Settings</h1>

<p>If you need a form just insert this code on your page: <h3>[form-ps]</h3></p>




<input id="admin-search-table-pv" type="text" placeholder="Search Table..">

 <table id="table-admin-ps">

    <thead>
        <tr>
            <th id="name-ps">Name</th>
            <th id="email-ps">Email</th> 
            <th>Phone Number</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($retrieve_data as $retrieved_data){ ?>

        <tr>
            <td><?php echo $retrieved_data->name;?></td>
            <td><?php echo $retrieved_data->email;?></td>
            <td><?php echo $retrieved_data->number;?></td>
        </tr>

    <?php } ?>
    </tbody>

 </table> 


