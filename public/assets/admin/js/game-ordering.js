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
        var $parentGameHolder = $this.closest('.game-holder');

        var gameId = $parentGameHolder.attr('data-game-id');

        // Find previous
        var $prevGameHolder = $parentGameHolder.prev('.game-holder');
        if ($prevGameHolder.length > 0) {
            var prevGameId = $prevGameHolder.attr('data-game-id');

            swapGameOrder(gameId, prevGameId);
        }
    });

    $('.btn-swap-down').click(function() {
        var $this = $(this);
        var $parentGameHolder = $this.closest('.game-holder');

        var gameId = $parentGameHolder.attr('data-game-id');

        // Find next
        var $nextGameHolder = $parentGameHolder.next('.game-holder');
        if ($nextGameHolder.length > 0) {
            var nextGameId = $nextGameHolder.attr('data-game-id');

            swapGameOrder(gameId, nextGameId);
        }

    });

    function swapGameOrder(gameOriId, gameToSwapId) {
        // update UI
        updateUISwapGamePosition(gameOriId, gameToSwapId);

        $.ajax({
            url: reorderUrl.replace('{id}', gameOriId),
            type: 'PUT',
            data: { swapWithGameId: gameToSwapId },
            success: function(result) {

                if (result.error) {
                    new PNotify({
                        'text': result.error.message,
                        'type': 'error',
                        'styling': 'bootstrap3'
                    });

                    // Roll back
                    updateUISwapGamePosition(gameOriId, gameToSwapId);
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
                updateUISwapGamePosition(gameOriId, gameToSwapId);
            }
        });
    }

    function updateUISwapGamePosition(gameOriId, gameToSwapId) {
        // Find the right dom
        var $gameHolderOri = $('.game-holder[data-game-id=' + gameOriId + ']');
        var $gameHolderToSwap = $('.game-holder[data-game-id=' + gameToSwapId + ']');

        if ($gameHolderOri.next().is($gameHolderToSwap)) {
            $gameHolderOri.before($gameHolderToSwap);
        }
        else {
            $gameHolderOri.after($gameHolderToSwap);
        }
    }
    // End

});
