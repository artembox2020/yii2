<script>
    var cardToggles = document.querySelectorAll(
        '.add-card .add-card-label, .card-confirm .close, .card-confirm .confirm-cancel-btn'
    );

    // add  card popup toggle event handlers
    for (var i = 0; i < cardToggles.length; ++i) {
        cardToggles[i].onclick = function() {
            toggleAddCard(this);
        }
    }

    // card confirmations blocks event handlers
    var blocks = document.querySelectorAll('.card-confirm');

    for (var i = 0; i < blocks.length; ++i) {
        var block = blocks[i];
        var confirmBtn = block.querySelector('button.confirmation-btn');

        // confirm button event handler
        confirmBtn.onclick = function(e) {
            confirmBtnEventHandler(this);
        };
    }

    // toggles card addition popup window
    function toggleAddCard(toggleLabel) {
        var block = toggleLabel.closest('.add-card').querySelector('.card-confirm');
        var glyphicon = toggleLabel.closest('a.add-card').querySelector('.glyphicon');
        if (glyphicon.classList.contains('glyphicon-plus')) {
            glyphicon.classList.add('glyphicon-minus');
            glyphicon.classList.remove('glyphicon-plus');
            block.classList.remove('hidden');
        } else {
            glyphicon.classList.remove('glyphicon-minus');
            glyphicon.classList.add('glyphicon-plus');
            block.classList.add('hidden');
        }
    }

    // confirmation button event handler 
    function confirmBtnEventHandler(confirmBtn) {
        var cardNo = confirmBtn.closest('.card-confirm').querySelector('input[name=card_no]');
        var queryString = "userId=<?= $userId ?>&cardNo=" + cardNo.value;
        var ajax = new XMLHttpRequest();
        ajax.open("GET", "/map/card-confirm?" + queryString, true);
        ajax.send();

        // server response handler
        ajax.addEventListener("load", function() {
            var response = JSON.parse(ajax.responseText);
            var infoBlock = confirmBtn.closest('.card-confirm').querySelector('.block');

            if (response.status == <?= Yii::$app->mapBuilder::STATUS_SUCCESS ?>) {
                infoBlock.classList.remove('block-error');
                infoBlock.classList.add('block-info');
                infoBlock.innerHTML = "<?= Yii::t('map', 'Success card assignment') ?>";
                setTimeout(function() {
                    location.reload();
                }, 1000);
            } else {
                infoBlock.classList.add('block-error');
                infoBlock.classList.remove('block-info');
                infoBlock.innerHTML = "<?= Yii::t('map', 'Error card assignment') ?>";
            }
        });
    }
</script>