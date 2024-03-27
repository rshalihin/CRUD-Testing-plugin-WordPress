;(function($){
    $('table.table-view-list.teammembers').on('click', 'a.submitdelete', function(e){
        e.preventDefault();
        
        if( ! confirm(crudTestAdmin.confirm) ) {
            return;
        }

        var self = $(this);
        var id   = self.data('id');
        
        wp.ajax.send( 'crud-test-info-delete', {
            data: {
                id: id,
                _wpnonce: crudTestAdmin.nonce
            }
        })
        .done(function(response){
                self.closest( 'tr' )
                .css( 'background-color', 'red' )
                .hide( 400, function() {
                    $(this).remove();
                })
        })
        .fail(function(){
            alert(crudTestAdmin.error);
        });
    
    });
})(jQuery);