<?php
/**
* @package ProveSource
* Plugin Name: Prove Source
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

        add_action( 'wp_footer', array($this,'ajaxInsertDB'));
        add_action( 'wp_ajax_instertInDb', array($this, 'instertInDb'));
        add_action( 'wp_ajax_nopriv_instertInDb', array($this, 'instertInDb'));
    }

    // Registration assets like => scripts, style, image...
    function registerAssets() {
        // Show script file of DASHBOARD
        add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

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



    // ************* Inserto to DB *************
    function ajaxInsertDB() {
        ?>
            <script>
                
                jQuery(document).ready(function(){
                    var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
                    jQuery('form').submit(function (event) {
                        event.preventDefault();
                        var nameProve   = jQuery(this).find("input[type='text']").val();
                        var emailProve  = jQuery(this).find("input[type='email']").val();
                        var numberProve = jQuery(this).find("input[type='number']").val();
                        // If all fileds fill than ajax work
                        if (nameProve !== '' && emailProve !== '' && numberProve !== '') {
                             // calling ajax
                            alert('all done!');
                            jQuery.ajax({
                                type: 'POST',
                                dataType: 'json',
                                url: ajaxurl,
                                data: { 
                                    'action' : 'instertInDb',
                                    'name': nameProve,
                                    'email': emailProve,
                                    'number': numberProve,  
                                },
                                success: function(data){
                                  
                                        var jsonn = jQuery.parseJSON(data); // create an object with the key of the array
                                        alert(jsonn.html);
                                        console.log(data.message);    // success message
                                   
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


    public function instertInDb() {

        global $wpdb;
    
        $tblname = 'prove_source';
        $wp_track_table = $wpdb->prefix . "$tblname";
    
        $nameProve      = $_POST['name'];
        $emailProve     = $_POST['email'];
        $numberProve    = $_POST['number'];
        // // Check connection
        // if($connection === false){
        //     die("ERROR: Could not connect. " . mysqli_connect_error());
        // }
        
        $insert_row = $wpdb->insert( 
            $wp_track_table, 
            array( 
                'name'      => $nameProve,
                'email'     => $emailProve,
                'number'    => $numberProve,
            )
        );
        echo 'Success!';
    
        // if row inserted in table
        if($insert_row){
            echo json_encode(array('res'=>true, 'message'=>__('New row has been inserted.')));
        }else{
            echo json_encode(array('res'=>false, 'message'=>__('Something went wrong. Please try again later.')));
        }
    
        die();
        return true;
    
    }
    // END: ************* Inserto to DB *************



    // Call style or script file
    function enqueue() {
        // enqueue all our scripts
        wp_enqueue_style( 'prove_source_style', plugins_url( '/assets/css/prove-style.css', __FILE__ ) );
        wp_enqueue_script( 'prove_source_script', plugins_url( '/assets/js/prove-script.js', __FILE__ ) );
        // Register AJAX and Jquery
        wp_deregister_script('jquery');  
        // Load a copy of jQuery from the Google API CDN  
        // The last parameter set to TRUE states that it should be loaded  
        // in the footer.  
        wp_register_script('jquery', 'http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js', FALSE, '1.11.0', TRUE);  
        wp_enqueue_script('jquery'); 

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


// Call file with shortcode function
require_once plugin_dir_path( __FILE__ ) . 'templates/shortcode.php';
register_activation_hook( __FILE__, 'wpb_demo_shortcode' );
