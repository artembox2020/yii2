<script>
    var block = document.querySelector('.block-user-dv');
    var blockButton = block.querySelector('.btn-block.block-btn');

    // block button press event handler
    blockButton.onclick = function(e) {
        e.preventDefault();

        if (commentSelect = block.querySelector('select[name=block-reason]')) {
            var comment = commentSelect.value;
        } else {
            var comment = '';
        }

        var queryString = "userId=<?= $userId ?>&comment=" + comment;
        var ajaxActions = new XMLHttpRequest();
        ajaxActions.open("GET", "/map/block-unblock-user?" + queryString, true);
        ajaxActions.send();
        location.reload();
    }
</script>