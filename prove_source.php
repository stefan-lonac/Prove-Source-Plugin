<?php
/**
* @package ProveSource
* Plugin Name: Elementor Prove Source
* Description: Handle the basics with this plugin.
* Version: 1.0.0
* Requires at least: 5.2
* Requires PHP: 7.2
* Author: Stefan Loncaric
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: my-basics-plugin -->
* Domain Path: /languages
*/

!defined('BASEPATH') or die('You cant Access!');

class ProveSource 
{


    // Create variable for absolute link of plugin
    public $plugin;

    function __construct() {
        $this->plugin = plugin_basename( __FILE__ );

        // Insert data to the database AJAX
        add_action( 'wp_footer', array($this,'ajaxInsertDB'));
        add_action( 'wp_ajax_instertInDb', array($this, 'instertInDb'));
        add_action( 'wp_ajax_nopriv_instertInDb', array($this, 'instertInDb'));

        // Show all information from database
        add_action( 'wp_footer', array($this,'popupContent'));
        add_action( 'wp_footer', array($this,'ajaxInformationPpup'));
        add_action( 'wp_ajax_informationDB', array($this, 'informationDB'));
        add_action( 'wp_ajax_nopriv_informationDB', array($this, 'informationDB'));

        // Delete row from database
        add_action( 'wp_ajax_deleteRow', array($this, 'deleteRow'));
        add_action( 'wp_ajax_nopriv_deleteRow', array($this, 'deleteRow'));
    }

    // Registration assets like => scripts, style, image...
    function registerAssets() {
        // Show script file of DASHBOARD
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdmin' ) );

        // Show script on PAGES (frontend)
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue' ) );

        // Show admin menu Settings
        add_action( 'admin_menu', array( $this, 'add_admin_pages' ) );

        add_filter( 'plugin_action_links_' . $this->plugin , array( $this, 'settingsLink' ) );
    }

    // Add custom setting link
    public function settingsLink($links) {
        $settings_link = '<a href="options-general.php?page=provesource_plugin">Settings</a>';
        array_push( $links, $settings_link );
        return $links;
    }

    // Create Settings page and icon(dashicons-buddicons-buddypress-logo)
    public function add_admin_pages() {
        add_menu_page( 'Prove Source Setting', 'Prove Source', 'manage_options', 'provesource_plugin', array( $this, 'admin_index'), 'dashicons-buddicons-buddypress-logo', 110 );
    }

    // Call template for setting page
    public function admin_index() {
        require_once plugin_dir_path( __FILE__ ) . 'templates/admin.php';
    }


    // Add Custom Posts option => Creating Function
    function custom_post_type() {
        register_post_type( 'book', ['public' => true, 'label' => 'Books'] );
    }

    // Delete row from Admin table on click
    function deleteRow() {

        global $wpdb;
    
        $tblname = 'prove_source';
        $table_name = $wpdb->prefix . "$tblname";

        $id = $_GET['delete_id'];
        $delete_row = $wpdb->delete( $table_name, array( 'id' => $id ) );


        if($delete_row) {
            echo json_encode(array('res'=>true, 'message'=>__('Delete ROW!'), 'rows' => $delete_row ));
        } else {
            echo json_encode(array('res'=>false, 'message'=>__('Not Delete ROW!')));
        }


        die();
        return true;

    }


    

