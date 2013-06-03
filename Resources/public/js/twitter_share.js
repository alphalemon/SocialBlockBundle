$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'TwitterShare') {
            return;
        }
        
        $('.al_editor_save').unbind().click(function()
        {
            $(document).EditBlock('Content', $('#al_item_form').serialize(), null, function(){ 
                $('body').find('a.twitter-share-button').each(function() {
                    twttr.widgets.load();
                });
            });

            return false;
        });
    });
});
