
(function($){

    // $(document).ready(function(){
    //     $("#submitFormTest").click(function(){
    //     var nameProve   = $("#nameProve").val();
    //     var emailProve  = $("#emailProve").val();
    //     var numberProve = $("#numberProve").val();
    //     jQuery.ajax({
    //             type: 'POST', // Adding Post method
    //             url: MyAjax.ajaxurl, // Including ajax file
    //             data:   {   "action": "instertInDb", 
    //                         "name": nameProve,
    //                         "email": emailProve,
    //                         "number": numberProve,
    //                     }, // Sending data dname to post_word_count function.
    //             success: function(data){ // Show returned data using the function.
    //                 alert(data);
    //             }
    //         });
    //     });
    // });
    


    jQuery('#formsubmit').on('submit', function(e){
        var nameProve   = $("#nameProve").val();
        var emailProve  = $("#emailProve").val();
        var numberProve = $("#numberProve").val();
        // calling ajax
        $.ajax({
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
                if (data.res == true){
                    alert(data.message);    // success message
                }else{
                    alert(data.message);    // fail
                }
            }
        });
    });


});


