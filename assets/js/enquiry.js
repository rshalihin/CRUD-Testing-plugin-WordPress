;(function($){
    $('#enquiry-form-id form').on('submit', function(e){
        e.preventDefault();
        var data = $(this).serialize();
        $.post( crudTestEnquiry.ajaxurl, data, function(data){

        })
        .fail(function(){
            alert(crudTestEnquiry.error);
        })
    });
})(jQuery);