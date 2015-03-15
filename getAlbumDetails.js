/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$(function() {

    $("input,textarea").jqBootstrapValidation({
        preventSubmit: true,
        submitError: function($form, event, errors) {
            
        },
        submitSuccess: function($form, event) {
            event.preventDefault(); // prevent default submit behaviour
            // get values from FORM
            var albumUri = $("input#album_uri_suffix").val();
            $.ajax({
                url: "./products.php",
                type: "POST",
                data: {
                    albumUri: albumUri,
                },
                cache: false,
                success: function(data) {
                    if(data=="true"){
                    }else{
                        alert(data);
                    }
                },
                error: function(err) {
                    // Fail message
                    
                },
            })
        },
        filter: function() {
            return $(this).is(":visible");
        },
    });

    $("a[data-toggle=\"tab\"]").click(function(e) {
        e.preventDefault();
        $(this).tab("show");
    });
});