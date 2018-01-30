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
        var $parentStageHolder = $this.closest('.stage-holder');

        var stageId = $parentStageHolder.attr('data-stage-id');

        // Find previous
        var $prevStageHolder = $parentStageHolder.prev('.stage-holder');
        if ($prevStageHolder.length > 0) {
            var prevStageId = $prevStageHolder.attr('data-stage-id');

            swapStageOrder(stageId, prevStageId);
        }
    });

    $('.btn-swap-down').click(function() {
        var $this = $(this);
        var $parentStageHolder = $this.closest('.stage-holder');

        var stageId = $parentStageHolder.attr('data-stage-id');

        // Find next
        var $nextStageHolder = $parentStageHolder.next('.stage-holder');
        if ($nextStageHolder.length > 0) {
            var nextStageId = $nextStageHolder.attr('data-stage-id');

            swapStageOrder(stageId, nextStageId);
        }

    });

    function swapStageOrder(stageOriId, stageToSwapId) {
        // update UI
        updateUISwapStagePosition(stageOriId, stageToSwapId);

        $.ajax({
            url: reorderUrl.replace('{id}', stageOriId),
            type: 'PUT',
            data: { swapWithStageId: stageToSwapId },
            success: function(result) {

                if (result.error) {
                    new PNotify({
                        'text': result.error.message,
                        'type': 'error',
                        'styling': 'bootstrap3'
                    });

                    // Roll back
                    updateUISwapStagePosition(stageOriId, stageToSwapId);
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
                updateUISwapStagePosition(stageOriId, stageToSwapId);
            }
        });
    }

    function updateUISwapStagePosition(fieldOriId, fieldToSwapId) {
        // Find the right dom
        var $stageHolderOri = $('.stage-holder[data-stage-id=' + fieldOriId + ']');
        var $stageHolderToSwap = $('.stage-holder[data-stage-id=' + fieldToSwapId + ']');

        if ($stageHolderOri.next().is($stageHolderToSwap)) {
            $stageHolderOri.before($stageHolderToSwap);
        }
        else {
            $stageHolderOri.after($stageHolderToSwap);
        }
    }
    // End

});
