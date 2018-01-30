$(function() {

    $('.image-upload').change(function(e) {
        // show preview file
        if (this.files && this.files[0]) {
            var reader = new FileReader();

            $input = $(this);
            
            reader.onload = function(e) {
                addImagePreview($input, e.target.result);
            }

            reader.readAsDataURL(this.files[0]);
        }
    });

    // check has preivew
    $('.image-upload').each(function() {
        var initialPreviewSrc = $(this).attr('data-initial-preview');
        if (initialPreviewSrc) {
            addImagePreview($(this), initialPreviewSrc);
        }
    });

    function addImagePreview($input, src) {
        // check if the html already added
        if ($input.next().hasClass('image-upload-preview')) {
            $input.next().attr('src', src);
        }
        else {
            $input.after("<img class='image-upload-preview thumbnail' src='" + src + "' />");
        }
    }
});
