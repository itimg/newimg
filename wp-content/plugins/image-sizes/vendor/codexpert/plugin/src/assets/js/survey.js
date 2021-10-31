jQuery(function($){
    $(document).on('click', '.cx-survey-notice .notice-dismiss, .cx-survey-notice .cx-notice-btn', function(e){
        $(this).prop('disabled', true);
        var $slug = $(this).closest('.cx-survey-notice').data('slug')
        $.ajax({
            url: ajaxurl,
            data: { 'action' : $slug + '_survey', 'participate' : $(this).data('participate') },
            type: 'POST',
            success: function(ret) {
                $('#'+$slug+'-survey-notice').slideToggle(500)
            }
        })
    })
})