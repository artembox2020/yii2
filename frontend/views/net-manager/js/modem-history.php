<script>

    var modemHistory = document.querySelector('.modem-history');
    var modemForm = modemHistory.querySelector('.modem-history-filter-form');

    // set imei and imeiId input fields from ui.item structure
    function setImeiInputs(item)
    {
        modemForm.querySelector('input[name=imeiId]').value = item.id;
        modemForm.querySelector('input[name=imei]').value = item.value;
    }

    // set address and addressId input fields from ui.item structure
    function setAddressInputs(item)
    {
        modemForm.querySelector('input[name=addressId]').value = item.id;
        modemForm.querySelector('input[name=address]').value = item.value;
    }

    // clears form but selector and submit form
    function clearFormButSelectorAndSubmit(selector)
    {
        var elements = modemForm.querySelectorAll(selector);
        var elementNames = [];
        for (var i = 0; i < elements.length; ++i) {
            elementNames.push(elements[i].name);
        }
        var formElements = modemForm.querySelectorAll('input');
        for (var i = 0; i < formElements.length; ++i) {
            if (!elementNames.includes(formElements[i].name)) {
                formElements[i].value = '';
            }
        }

        setTimeout(function()
        {
            modemForm.querySelector('button[type=submit]').click();
        }, 300);
    }
</script>