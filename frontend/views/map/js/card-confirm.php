<script>
    var cardToggles = document.querySelectorAll(
        '.nav a.add-card .add-card-label, .nav #card-confirm .close, .nav #card-confirm .confirm-cancel-btn'
    );
    var block = document.querySelector('#card-confirm');
    var cardNo = block.querySelector('input[name=card_no]');
    var infoBlock = block.querySelector('.block');
    var confirmBtn = block.querySelector('button.confirm-btn');

    // add  card popup toggle event handlers
    for (var i = 0; i < cardToggles.length; ++i) {
        cardToggles[i].onclick = function() {
            toggleAddCard(this);
        }
    }

    // confirm button event handler
    confirmBtn.onclick = function() {
        var queryString = "userId=<?= $userId ?>&cardNo=" + cardNo.value;
        var ajax = new XMLHttpRequest();
        ajax.open("GET", "/map/card-confirm?" + queryString, true);
        ajax.send();

        // server response handler
        ajax.addEventListener("load", function() {
            var response = JSON.parse(ajax.responseText);

            if (response.status == <?= Yii::$app->mapBuilder::STATUS_SUCCESS ?>) {
                infoBlock.classList.remove('block-error');
                infoBlock.classList.add('block-info');
                infoBlock.innerHTML = "<?= Yii::t('map', 'Success card assignment') ?>";
                setTimeout(function() { location.reload(); }, 1000);
            } else {
                infoBlock.classList.add('block-error');
                infoBlock.classList.remove('block-info');
                infoBlock.innerHTML = "<?= Yii::t('map', 'Error card assignment') ?>";
            }
        });
    }

    // toggles card addition popup window
    function toggleAddCard(toggleLabel) {
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
</script>