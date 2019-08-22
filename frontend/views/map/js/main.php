<script>
    var actionsBlocks = document.querySelectorAll('.actions-block');

    // set event handlers for all actions blocks
    for (var i = 0; i < actionsBlocks.length; ++i) {
        var actionsBlock = actionsBlocks[i];
        var glyphicon = actionsBlock.querySelector('.glyphicon');
        var dataBlock = actionsBlock.querySelector('.filter-menu');
        var refill = dataBlock.querySelector('input[name=to_refill]');
        var reset = dataBlock.querySelector('button[type=reset]');

        // glyphicon (+-) click event handler
        glyphicon.onclick = function() {
            var dataBlock = this.closest('.actions-block').querySelector('.filter-menu');
            glyphiconToggleAction(this, dataBlock);
        }

        // refill sum change event handler
        refill.onchange = function() {
            var dataBlock = this.closest('.actions-block').querySelector('.filter-menu');
            refillChangeAction(this, dataBlock);
        }

        // reset button click event handler
        reset.onclick = function(e) {
            e.preventDefault();
            var dataBlock = this.closest('.actions-block').querySelector('.filter-menu');
            var glyphicon = this.closest('.actions-block').querySelector('.glyphicon');
            dataBlock.classList.add('hidden');
            glyphicon.classList.remove('glyphicon-minus');
            glyphicon.classList.add('glyphicon-plus');
        }
    }

    // refill sum change event handler function
    function refillChangeAction(refill, dataBlock)
    {
        console.log(refill.checked);
        var refillAmount = dataBlock.querySelector('input[name=refill_amount]');
        if (refill.checked) {
            refillAmount.removeAttribute('readonly');
        } else {
            refillAmount.setAttribute('readonly', 'readonly');
            refillAmount.value = '';
        }
    }

    // glyphicon (+-) click event toggle function
    function glyphiconToggleAction(glyphicon, dataBlock)
    {
        if (glyphicon.classList.contains('glyphicon-plus')) {
            dataBlock.classList.remove('hidden');
            glyphicon.classList.add('glyphicon-minus');
            glyphicon.classList.remove('glyphicon-plus');
        } else {
            dataBlock.classList.add('hidden');
            glyphicon.classList.remove('glyphicon-minus');
            glyphicon.classList.add('glyphicon-plus');
        }
    }
</script>