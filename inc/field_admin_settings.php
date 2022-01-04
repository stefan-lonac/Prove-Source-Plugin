<?php 



        // ************* Registering and displaying the fields that will be stored in the wordpress database *************
        add_action( 'admin_init',  'ps_register_setting' );
        function ps_register_setting(){

            // Add fields to the wordpress database
            add_settings_section(
                'some_settings_section_id', // section ID
                '', // title (if needed)
                '', // callback function (if needed)
                'provesource_plugin' // page slug
            );

           
            

            // Field registration Number of users
            register_setting(
                'prove_source_settings', // settings group name
                'number-of-users', // option name
                'sanitize_text_field' // sanitization function
            );

            // The display function displays the "ps_text_field_html_numberOfUsers" fields and function on the admin.php page
            add_settings_field(
                'number-of-users',
                'Number of users: ',
                'ps_text_field_html_numberOfUsers', // function which prints the field
                'provesource_plugin', // page slug
                'some_settings_section_id', // section ID
                array( 
                    'label_for' => 'number-of-users',
                    'class' => 'table__tr', // for <tr> element
                )
            );



            // Field registration Text popup received
            register_setting(
                'prove_source_settings', // settings group name
                'text-received', // option name
                'sanitize_text_field' // sanitization function
            );

            // The display function displays the "ps_text_field_html_receive" fields and function on the admin.php page
            add_settings_field(
                'text-received',
                'Text popup received text: ',
                'ps_text_field_html_receive', // function which prints the field
                'provesource_plugin', // page slug
                'some_settings_section_id', // section ID
                array( 
                    'label_for' => 'text-received',
                    'class' => 'table__tr', // for <tr> element
                )
            );
        

        }


        // ==== The text input fields are displayed ====
        // Received field
        function ps_text_field_html_receive(){

            global $PSreceivetext;

            printf(
                '<input type="text" id="text-received" name="text-received" value="%s" />',
                esc_attr( $PSreceivetext )
            );
        
        }// END: Received field

        // Number of users field
        function ps_text_field_html_numberOfUsers(){

            global $PSnumberOfUsers;

            printf(
                '<input type="number" id="number-of-users" name="number-of-users" value="%s" />',
                esc_attr( $PSnumberOfUsers )
            );
        
        }// END: Number of users field
        // ==== END: The text input fields are displayed ====

        // ************* END: Registering and displaying the fields that will be stored in the wordpress database *************