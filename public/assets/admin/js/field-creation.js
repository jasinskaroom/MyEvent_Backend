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

        var fieldId = $this.parent().attr('data-field-id');

        // Find previous
        var $prevFieldHolder = $this.closest('.field-holder').prev('.field-holder');
        if ($prevFieldHolder.length > 0) {
            var prevFieldId = $prevFieldHolder.find('.actions').attr('data-field-id');

            swapField(fieldId, prevFieldId);
        }
    });

    $('.btn-swap-down').click(function() {
        var $this = $(this);

        var fieldId = $this.parent().attr('data-field-id');

        // Find next
        var $nextFieldHolder = $this.closest('.field-holder').next('.field-holder');
        if ($nextFieldHolder.length > 0) {
            var nextFieldId = $nextFieldHolder.find('.actions').attr('data-field-id');

            swapField(fieldId, nextFieldId);
        }

    });

    function swapField(fieldOriId, fieldToSwapId) {
        // update UI
        updateUISwapField(fieldOriId, fieldToSwapId);

        $.ajax({
            url: reorderUrl.replace('{id}', fieldOriId),
            type: 'PUT',
            data: { swapWithFieldId: fieldToSwapId },
            success: function(result) {

                if (result.error) {
                    new PNotify({
                        'text': result.error.message,
                        'type': 'error',
                        'styling': 'bootstrap3'
                    });

                    // Roll back
                    updateUISwapField(fieldOriId, fieldToSwapId);
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
                updateUISwapField(fieldOriId, fieldToSwapId);
            }
        });
    }

    function updateUISwapField(fieldOriId, fieldToSwapId) {
        // Find the right dom
        var $fieldHolderOri = $('.field-holder').find('.actions[data-field-id=' + fieldOriId + ']').closest('.field-holder');
        var $fieldHolderToSwap = $('.field-holder').find('.actions[data-field-id=' + fieldToSwapId + ']').closest('.field-holder');

        if ($fieldHolderOri.next().is($fieldHolderToSwap)) {
            $fieldHolderOri.before($fieldHolderToSwap);
        }
        else {
            $fieldHolderOri.after($fieldHolderToSwap);
        }
    }
    // End

});
