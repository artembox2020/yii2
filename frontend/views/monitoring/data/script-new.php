<?php

use frontend\controllers\MonitoringController;

?>
<script>
    (function() 
    {
        var monitoring = document.querySelector('.monitoring-new');
        var monitoringForm = monitoring.querySelector('.monitoring-filter-form');
        var monitoringFormAddress = monitoringForm.querySelector('input[name=address]');
        var monitoringFormSortOrder = monitoringForm.querySelector('select[name=sortOrder]');
        var monitoringSerialNumbers = monitoring.querySelectorAll('input.address-serial-number');
        var monitoringSerialSorts = monitoring.querySelectorAll(
            '.header-block .dropup.number, .header-block .dropdown.number'
        );
        var monitoringBalanceHolderSorts = monitoring.querySelectorAll(
            '.header-block .dropup.bhname, .header-block .dropdown.bhname'
        );
        var monitoringAddressSorts = monitoring.querySelectorAll(
            '.header-block .dropup.address, .header-block .dropdown.address'
        );
        var MIN_VALUE = -999999999;
        var MAX_VALUE = 999999999;

        /*function getActiveTab()
        {
            var genTab = monitoring.querySelector('#tab-gen');
            var finTab = monitoring.querySelector('#tab-fin');
            var techTab = monitoring.querySelector('#tab-tech');
            
            if (typeof genTab == 'undefined' || genTab == null) {
                if (typeof finTab != 'undefined' && finTab != null) {

                    return monitoring.querySelector("#tab-fin");    
                }

                return monitoring.querySelector("#tab-tech");
            }
            
            if (genTab.style && genTab.style.display != 'none') {
                
                return monitoring.querySelector("#tab-gen");
            }
            
            if (finTab.style && finTab.style.display != 'none') {
                
                return monitoring.querySelector("#tab-fin");
            }

            return monitoring.querySelector("#tab-tech");
        }*/

        // applies filter by value 
        function applyFilterByValue(value, inputSelector)
        {
            if (typeof value != 'undefined' && value != null && value.trim() != '') {
                value = value.toLowerCase();
                var inputSearch = monitoring.querySelectorAll('input'+ inputSelector+'[value*="' + value + '"]');
                var upperRows = monitoring.querySelectorAll('.upper-row');

                for (var i = 0; i < upperRows.length; ++i) {
                    if (upperRows[i].querySelectorAll('input'+ inputSelector+'[value*="' + value + '"]').length > 0) {
                        var displayProp = 'table-row';
                    } else {
                        var displayProp = 'none';
                    }
                    var nextSibling = upperRows[i].nextSibling;

                    while(nextSibling) {
                        if (!nextSibling.classList || !nextSibling.classList.contains('upper-row')) {
                            nextSibling.style = "display:" + displayProp;
                        } else {
                            break;
                        }

                        nextSibling = nextSibling.nextSibling;
                    }

                    upperRows[i].style.display = displayProp;
                }
            } else {
                var tables = monitoring.querySelectorAll('.monitoring-grid-view table');
                for (var j = 0; j < tables.length; ++j) {
                    var table = tables[j];
                    var rows  = table.querySelectorAll("tr");

                    for (var i = 0; i < table.querySelectorAll("tr").length; ++i) {
                        rows[i].style = "display: table-row";
                    }
                }
            }
        }

        // monitoring form address change function
        monitoringFormAddress.onchange = function()
        {
            applyFilterByValue(this.value, '.search-address-value');
        }

        // apply monitoring form address change function on <ENTER>
        monitoringFormAddress.onkeypress = function(e)
        {
            if (e.keyCode == 13 ) {
                e.preventDefault();
                this.onchange();

                return true;
            }
        }

        // monitoring form sort order change function
        monitoringFormSortOrder.onchange = function()
        {
            var form = monitoring.querySelector('.monitoring-pjax-form');
            var sortOrder = form.querySelector('input[name=sortOrder]');
            sortOrder.value = this.value;
            var monitoringFormInputs = monitoringForm.querySelectorAll('input');

            for (var i = 0; i < monitoringFormInputs.length; ++i) {
                monitoringFormInputs[i].value = '';
            }

            form.querySelector('button[type=submit]').click();
        }

        // monitoring serial number change
        for (var i = 0; i < monitoringSerialNumbers.length; ++i) {
            monitoringSerialNumbers[i].onchange = function()
            {
                var serialNumber = this.value;
                var addressId = this.closest('tr').querySelector('.address-id').value;
                var pjaxForm = monitoring.querySelector('.monitoring-pjax-form');
                var address = pjaxForm.querySelector('input[name=addressId]');
                var number = pjaxForm.querySelector('input[name=serialNumber]');
                address.value = addressId;
                number.value = serialNumber;

                var monitoringFormInputs = monitoringForm.querySelectorAll('input');

                for (var i = 0; i < monitoringFormInputs.length; ++i) {
                    monitoringFormInputs[i].value = '';
                }

                var sortOrder = pjaxForm.querySelector('input[name=sortOrder]');
                var monitoringFormSortOrder = monitoringForm.querySelector('select[name=sortOrder]');
                sortOrder.value =  monitoringFormSortOrder.value;

                pjaxForm.querySelector('button[type=submit]').click();
            }
        }

        // puts item after node
        function putItemAfter(tr, afterNode)
        {
            var oldNode = afterNode;
            afterNode = afterNode.nextSibling;
            while  (typeof afterNode != 'undefined' && afterNode != null 
                && (!afterNode.classList || !afterNode.classList.contains('upper-row'))
            ) {
                oldNode = afterNode;
                afterNode = afterNode.nextSibling;
            }
            var nextSibling = tr.nextSibling;
            oldNode.after(tr);

            while (
                typeof nextSibling != 'undefined' && nextSibling != null 
                && (!nextSibling.classList || !nextSibling.classList.contains('upper-row'))
            ) {
                newNextSibling  = nextSibling.nextSibling;
                tr.after(nextSibling);
                tr = tr.nextSibling;
                nextSibling = newNextSibling;
            }
        }

        // initialize script
        (function init() {
            var genTab = monitoring.querySelector("#tab-gen");
            var finTab = monitoring.querySelector("#tab-fin");
            var techTab = monitoring.querySelector("#tab-tech");

            if (typeof genTab == "undefined" || genTab == null) {
                if (typeof finTab != "undefined" && finTab != null) {
                    finTab.querySelector("tr th.shapter-technical").remove();
                    var techHeaders = finTab.querySelectorAll('.tech');
                    for (var i = 0; i < techHeaders.length; ++i) {
                        techHeaders[i].remove();
                    }
                    finTab.style = "display: block;";
                } else  if (typeof techTab != "undefined" && techTab != null) {
                    techTab.querySelector("tr th.shapter-financial").remove();
                    var finHeaders = techTab.querySelectorAll('.fin');
                    for (var i = 0; i < finHeaders.length; ++i) {
                        finHeaders[i].remove();
                    }
                    techTab.style = "display: block;";
                }
            } else {
                monitoringForm.querySelector(".tab-navs").style = "display: inline-block";
                finTab.querySelector("tr th.shapter-technical").remove();
                techTab.querySelector("tr th.shapter-financial").remove();

                var techHeaders = finTab.querySelectorAll('.tech');
                for (var i = 0; i < techHeaders.length; ++i) {
                    techHeaders[i].remove();
                }

                var finHeaders = techTab.querySelectorAll('.fin');
                for (var i = 0; i < finHeaders.length; ++i) {
                    finHeaders[i].remove();
                }
            }
        })();

        // on sort arrow click function
        function sortClick(elem, sortValue, sortDescValue)
        {
            var form = monitoring.querySelector('.monitoring-pjax-form');
            var sortOrderField = form.querySelector('input[name=sortOrder]');

            if (elem.classList.contains('dropup')) {
                var sortOrder = sortDescValue;
            } else {
                var sortOrder = sortValue;
            }

            sortOrderField.value = sortOrder;
            elem.classList.add('active');
            form.querySelector('button[type=submit]').click();
        }
        
        // on sort arrow click process function
        function sortClickProc(elem, selector, sortAsc, sortDesc, type)
        {
            var length = monitoring.querySelectorAll(selector).length;

            if (elem.classList.contains('dropup')) {
                var sortOrder = sortDesc;
                elem.classList.remove('dropup');
                elem.classList.add('dropdown');
            } else {
                var sortOrder = sortAsc;
                elem.classList.remove('dropdown');
                elem.classList.add('dropup');
            }

            var arrayAsc = [
                <?= MonitoringController::SORT_BY_ADDRESS ?>,
                <?= MonitoringController::SORT_BY_BALANCEHOLDER ?>,
                <?= MonitoringController::SORT_BY_SERIAL ?>
            ];

            var arrayDesc = [
                <?= MonitoringController::SORT_BY_ADDRESS_DESC ?>,
                <?= MonitoringController::SORT_BY_BALANCEHOLDER_DESC ?>,
                <?= MonitoringController::SORT_BY_SERIAL_DESC ?>
            ];

            for (var j = length - 1; j > 0; --j) {
                var lines = monitoring.querySelectorAll(selector);
                var currentElem = lines[0];
                var lastElem = lines[j];
                for (var i = 1; i <= j; ++i) {
                    var item = lines[i];
                    var itemValue = item.value;
                    var currValue = currentElem.value;

                    if (type == 'int') {
                        if (parseInt(itemValue)) {
                            itemValue = parseInt(itemValue);
                        } else {
                            if (arrayAsc.includes(sortOrder)) {
                                itemValue = MAX_VALUE;
                            } else {
                                itemValue = MIN_VALUE;
                            }
                        }

                        if (parseInt(currValue)) {
                            currValue = parseInt(currValue);
                        } else {
                            if (arrayAsc.includes(sortOrder)) {
                                currValue = MAX_VALUE;
                            } else {
                                currValue = MIN_VALUE;
                            }
                        }
                    }

                    if (arrayAsc.includes(sortOrder)) {
                        var condition = itemValue > currValue;
                    } else {
                        var condition = itemValue < currValue;
                    }

                    if (condition) {
                        currentElem = item;
                    }
                }
                putItemAfter(currentElem.closest('tr.upper-row'), lastElem.closest('tr.upper-row'));
            }
        }

        //sort click aggregated function 
        function sortClickFunc(elemSelector, selector, sortAsc, sortDesc, type)
        {
            var selectors = [];

            if (monitoring.querySelector("#tab-gen")) {
                selectors.push("#tab-gen " + selector);
            }

            if (monitoring.querySelector("#tab-tech")) {
                selectors.push("#tab-tech " + selector);
            }

            if (monitoring.querySelector("#tab-fin")) {
                selectors.push("#tab-fin " + selector);
            }

            for (var i = 0; i < selectors.length; ++i) {
                var elem = monitoring.querySelector(selectors[i].split(" ")[0] + ' ' + elemSelector);
                sortClickProc(elem, selectors[i], sortAsc, sortDesc, type);
            }
        }

        // serial columns sort
        for (var i = 0; i < monitoringSerialSorts.length; ++i) {
            monitoringSerialSort = monitoringSerialSorts[i];
            monitoringSerialSort.onclick = function() {
                sortClickFunc(
                    ".header-block .number",
                    ".address-serial-number",
                    <?= MonitoringController::SORT_BY_SERIAL ?>,
                    <?= MonitoringController::SORT_BY_SERIAL_DESC ?>,
                    "int"
                );
            }
        }

        // balance holders sort
        for (var i = 0; i < monitoringBalanceHolderSorts.length; ++i) {
            monitoringBalanceHolderSort = monitoringBalanceHolderSorts[i];
            monitoringBalanceHolderSort.onclick = function() {
                sortClickFunc(
                    ".header-block .bhname",
                    ".search-bh-value",
                    <?= MonitoringController::SORT_BY_BALANCEHOLDER ?>,
                    <?= MonitoringController::SORT_BY_BALANCEHOLDER_DESC ?>,
                    "string"
                );
            }
        }

        // addresses sort
        for (var i = 0; i < monitoringAddressSorts.length; ++i) {
            monitoringAddressSort =  monitoringAddressSorts[i];
            monitoringAddressSort.onclick = function() {
                sortClickFunc(
                    ".header-block .address",
                    ".search-address-value",
                    <?= MonitoringController::SORT_BY_ADDRESS ?>,
                    <?= MonitoringController::SORT_BY_ADDRESS_DESC ?>,
                    "string"
                );
            }
        }

        // update page script
        <?= Yii::$app->view->render('/monitoring/data/ajax_update_handler', ['timestamp' => $timestamp, 'timeOut' => $timeOut]) ?>
    }());
</script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />