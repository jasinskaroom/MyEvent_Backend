$(() => {

    // Delete
    $('.form-delete').submit(function(e) {
        e.preventDefault();

        var status = confirm('Are you sure?');
        if (status) {
            this.submit();
        }
    });
    // End

    // Swap order
    $('.btn-swap-up').click(function() {
        var $this = $(this);
        var $parentBannerHolder = $this.closest('.banner-holder');

        var bannerId = $parentBannerHolder.attr('data-banner-id');

        // Find previous
        var $prevBannerHolder = $parentBannerHolder.prev('.banner-holder');
        if ($prevBannerHolder.length > 0) {
            var prevBannerId = $prevBannerHolder.attr('data-banner-id');

            swapBannerOrder(bannerId, prevBannerId);
        }
    });

    $('.btn-swap-down').click(function() {
        var $this = $(this);
        var $parentBannerHolder = $this.closest('.banner-holder');

        var bannerId = $parentBannerHolder.attr('data-banner-id');

        // Find next
        var $nextBannerHolder = $parentBannerHolder.next('.banner-holder');
        if ($nextBannerHolder.length > 0) {
            var nextBannerId = $nextBannerHolder.attr('data-banner-id');

            swapBannerOrder(bannerId, nextBannerId);
        }

    });

    function swapBannerOrder(bannerOriId, bannerToSwapId) {
        // update UI
        updateUISwapBannerPosition(bannerOriId, bannerToSwapId);

        $.ajax({
            url: reorderUrl.replace('{id}', bannerOriId),
            type: 'PUT',
            data: { swapWithBannerId: bannerToSwapId },
            success: function(result) {

                if (result.error) {
                    new PNotify({
                        'text': result.error.message,
                        'type': 'error',
                        'styling': 'bootstrap3'
                    });

                    // Roll back
                    updateUISwapBannerPosition(bannerOriId, bannerToSwapId);
                }
                else {
                    new PNotify({
                        'text': result.data.message,
                        'type': 'success',
                        'styling': 'bootstrap3'
                    });
                }
            },
            error: function(result, status) {
                new PNotify({
                    'text': "Swap failed (error code: " + result.status + ")",
                    'type': 'error',
                    'styling': 'bootstrap3'
                });

                // Roll back
                updateUISwapBannerPosition(bannerOriId, bannerToSwapId);
            }
        });
    }

    function updateUISwapBannerPosition(fieldOriId, fieldToSwapId) {
        // Find the right dom
        var $bannerHolderOri = $('.banner-holder[data-banner-id=' + fieldOriId + ']');
        var $bannerHolderToSwap = $('.banner-holder[data-banner-id=' + fieldToSwapId + ']');

        if ($bannerHolderOri.next().is($bannerHolderToSwap)) {
            $bannerHolderOri.before($bannerHolderToSwap);
        }
        else {
            $bannerHolderOri.after($bannerHolderToSwap);
        }
    }
    // End

});
