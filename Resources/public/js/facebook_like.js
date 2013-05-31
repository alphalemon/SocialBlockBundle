$(document).ready(function() {
    $(document).on("popoverShow", function(event, element){
        if (element.attr('data-type') != 'FacebookLikeButton') {
            return;
        }
        
        $('#al_facebook_editor_tabs').tab('show');
        
        $('.al_editor_save').unbind().click(function()
        {
            $(document).EditBlock('Content', $('#al_facebook_form').serialize());

            return false;
        });
    });
});