    // ************* Insert to DB *************
    function ajaxInsertDB() {
        ?>
            <script>
                
                jQuery(document).ready(function(){

                    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    jQuery('form').submit(function (event) {
                        event.preventDefault();
                        var nameProve        = jQuery(this).find("input[type='text']").val();
                        var emailProve       = jQuery(this).find("input[type='email']").val();
                        var numberPrefix     = jQuery( ".iti__selected-dial-code" ).text();
                        var numberProve      = jQuery(this).find("input[type='tel']").val();
                        var fullNumber       = '';
                        var numberWithPrefix = fullNumber.concat(numberPrefix, numberProve);
                       
                        // If all fileds fill than ajax work
                        if (nameProve !== '' && emailProve !== '' && numberProve !== '') {
                            // calling ajax
                            alert(nameProve + '-' + emailProve + '-' + numberWithPrefix);
                            alert('all done!');
                            jQuery.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: ajaxurl,
                                data: { 
                                    'action' : 'instertInDb',
                                    'name': nameProve,
                                    'email': emailProve,
                                    'number': numberWithPrefix,  
                                },
                                success: function(data){
                                    console.log('Insert: ' + data);
                                }
                            });
                         // If not all fileds fill than ajax wont work
                        } else {
                            alert('please fill fields');
                        }
                    });
                });
            </script>

        <?php
    }


    function instertInDb() {

        global $wpdb;
    
        $tblname = 'prove_source';
        $wp_track_table = $wpdb->prefix . "$tblname";
    
        $nameProve      = $_POST['name'];
        $emailProve     = $_POST['email'];
        $numberProve    = $_POST['number'];
        
        $insert_row = $wpdb->insert( 
            $wp_track_table, 
            array( 
                'name'      => $nameProve,
                'email'     => $emailProve,
                'number'    => $numberProve,
            )
        );
    
        // if row inserted in table 
        if($insert_row){
            echo json_encode(array('res'=>true, 'message'=>__('New row has been inserted.')));
        } else{
            echo json_encode(array('res'=>false, 'message'=>__('Something went wrong. Please try again later.')));
        }
    
        die();
        return true;
    
    }
    // END: ************* Inserto to DB *************




    // ************* Show data from Database *************
    // Show in information in admin panel on table
    function informationDB() {
        // Show all information form database table
        global $wpdb;
        $table_name = $wpdb->prefix . "prove_source";
        $rowPerPage = $_POST['rowPerPage']; // Rows display per page
        $retrieve_data = $wpdb->get_results( "SELECT * FROM $table_name" );

        echo json_encode($retrieve_data);

        // if row inserted in table 
        if(!$retrieve_data){
            echo json_encode(array('res'=>false, 'message'=>__('Something went wrong. Please try again later.')));
        }

        header('Content-Type: application/json');

    
        die();
        return true;
    }


    function ajaxInformationPpup() {
        ?>
            <script>
            
            jQuery(document).ready(function() {

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


                var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                jQuery.ajax({    //create an ajax request to display.php
                    type: "POST",
                    url: ajaxurl,             
                    dataType: "JSON",   //expect html to be returned   
                    data: { 
                        'action' : 'informationDB', 
                    },             
                    success: function(response){  
                        // Slice array to first 5 customers
                        var arrayContent = response.slice(-5);           

                        jQuery.each(arrayContent, function(key, value){
                            jQuery("#popup-hide").append('<div class="popup-content-ps">' + 
                                                            '<h3>' + value.name + '</h3>' +
                                                            '<p>' + 'Just received an E-BOOK!' + '</p>' + 
                                                            '<p>' + (timeSince(value.date_time)) + ' ago' + '</p>' + // Call the display function before the user logs on to the form
                                                        '</div');
                        });

                    }

                });

                function delayPopup() {

                    // Hides a popup that shows all the elements.
                    jQuery("#popup-hide").hide();

                    // A variable that displays random elements in text format
                    var text = jQuery('#popup-show').text();

                    // Takes from the "p" element and converts it to text
                    var words = jQuery("#popup-hide .popup-content-ps").map(function() {
                        return jQuery(this).html()
                    }).get();

                    // Function that displays popup after 3.5s (3500)
                    setInterval(function() {
                        // Toggle a "open" class
                        jQuery('#popup-show').toggleClass('open');

                        // Mix random names if there is an "open" class
                        if ( jQuery('#popup-show').hasClass('open') ) {
                            var randomElements = text + ' ' + words[Math.floor(Math.random() * words.length)];
                            jQuery('#popup-show').html(randomElements);
                        }
                    }, 3500)

                }
                
                setTimeout(delayPopup, 1000);

            });
            
            </script> 
        <?php
    }

    function popupContent() {
        ?>
        <div id="popup-hide">

        </div>

        <div id="popup-show" class="popup-content-ps-show">
            
        </div>

        <?php
    }
    // END: ************* Show data from Database *************



    // Call style or script file
    function enqueue() { 
        wp_enqueue_style( 'prove_source_style', plugins_url( '/assets/css/prove-style.css', __FILE__ ) );
        // Register AJAX and Jquery 
        // in the footer.  
        wp_deregister_script('jquery');
        wp_enqueue_script('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js', array(), null, true);

    }

    // Call style or script file ON ADMIN PANEL
    function enqueueAdmin() {
        wp_enqueue_script('bootbox', 'https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/5.4.1/bootbox.min.js', array(), null, false);
        wp_enqueue_script('bootstrap', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js', array(), null, false);

        wp_enqueue_script( 'prove_source_script', plugins_url( '/assets/js/prove-script.js', __FILE__ ) );

        wp_enqueue_style( 'prove_source_style', plugins_url( '/assets/css/prove-style-admin.css', __FILE__ ) );
        wp_enqueue_style('bootstrapStyle', 'https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css', array(), null, false);

        wp_enqueue_script('tableAdminDBmin', 'https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js', array(), null, true);
        wp_enqueue_style('tableAdminDBminStyle', 'https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css', array(), null, false);

    }

}

if (class_exists('ProveSource')) {
    $ProveSource = new ProveSource();
    $ProveSource->registerAssets();
}

// activation
require_once plugin_dir_path( __FILE__ ) . 'inc/prove_source_activate.php';
register_activation_hook( __FILE__, array( 'ProveSourceActivate', 'activate') );

// deactivation
require_once plugin_dir_path( __FILE__ ) . 'inc/prove_source_deactivate.php';
register_deactivation_hook( __FILE__, array( 'ProveSourceDeactivate' , 'deactivate') );

// Call file with creating table function
require_once plugin_dir_path( __FILE__ ) . 'templates/creating-tableDB.php';
register_activation_hook( __FILE__, 'create_plugin_database_table' );


// // Call file with shortcode function
// require_once plugin_dir_path( __FILE__ ) . 'templates/shortcode.php';
// register_activation_hook( __FILE__, 'wpb_demo_shortcode' );


