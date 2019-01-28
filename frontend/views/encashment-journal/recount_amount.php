<script>
    var encashmentIndex = document.querySelector(".encashment-index");
    var recountAmounts = encashmentIndex.querySelectorAll("input[name=recount_amount]");

    for (var i = 0; i < recountAmounts.length; ++i) {
        var currentRa = recountAmounts[i];
        currentRa.onchange = function() { updateRecountAmount(this);}
    }

    // updates recount amount by ajax request
    function updateRecountAmount(currentRa)
    {
        var AjaxHandler = { "ajax" : new XMLHttpRequest()};

        AjaxHandler.run = function()
        {
            AjaxHandler.ajax = new XMLHttpRequest();
            var queryString = 'logId=' + currentRa.dataset.id + "&value=" + currentRa.value;

            AjaxHandler.ajax.open("GET", "/encashment-journal/update-recount-amount?" + queryString, true);
            AjaxHandler.ajax.send();
        }

        AjaxHandler.run();
    }

</script>