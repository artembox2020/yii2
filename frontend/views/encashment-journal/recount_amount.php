<script>
    var encashmentIndex = document.querySelector(".encashment-index");
    var recountAmounts = encashmentIndex.querySelectorAll("input[name=recount_amount]");

    for (var i = 0; i < recountAmounts.length; ++i) {
        var currentRa = recountAmounts[i];
        var difference = currentRa.closest('tr').querySelector('td.cell-difference span');
        var hidden = document.createElement('input');
        hidden.type = 'hidden';
        hidden.value = parseFloat(difference.innerHTML) - parseInt(currentRa.value);
        difference.parentNode.insertBefore(hidden, difference);
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

            AjaxHandler.ajax.onload = function() {

                if (AjaxHandler.ajax.readyState === 4) {
                    if (AjaxHandler.ajax.status === 200) {

                        var hidden = currentRa.closest('tr').querySelector('td.cell-difference input[type=hidden]');
                        var difference = currentRa.closest('tr').querySelector('td.cell-difference span');
                        var diff = parseFloat(hidden.value) + parseInt(currentRa.value);
                        difference.innerHTML = diff;

                        if (diff > 0) {
                            difference.classList.remove('difference');
                            difference.classList.add('difference-green');
                        } else if (diff < 0) {
                            difference.classList.add('difference');
                            difference.classList.remove('difference-green');
                        } else {
                            difference.classList.remove('difference');
                            difference.classList.remove('difference-green');
                        }
                    } else {
                        console.error(AjaxHandler.ajax.statusText);
                    }
                }
            };
        }

        AjaxHandler.run();
    }

</script>