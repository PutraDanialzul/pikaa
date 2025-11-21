//RQUIRED FOR JQUERY
$("#formcarryForm").submit(function(e){
    e.preventDefault();
    var href = $(this).attr("action");
    var formData = new FormData(this);
    formData.append("time", new Date().toString());
    $.ajax({
        type: "POST",
        url: href,
        data: formData,
        dataType: "json",
        processData: false,
        contentType: false,
        success: function(response){
            if(response.status == "success"){
                window.location.replace("/feedback/thank");
            }
            else if(response.code === 422){
                alert("Field validation failed");
                $.each(response.errors, function(key) {
                    $('[name="' + key + '"]').addClass('formcarry-field-error');
                });
            }
            else{
                alert("An error occured: " + response.message);
            }
        },
        error: function(jqXHR, textStatus){
            const errorObject = jqXHR.responseJSON;
            alert("Request failed, " + errorObject.title + ": " + errorObject.message);
        },
        complete: function(){
            // This will be fired after request is complete whether it's successful or not.
            // Use this block to run some code after request is complete.
        }
    });
});