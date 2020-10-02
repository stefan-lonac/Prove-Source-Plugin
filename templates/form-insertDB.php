<?php 
// require dirname(__FILE__) . '/connectionDB.php';


// add_action('wp_ajax_nopriv_add_new_user', 'instertInDb'); // Call when user logged in
// add_action('wp_ajax_add_new_user', 'instertInDb'); // Call when user in

// function instertInDb() {

//     global $wpdb;

//     $tblname = 'prove_source';
//     $wp_track_table = $wpdb->prefix . "$tblname";

//     $nameProve      = $_POST['name'];
//     $emailProve     = $_POST['email'];
//     $numberProve    = $_POST['number'];
//     // // Check connection
//     // if($connection === false){
//     //     die("ERROR: Could not connect. " . mysqli_connect_error());
//     // }
    
//     $insert_row = $wpdb->insert( 
//         $wp_track_table, 
//         array( 
//             'name'      => $nameProve,
//             'email'     => $emailProve,
//             'number'    => $numberProve,
//         )
//     );
//     echo 'Success!';

//     // if row inserted in table
//     if($insert_row){
//         echo json_encode(array('res'=>true, 'message'=>__('New row has been inserted.')));
//     }else{
//         echo json_encode(array('res'=>false, 'message'=>__('Something went wrong. Please try again later.')));
//     }

//     die();
//     return true;

// }