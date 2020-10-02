<?php 
    require dirname(__FILE__) . '/form-insertDB.php';
    // function that runs when shortcode is called
    function wpb_demo_shortcode() { 
 
        // Things that you want to do. 
        $message = 
        '<form id="formsubmit">
            <p>
                <label for="Name">First Name:</label>
                <input type="text" name="name" id="nameProve">
            </p>
            <p>
                <label for="Email">Email:</label>
                <input type="email" name="email" id="emailProve">
            </p>
            <p>
                <label for="Number">Number:</label>
                <input type="number" name="number" id="numberProve">
            </p>
            <input type="submit" value="Submit" id="submitFormTest">
        </form>';
        
        // Output needs to be return
        return $message;
    } 
    // register shortcode
    add_shortcode('form-ps', 'wpb_demo_shortcode');