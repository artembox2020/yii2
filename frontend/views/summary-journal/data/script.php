<script>
    (function() 
    {
        var numberOfDays = <?= $numberOfDays ?>;
        var summaryJournal = document.querySelector('.summary-journal');

        // appends tr to table
        function appendTr(element, value)
        {
            var tr = document.createElement('tr');
            tr.dataset.key = 1;
            var td = document.createElement('td');
            td.classList.add('cell-device');
            td.innerHTML = value;
            tr.appendChild(td);
            element.appendChild(tr);
        }

        // appends tr to table and sets rowspan
        function appendTr2(element, value, rowspan)
        {
            var tr = document.createElement('tr');
            tr.dataset.key = 1;
            tr.style.height = 39 * rowspan + 'px';
            tr.style.width = '100%';
            var td = document.createElement('td');
            td.classList.add('cell-device');
            td.innerHTML = value;
            tr.appendChild(td);
            element.appendChild(tr);
        }

        // makes float value from element
        function parse(element)
        {
            var number = parseFloat(element.innerHTML);
            if (isNaN(number)) {

                return 0;
            }

            return preciseNumber(number);
        }

        // makes float value from element
        function makeNumberFromElement(element, divideBy)
        {
            var number = parseFloat(element.innerHTML), sum = 0;

            if (number != 0) {
                if (typeof divideBy != "undefined" && divideBy != null && divideBy.innerHTML != null) {
                    divideBy = parseFloat(divideBy.innerHTML);
                    sum = number / divideBy;
                }
            }

            return preciseNumber(sum);
        }

        // precises number
        function preciseNumber(number)
        {
            if (isNaN(number)) {

                return 0;
            }
            if (Math.round(number) != number) {
                number = parseFloat(number).toFixed(2);
            }

            return parseFloat(number);
        }

        // makes division and precision
        function divideBy(number, divide)
        {
            if (typeof divide != "undefined" && divide) {
                    number = number / parseFloat(divide);
            } else {
                number = 0;
            }

            if (Math.round(number) != number) {
                number = number.toFixed(2);
            }

            return parseFloat(number);
        }

        // returns empty tbody tag by selector
        function getBodyBySelector(selector)
        {
            var table = summaryJournal.querySelector(selector);
            var tbody = table.querySelector('tbody');
            tbody.innerHTML = '';

            return tbody;
        }

        //makes serial column
        function makeSerialColumn() {
            var addressContainers = summaryJournal.querySelectorAll('td.balance-address-container > table');
            var mashinesCount = 0;

            for (var i = 0; i < addressContainers.length; ++i) {
                var count = 0;
                var mashineNumberCells = addressContainers[i].querySelectorAll('td.mashine-numbers-cell');
                if (mashineNumberCells.length > 0) {
                    count = 0;
                    for (var k = 0; k < mashineNumberCells.length; ++k) {
                        var mashinesNumber = parseInt(mashineNumberCells[k].dataset.mashinesNumber);
                        if (mashinesNumber == 0 || isNaN(mashinesNumber)) {
                            mashinesNumber = 1;
                        }

                        count += mashinesNumber;
                    }
                } else {
                    count += parseInt(addressContainers[i].dataset.count);
                }
                mashinesCount += count;
            }

            var serialColumnRows = summaryJournal.querySelectorAll('.table-serial-column tr');
        
            for (var i = mashinesCount; i < mashinesCount + 2 && i < serialColumnRows.length; ++i) {
                serialColumnRows[i].innerHTML = '<td>&nbsp;&nbsp;</td>';
            }
        
            for (var i = mashinesCount + 2; i < serialColumnRows.length; ++i) {
                serialColumnRows[i].style.display = 'none';
            }
        }

        // removes empty balance holders
        function removeEmptyBalanceHolders() {
            var addressContainerTables = summaryJournal.querySelectorAll('.table-address-container');
            for (var i = 0; i < addressContainerTables.length; ++i) {
                var table = addressContainerTables[i];
                if (table.dataset.count == 0) {
                    var tr = table.closest('tr');
                    tr.parentNode.removeChild(tr);
                }
            }
        }

        // calculates total incomes by addresses
        function makeIncomesSummaryByAddresses()
        {
            var incomeTable = summaryJournal.querySelector('.table-income');
            var tbody = getBodyBySelector('.table-summary-addresses');
            var incomeTableRows = incomeTable.querySelectorAll('tr');
            var sumTotal = 0.00;
            for (var i = 0; i < incomeTableRows.length; ++i) {
                if (i == incomeTableRows.length - 2) {
                    sum = preciseNumber(sumTotal);
                } else if (i == incomeTableRows.length - 1) {
                    sum = preciseNumber(parseFloat(sumTotal) / (incomeTableRows.length -2));
                } else {
                    var row = incomeTableRows[i];
                    var tds = row.querySelectorAll('td');
                    var sum = 0;
                    for (var j = 0; j < tds.length; ++j) {
                        sum += parse(tds[j]);
                    }
                    sumTotal  += preciseNumber(sum);
                }
                appendTr(tbody, preciseNumber(sum));
            }
        }

        // calculates average incomes by addresses
        function makeIncomesAverageSummaryByAddresses()
        {
            var averageSummaryTable = summaryJournal.querySelector('.table-average-summary-addresses');
            var summaryAddressesTable = summaryJournal.querySelector('.table-income');
            var tbody = averageSummaryTable.querySelector('tbody');
            tbody.innerHTML = '';
            var averageSummaryRows = summaryAddressesTable.querySelectorAll('tr');
            var sumTotal = 0; 
            for (var i = 0; i < averageSummaryRows.length; ++i) {
                if (i == averageSummaryRows.length - 2) {
                    sum = sumTotal;
                } else if (i == averageSummaryRows.length - 1) {
                    sum = preciseNumber(sumTotal / (averageSummaryRows.length - 2));
                } else {
                    var row = averageSummaryRows[i];
                    var tds = row.querySelectorAll('td');
                    var totalDays = 0, sum = 0;
                    for (var j = 0; j < tds.length; ++j) {
                        if (!tds[j].classList.contains('not-set-income')) {
                            ++totalDays;
                            sum += parse(tds[j]);
                        }
                    }
                    sum = divideBy(sum, totalDays);
                    sumTotal += preciseNumber(sum);
                }
                appendTr(tbody, preciseNumber(sum));
            }
        }

        // makes incomes average by mashine
        function makeIncomesAverageMashineSummaryByAddresses()
        {
            var summaryAddressesTable = summaryJournal.querySelector('.table-summary-addresses');
            var tbody = getBodyBySelector('.table-average-mashine-summary-addresses');
            var averageSummaryRows = summaryAddressesTable.querySelectorAll('tr');
            var sumTotal = 0.00;
            for (var i = 0; i < averageSummaryRows.length; ++i) {
                if (i == averageSummaryRows.length - 2) {
                    sum = sumTotal;
                } else if (i == averageSummaryRows.length - 1) {
                    sum = preciseNumber(sumTotal / (averageSummaryRows.length - 2));
                } else {
                    var row = averageSummaryRows[i];
                    var td = row.querySelector('td');
                    var addressRowSelector = getTableAddressRowSelectorByIndex(i);
                    var mashineCountCell = summaryJournal.querySelector(addressRowSelector + ' td.mashine-count');
                    var sum = makeNumberFromElement(td, mashineCountCell);
                    sumTotal += preciseNumber(sum);
                }
                appendTr(tbody, preciseNumber(sum));
            }
        }

        // additional function tomake selector
        function getTableAddressRowSelectorByIndex(index)
        {
            var tableAddressContainers = summaryJournal.querySelectorAll('.table-address-container');
            var addressRowsCount = 0;
            var j = 0;
            var kIndex = index + 1;
            for (j = 0; j < tableAddressContainers.length; ++j) {
                var tableContainer = tableAddressContainers[j];
                var trs =  tableContainer.querySelectorAll('tr');

                if (addressRowsCount + trs.length >= index +1) {
                    kIndex = index + 1 - addressRowsCount;
                    break;
                }
                addressRowsCount += trs.length;
            }

            return '.table-container tr:nth-child(' + (j+1) + ') .table-address-container tr:nth-child(' + (kIndex) + ')';
        }

        // makes incomes average by citizens
        function makeIncomesAverageCitizensSummaryByAddresses()
        {
            var summaryAddressesTable = summaryJournal.querySelector('.table-summary-addresses');
            var tbody = getBodyBySelector('.table-average-citizens-summary-addresses');
            var averageSummaryRows = summaryAddressesTable.querySelectorAll('tr');
            var sumTotal = 0;
            for (var i = 0; i < averageSummaryRows.length; ++i) {
                if (i == averageSummaryRows.length - 2) {
                    sum = sumTotal;
                } else if (i == averageSummaryRows.length - 1) {
                    sum = preciseNumber(sumTotal / (averageSummaryRows.length - 2));
                } else {
                    var row = averageSummaryRows[i];
                    var td = row.querySelector('td');
                    var mashineCountCell = summaryJournal.querySelector(getTableAddressRowSelectorByIndex(i) + ' td.number-of-citizens');
                    var sum = makeNumberFromElement(td, mashineCountCell);
                    sumTotal += preciseNumber(sum);
                }
                appendTr(tbody, preciseNumber(sum));
            }
        }

        // makes consolidated summary byaddresses
        function makeConsolidatedSummaryByAddresses()
        {
            var summaryAddressesTable = summaryJournal.querySelector('.table-summary-addresses');
            var tbody = getBodyBySelector('.table-consolidated-summary-addresses');
            var averageSummaryRows = summaryAddressesTable.querySelectorAll('tr');
            for (var i = 0; i < averageSummaryRows.length; ++i) {
                var row = averageSummaryRows[i];
                appendTr(tbody, '-');
            }
        }

        // makes expectation
        function makeExpectation()
        {
            var summaryAddressesTable = summaryJournal.querySelector('.table-summary-addresses');
            var tbody = getBodyBySelector('.table-expectation');
            var averageSummaryRows = summaryAddressesTable.querySelectorAll('tr');
            var sumTotal = 0;
            for (var i = 0; i < averageSummaryRows.length; ++i) {
                if (i == averageSummaryRows.length - 2) {
                    sum = sumTotal;
                } else if ( i == averageSummaryRows.length -1) {
                    sum = preciseNumber(sumTotal / (averageSummaryRows.length - 2));
                } else {
                    var row = averageSummaryRows[i];
                    var averageByDay = summaryJournal.querySelector('.table-average-summary-addresses tr:nth-child(' + (i+1) + ') td');
                    averageByDay = parse(averageByDay);

                    var income = summaryJournal.querySelector('.table-summary-addresses tr:nth-child(' + (i+1) + ') td');
                    income = parse(income);
                    var sum = averageByDay * numberOfDays - income;
                    sumTotal += preciseNumber(sum);
                }
                appendTr(tbody, preciseNumber(sum));
            }
        }

        // makes expectation by balance holders
        function makeExpectationByBalanceHolders()
        {
            var expectationTable = summaryJournal.querySelector('.table-expectation');
            var tbody = getBodyBySelector('.table-expectation-by-balance-holders');
            var addressContainers = summaryJournal.querySelectorAll('.table-container .table-address-container');
            var expectationRows = expectationTable.querySelectorAll('tr');
            var totalSum = 0, i = 0;
            for (var j = 0; i < addressContainers.length; ++i) {
                var count = parseInt(addressContainers[i].dataset.count);
                var mashineNumberCells = addressContainers[i].querySelectorAll('td.mashine-numbers-cell');
                if (mashineNumberCells.length > 0) {
                    count = 0;
                    for (var k = 0; k < mashineNumberCells.length; ++k) {
                        var mashinesNumber = parseInt(mashineNumberCells[k].dataset.mashinesNumber);
                        if (mashinesNumber == 0) {
                            ++mashinesNumber;
                        }
    
                        count += mashinesNumber;
                    }
                }
                for (var k = j, sum = 0; k < j + count; ++k) {
                    var row = expectationRows[k];
                    var td = row.querySelector('td');
                    sum += parse(td);
                }
                j += count;
                appendTr2(tbody, preciseNumber(sum), count);
                totalSum += sum;
            }
            var numberOfBalanceHolders = tbody.querySelectorAll('tr').length;
            appendTr(tbody, preciseNumber(totalSum));
            appendTr(tbody, preciseNumber(totalSum/numberOfBalanceHolders));
        }

        // makes idle days
        function makeIdleDays()
        {
            var incomeTable = summaryJournal.querySelector('.table-income');
            var tbody = getBodyBySelector('.table-idle-days');
            var incomeRows = incomeTable.querySelectorAll('tr');
            var sumTotal = 0.00;
            for (var i = 0; i < incomeRows.length; ++i) {
                if (i == incomeRows.length - 2) {
                    totalIdles = preciseNumber(sumTotal);
                } else if (i == incomeRows.length - 1) {
                    totalIdles = preciseNumber(sumTotal / (incomeRows.length - 2));
                } else {
                    var row = incomeRows[i];
                    var tds = row.querySelectorAll('td');
                    var totalIdles = 0;
                    for (var j = 0; j < tds.length; ++j) {
                        if (tds[j].classList.contains('idle')) {
                            totalIdles += preciseNumber(parseFloat(tds[j].dataset.idleHours) / 24);
                        }
                    }
                    sumTotal += totalIdles;
                }
                appendTr(tbody, preciseNumber(totalIdles));
            }
        }

        // makes idle days damages
        function makeIdleDamages()
        {
            var averageIncomeTable = summaryJournal.querySelector('.table-average-summary-addresses')
            var tbody = getBodyBySelector('.table-idle-damages');
            var averageIncomeRows = averageIncomeTable.querySelectorAll('tr');
            var sumTotal = 0;
            for (var i = 0; i < averageIncomeRows.length; ++i) {
                if (i == averageIncomeRows.length - 2) {
                    sum = preciseNumber(sumTotal);
                } else if (i == averageIncomeRows.length -1) {
                    sum = preciseNumber(sumTotal / (averageIncomeRows.length - 2));
                } else {
                    var row = averageIncomeRows[i];
                    var td = row.querySelector('td');
                    var idleDays = summaryJournal.querySelector('.table-idle-days tr:nth-child(' + (i+1) + ') td');
                    var sum = parse(td) * parse(idleDays);
                    sumTotal += preciseNumber(sum);
                }
                appendTr(tbody, preciseNumber(sum));
            }
        }

        // makes summary conclusion
        function makeSummaryConclusion()
        {
            var addressesSummaryTable = summaryJournal.querySelector('.table-summary-addresses');
            var tbody = getBodyBySelector('.table-summary-conclusion');
            var addressContainers = summaryJournal.querySelectorAll('.table-container .table-address-container');
            var addressesSummaryRows = addressesSummaryTable.querySelectorAll('tr');
            var totalSum = 0, i = 0;
            for (var j = 0; i < addressContainers.length; ++i) {
                var count = parseInt(addressContainers[i].dataset.count);
                var mashineNumberCells = addressContainers[i].querySelectorAll('td.mashine-numbers-cell');
                if (mashineNumberCells.length > 0) {
                    count = 0;
                    for (var k = 0; k < mashineNumberCells.length; ++k) {
                        var mashinesNumber = parseInt(mashineNumberCells[k].dataset.mashinesNumber);
                        if (mashinesNumber == 0) {
                            ++mashinesNumber;
                        }
    
                        count += mashinesNumber;
                    }
                }
                for (var k = j, sum = 0; k < j + count; ++k) {
                    var row = addressesSummaryRows[k];
                    var td = row.querySelector('td');
                    sum += parse(td);
                }
                j += count;
                appendTr2(tbody, preciseNumber(sum), count);
                totalSum += sum;
            }
            var percentForLastYear = 100;
            var lastYearIncome = <?= $lastYearIncome ?>;
            if (lastYearIncome != 0) {
                percentForLastYear = parseInt(totalSum / lastYearIncome * 100);
            }
            <?php if ($isDetailed): ?>
                var lastMonthIncome = <?= $lastMonthIncome ?>;
                var percentForLastMonth = 100;
                if (lastMonthIncome != 0) {
                    percentForLastMonth = parseInt(totalSum / lastMonthIncome * 100);
                }
                appendTr(tbody, percentForLastMonth + '%');
            <?php else: ?>
                appendTr(tbody, preciseNumber(totalSum));
            <?php endif; ?>
            appendTr(tbody, percentForLastYear + '%');
        }

        // gets total number of all mashines 
        function getNumberOfAllMachines()
        {
            var mashineCountCells = summaryJournal.querySelectorAll('.mashine-count');
            var total = 0;
            for (var i = 0; i < mashineCountCells.length; ++i) {
                total += parse(mashineCountCells[i]);
            }

            return total;
        }

        // makes total summary
        function makeTotalSummary()
        {
            var tableContainer = summaryJournal.querySelector('.table-container tbody');
            var tr = document.createElement('tr');
            var td = document.createElement('td');
            var mashinesCount = getNumberOfAllMachines();
            var table = document.createElement('table');
            td.innerHTML = '<?= Yii::t('frontend', 'Total from all machines')  ?>';
            tr.appendChild(td);

            td = document.createElement('td');
            td.classList.add('total-mashines-count');
            td.innerHTML = ' &nbsp;' + mashinesCount;
            tr.appendChild(td);
            table.appendChild(tr);
            table.classList.add('table-mashines-count');
            tableContainer.appendChild(table);
        }

        //makes total average summary
        function makeTotalAverage()
        {
            var tableContainer = summaryJournal.querySelector('.table-container tbody');
            var tr = document.createElement('tr');
            var td = document.createElement('td');
            var mashinesCount = getNumberOfAllMachines();
            var table = document.createElement('table');
            td.innerHTML = '<?= Yii::t('frontend', 'Average from one') ?>';
            tr.appendChild(td);

            td = document.createElement('td');
            td.classList.add('total-mashines-count');
            td.innerHTML = ' &nbsp;' + mashinesCount;
            tr.appendChild(td);
            table.appendChild(tr);
            table.classList.add('table-mashines-count');
            tableContainer.appendChild(table);
        }

        // makes cell red | green, depending on timestamps
        function makeCellColor(booleanIsDeleted, timestampInserted, timestampDeletedAt, cell)
        {
            var tableMonth = summaryJournal.querySelector('.table-month');
            var timestampStart = tableMonth.dataset.stampStart;
            var timestampEnd = tableMonth.dataset.stampEnd;
            
            if (timestampInserted >= timestampStart && timestampInserted <= timestampEnd) {
                cell.classList.add('green-color');
            }

            if (booleanIsDeleted && timestampDeletedAt > timestampStart && timestampDeletedAt <= timestampEnd) {
                cell.classList.add('red-color');
            }
        }

        // updates cell color marking for addresses (green and red mark added or deleted ones)
        function updateCellColorMarking()
        {
            var tableMonth = summaryJournal.querySelector('.table-month');
            var timestampStart = tableMonth.dataset.stampStart;
            var timestampEnd = tableMonth.dataset.stampEnd;

            var tableAddressContainerRows = summaryJournal.querySelectorAll('.table-address-container > tbody > tr');
            for (var i = 0; i < tableAddressContainerRows.length; ++i) {
                // update address status (added, deleted)
                var row = tableAddressContainerRows[i];
                var timestampInserted = row.querySelector('td.date-inserted').innerHTML;
                var booleanIsDeleted = parseInt(row.querySelector('td.is_deleted').innerHTML);
                var timestampDeletedAt = parseInt(row.querySelector('td.deleted_at').innerHTML);
                timestampInserted = preciseNumber(timestampInserted);
                makeCellColor(booleanIsDeleted, timestampInserted, timestampDeletedAt, row.querySelector('td.address'));

                // update balance holder status (added,  deleted)
                var balanceHolderTr = tableAddressContainerRows[i].closest('.balance-address-container').closest('tr');
                var booleanIsDeleted = parseInt(balanceHolderTr.querySelector('td.is_deleted').innerHTML);
                var timestampDeletedAt = parseInt(balanceHolderTr.querySelector('td.deleted_at').innerHTML);
                var timestampInserted = preciseNumber(balanceHolderTr.querySelector('td.date-inserted').innerHTML);
                makeCellColor(booleanIsDeleted, timestampInserted, timestampDeletedAt, balanceHolderTr.querySelector('.cell-device'));
            }
        }

        // applies all functions
        removeEmptyBalanceHolders();
        makeSerialColumn();
        makeIncomesSummaryByAddresses();
        makeIncomesAverageSummaryByAddresses();
        <?php if (!$isDetailed): ?>
            makeIncomesAverageMashineSummaryByAddresses();
        <?php endif; ?>
        makeIncomesAverageCitizensSummaryByAddresses();
        makeConsolidatedSummaryByAddresses();
        makeExpectation();
        makeExpectationByBalanceHolders();
        makeIdleDays();
        makeIdleDamages();
        makeSummaryConclusion();
        makeTotalSummary();
        makeTotalAverage();
        updateCellColorMarking();
    }());
</script>