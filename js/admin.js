jQuery(document).ready(function($) {

   $(document).on("click", ".mjr_upload_image_button", function() {

        jQuery.data(document.body, 'prevElement', $('.mjr_profile_avatar'));

        window.send_to_editor = function(html) {
            var imgurl = jQuery(html).attr('src');
            var inputText = jQuery.data(document.body, 'prevElement');

            if(inputText != undefined && inputText != '')
            {
                inputText.val(imgurl);
            }

            tb_remove();
        };

        tb_show('', 'media-upload.php?type=image&TB_iframe=true');
        return false;
    });

});