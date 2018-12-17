<script>
    (function() 
    {
        var monitoring = document.querySelector('.monitoring');
        var monitoringShapter = monitoring.querySelector('.monitoring-shapter');
        var monitoringDropList = monitoringShapter.querySelector("*[name=monitoring_shapter]");
        var monitoringDevices = monitoring.querySelectorAll('.devices .cell-device a');
        var monitoringForm = monitoring.querySelector('.monitoring-filter-form');
        var monitoringFormAddress = monitoringForm.querySelector('input[name=address]');
        var monitoringFormImei = monitoringForm.querySelector('input[name=imei]');
        var monitoringFormSortOrder = monitoringForm.querySelector('select[name=sortOrder]');
        var monitoringSerialNumbers = monitoring.querySelectorAll('input.address-serial-number');

        // displays by query selector
        function displayByQuerySelector(selector) {
            hideByQuerySelector('.imei, .common, .remote-connection, .devices, .terminal, .financial');
            var  elementsBySelector = monitoring.querySelectorAll(selector);

            for (var i = 0; i < elementsBySelector.length; ++i) {
                elementsBySelector[i].style.display = 'table-cell';
            }

            if (selector.indexOf('.common') != -1 || selector.indexOf('.all') != -1 ) {
                var commonElements = monitoring.querySelectorAll('.common');

                for (var i = 0; i < commonElements.length; ++i) {

                    if (commonElements[i].tagName == 'TD') {
                        commonElements[i].style.display = 'block';
                    }
                }
            }
        }

        // hides by query selector
        function hideByQuerySelector(selector) {
            var  elementsBySelector = monitoring.querySelectorAll(selector);

            for (var i = 0; i < elementsBySelector.length; ++i) {
                elementsBySelector[i].style.display = 'none';
            }
        }

        // open in a new tab on device reference click        
        for (var i = 0; i < monitoringDevices.length; ++i) {
            monitoringDevices[i].onclick = function(e) {
                e.preventDefault();
                window.open(this.href,  '_blank');
            }
        }

        // monitoring droplist on change function
        monitoringDropList.onchange = function()
        {
            if (this.value == 'all') {
                displayByQuerySelector('.'+this.value);
            } else {
                displayByQuerySelector('.'+ this.value + ', .imei');
            }
        };

        // removes option 'all' on small devices
        if (screen.width < <?= $smallDeviceWidth ?>) {
            var optionNode = monitoringDropList.querySelector('option[value=all]');
            optionNode.parentNode.removeChild(optionNode);
        } else {
            //monitoringShapter.style.display = 'block';
            displayByQuerySelector('.common, .remote-connection, .devices, .terminal, .financial');
        }

        //generates on change event
        monitoringDropList.onchange();

        // adjusts height of the common table to the bottom of parent
        function adjustCommonTableSize()
        {
            var commonTables = monitoring.querySelectorAll('.common-container');

            for (var i = 0; i < commonTables.length; ++i) {
                var closestCell = commonTables[i].closest('tr');
                var commonHeader = commonTables[i].querySelector('.common-header');
                var styleObject = window.getComputedStyle(closestCell);
                var commonHeaderStyleObject = window.getComputedStyle(commonHeader);
                var cells = commonTables[i].querySelectorAll('.cell');
                var height = parseInt(styleObject.getPropertyValue('height'));
                height -= 39;

                for (var j = 0; j < cells.length; ++j) {
                    cells[j].style.height = height + 'px';
                    cells[j].style.paddingTop = (height / 2 - 90) + 'px';
                }
            }
        }

        // adjusts last row height of the modem card table to the bottom of parent
        function adjustModemCardTableSize()
        {
            var modemCardTableRows = monitoring.querySelectorAll('tr.modem-card-last-row');

            if (modemCardTableRows != null) {

                for (var i= 0; i < modemCardTableRows.length; ++i) {
                    var closestCell = modemCardTableRows[i].closest('td.all');
                    var modemCardTable = closestCell.querySelector('table');
                    var closestCellStyleObject = window.getComputedStyle(closestCell);
                    var modemCardStyleObject = window.getComputedStyle(modemCardTable);
                    var modemCardRowStyleObject = window.getComputedStyle(modemCardTableRows[i]);
                    var height = - parseInt(modemCardStyleObject.getPropertyValue('height'));
                    height = height + parseInt(closestCellStyleObject.getPropertyValue('height'));
                    modemCardTableRows[i].style.height = parseInt(modemCardRowStyleObject.getPropertyValue('height')) + height + 'px';
                }
            }
        }

        // adjusts last row height of the table to the bottom of parent
        function adjustTableSize(tableClass, cellClass, shift)
        {
            var closestCells = monitoring.querySelectorAll('td.' + tableClass);
            if (closestCells != null) {

                for (var i = 0; i < closestCells.length; ++i) {
                    var tableRows = closestCells[i].querySelectorAll('.' + cellClass);
                    var tableRowsLength = tableRows.length;

                    if (tableRowsLength > 0) {
                        var tableRow = tableRows[tableRowsLength- 1].closest('tr');
                        var table = closestCells[i].querySelector('table');
                        var closestCellStyleObject = window.getComputedStyle(closestCells[i]);
                        var tableStyleObject = window.getComputedStyle(table);
                        var tableRowStyleObject = window.getComputedStyle(tableRow);
                        var height = - parseInt(tableStyleObject.getPropertyValue('height'));
                        height = height + parseInt(closestCellStyleObject.getPropertyValue('height')) - 1;
                        tableRow.style.height = parseInt(tableRowStyleObject.getPropertyValue('height')) + height + shift + 'px';
                    }
                }
            }
        }

        // adjusts all rows height of the table to equal each other
        function adjustTableSizeTheSameHeight(tableClass, cellClass, shift)
        {
            adjustTableSize(tableClass, cellClass, shift);
            var closestCells = monitoring.querySelectorAll('td.' + tableClass);

            if (closestCells != null) {

                for (var i = 0; i < closestCells.length; ++i) {
                    var tableRows = closestCells[i].querySelectorAll('.' + cellClass);
                    var tableRowsLength = tableRows.length;
                    var totalHeight = 0;

                    for (var j = 0; j < tableRowsLength; ++j) {
                        var tableRowStyleObject = window.getComputedStyle(tableRows[j].closest('tr'));
                        totalHeight += parseInt(tableRowStyleObject.getPropertyValue('height'));
                    }

                    var height = parseInt(totalHeight / tableRowsLength);

                    for (var j = 0; j < tableRowsLength; ++j) {
                        tableRows[j].closest('tr').style = "height: " + height + "px;";
                    }
                }
            }
        }

        // hides redundant headers
        function hideRedundantHeaders()
        {
            var headers = monitoring.querySelectorAll('thead');
            for (var i = <?= $numberRedundantHeaders ?>, zIndex = 1000; i < headers.length; ++i) {
                var td = headers[i].closest('tr.common');

                if (td != null) {
                    headers[i].style.display = 'none';
                }

                var headerTr = headers[i].querySelector('tr');

                headers[i].closest('table').style.position = 'relative';
                headers[i].closest('table').style.marginTop = "-40px";
                headers[i].closest('table').style.zIndex = --zIndex;
            }
        }

        // hides redundant headers of the common table
        function hideRedundantCommonHeaders()
        {
            var containers = monitoring.querySelectorAll('.common-container');

            for (var i = 1; i < containers.length; ++i) {
                containers[i].querySelector('.common-header').style.display = 'none';
            }
        }

        // adjusts cell height by selector
        function adjustCellsHeightBySelector(heightAddition, selector, startPosition)
        {
            var cells = monitoring.querySelectorAll(selector);
            for (var i = startPosition; i < cells.length; ++i)
            {
                var cellStyleObject = window.getComputedStyle(cells[i]);
                cells[i].style.height = parseInt(cellStyleObject.getPropertyValue('height')) + heightAddition + 'px';
            }
        }
        
        // applies filter by value 
        function applyFilterByValue(value, inputSelector)
        {
            var gridView = monitoring.querySelector('.grid-view');
            var table = gridView.querySelector('table');
            var tableRows = table.querySelectorAll('tr.rows');
            if (typeof value != 'undefined' && value != null && value.trim() != '') {
                value = value.toLowerCase();
                var inputSearch = monitoring.querySelectorAll('input'+ inputSelector+'[value*="' + value + '"]');

                for (var j = 0; j < tableRows.length; ++j) {
                    tableRows[j].style.display = 'none';
                }

                for (var i = 0; i < inputSearch.length; ++i) {
                    var tr = inputSearch[i].closest('tr.rows');
                    var table = tr.closest('table');
                    var tableRows = table.querySelectorAll('tr.rows');

                    for (var j = 0; j < tableRows.length; ++j) {
                        if (tableRows[j].dataset.key == tr.dataset.key) {
                            tableRows[j].style.display = 'table-row';
                        }
                    }
                }
            } else {

                for (var j = 0; j < tableRows.length; ++j) {
                    tableRows[j].style.display = 'table-row';
                }
            }
        }

        // applies all table processing functions

        adjustCommonTableSize();
        adjustModemCardTableSize();
        adjustTableSizeTheSameHeight('devices', 'cell-device', 0);
        adjustTableSizeTheSameHeight('financial', 'cell-financial', 0);
        adjustTableSizeTheSameHeight('terminal', 'cell-bill-acceptance', 0);
        adjustTableSizeTheSameHeight('terminal', 'cell-software', 0);
        adjustTableSizeTheSameHeight('terminal', 'cell-actions', 0);
        adjustTableSizeTheSameHeight('terminal', 'cell-modem', 0);
        adjustCellsHeightBySelector(40, '.financial tr.modem-card-last-row', 1);
        adjustCellsHeightBySelector(40, '.terminal tr.modem-card-last-row', 1);

        hideRedundantHeaders();
        hideRedundantCommonHeaders();

        // monitoring form address change function
        monitoringFormAddress.onchange = function()
        {
            applyFilterByValue(this.value, '.search-address-value');
            var monitoringFormInputs = monitoringForm.querySelectorAll('input[name=imei]');

            for (var i = 0; i < monitoringFormInputs.length; ++i) {
                monitoringFormInputs[i].value = '';
            }
        }

        // monitoring form imei change function
        monitoringFormImei.onchange = function()
        {
            applyFilterByValue(this.value, '.search-imei-value');
            var monitoringFormInputs = monitoringForm.querySelectorAll('input[name=address]');

            for (var i = 0; i < monitoringFormInputs.length; ++i) {
                monitoringFormInputs[i].value = '';
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

        // update page script
        <?= Yii::$app->view->render('/monitoring/data/ajax_update_handler', ['timestamp' => $timestamp, 'timeOut' => $timeOut]) ?>
    }());
</script>