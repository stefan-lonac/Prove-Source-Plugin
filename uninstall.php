<?php

if (!define('WP_ININSTALL_PLUGIN') ) {
    exit;
}


// Clear Database stored data
// $books = get_posts( array( 'post_type' => 'book', 'numberposts' => -1) );

// foreach( $books as $book ) {
//     wp_delete_posts( $book-ID, true );
// }



// Access the database via SQL => More Destructive but more DANGEROUS
// global $wpdb;
// $wpdb->query( "DELETE FROM wp_posts WHERE post_type = 'book'" );