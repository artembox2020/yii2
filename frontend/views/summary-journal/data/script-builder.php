<script>
var Builder = {};

// returns empty tbody tag by selector
Builder.getBodyBySelector = function(selector)
{
    var table = summaryJournal.querySelector(selector);

    if (table != null)
    {
        var tbody = table.querySelector('tbody');
        tbody.innerHTML = '';
    } else {
        var tbody = document.createElement('tbody');
        tbody.innerHTML = '';
    }

    return tbody;
}

//makes serial column
Builder.makeSerialColumn = function()
{
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
Builder.removeEmptyBalanceHolders = function()
{
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
Builder.makeIncomesSummaryByAddresses = function()
{
    var incomeTable = summaryJournal.querySelector('.table-income');

    if (incomeTable == null) {

        return true;
    }

    var tbody = Builder.getBodyBySelector('.table-summary-addresses');
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
Builder.makeIncomesAverageSummaryByAddresses = function()
{
    var averageSummaryTable = summaryJournal.querySelector('.table-average-summary-addresses');

    if (averageSummaryTable == null) {

        return true;
    }

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
            var totalDays = 0, sum = 0, sumIncomes = 0;

            for (var j = 0; j < tds.length; ++j) {
                if (!tds[j].classList.contains('not-set-income')) {
                    ++totalDays;
                    sum += parse(tds[j]);
                    sumIncomes += parseIncomes(tds[j]);
                }
            }

            sum = divideBy(sum, totalDays);
            sumIncomes = divideBy(sumIncomes, totalDays);
            sumTotal += preciseNumber(sum);
        }

        //appendTr(tbody, preciseNumber(sum));
        appendTrWithAttrs(tbody, preciseNumber(sum), 'idleHours', sumIncomes);
    }
}

// makes incomes average by mashine
Builder.makeIncomesAverageMashineSummaryByAddresses = function(isDetailed)
{
    if (isDetailed) {

        return true;
    }

    var summaryAddressesTable = summaryJournal.querySelector('.table-summary-addresses');

    if (summaryAddressesTable == null) {

        return true;
    }

    var tbody = Builder.getBodyBySelector('.table-average-mashine-summary-addresses');
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
            var addressRowSelector = Builder.getTableAddressRowSelectorByIndex(i);
            var mashineCountCell = summaryJournal.querySelector(addressRowSelector + ' td.mashine-count');
            var sum = makeNumberFromElement(td, mashineCountCell);
            sumTotal += preciseNumber(sum);
        }

        appendTr(tbody, preciseNumber(sum));
    }
}

// additional function tomake selector
Builder.getTableAddressRowSelectorByIndex = function(index)
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
Builder.makeIncomesAverageCitizensSummaryByAddresses = function()
{
    var summaryAddressesTable = summaryJournal.querySelector('.table-summary-addresses');

    if (summaryAddressesTable == null) {

        return true;
    }

    var tbody = Builder.getBodyBySelector('.table-average-citizens-summary-addresses');
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
            var mashineCountCell = summaryJournal.querySelector(Builder.getTableAddressRowSelectorByIndex(i) + ' td.number-of-citizens');
            var sum = makeNumberFromElement(td, mashineCountCell);
            sumTotal += preciseNumber(sum);
        }

        appendTr(tbody, preciseNumber(sum));
    }
}

// makes consolidated summary by addresses
Builder.makeConsolidatedSummaryByAddresses = function()
{
    var summaryAddressesTable = summaryJournal.querySelector('.table-summary-addresses');

    if (summaryAddressesTable == null) {

        return true;
    }

    var tbody = Builder.getBodyBySelector('.table-consolidated-summary-addresses');
    var averageSummaryRows = summaryAddressesTable.querySelectorAll('tr');
    for (var i = 0; i < averageSummaryRows.length; ++i) {
        var row = averageSummaryRows[i];
        appendTr(tbody, '-');
    }
}

// makes expectation
Builder.makeExpectation = function()
{
    var summaryAddressesTable = summaryJournal.querySelector('.table-summary-addresses');

    if (summaryAddressesTable == null) {

        return true;
    }

    var tbody = Builder.getBodyBySelector('.table-expectation');
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

// makes idle days
Builder.makeExpectationByBalanceHolders = function()
{
    var expectationTable = summaryJournal.querySelector('.table-expectation');

    if (expectationTable == null) {

        return true;
    }

    var tbody = Builder.getBodyBySelector('.table-expectation-by-balance-holders');
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

// makes idle days damages
Builder.makeIdleDays = function()
{
    var incomeTable = summaryJournal.querySelector('.table-income');

    if (incomeTable == null) {

        return true;
    }

    var tbody = Builder.getBodyBySelector('.table-idle-days');
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
                if (!tds[j].classList.contains('not-set-income')) {
                    totalIdles += preciseNumber(parseFloat(tds[j].dataset.idleHours) / 24);
                }
            }
            sumTotal += totalIdles;
        }

        appendTr(tbody, preciseNumber(totalIdles));
    }
}

// makes idle damages summing
Builder.makeIdleDamages = function()
{
    var averageIncomeTable = summaryJournal.querySelector('.table-average-summary-addresses')

    if (averageIncomeTable == null) {

        return true;
    }

    var tbody = Builder.getBodyBySelector('.table-idle-damages');
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
            var sum = parseIdles(td) * parse(idleDays);
            sumTotal += preciseNumber(sum);
        }

        appendTr(tbody, preciseNumber(sum));
    }
}

// makes summary conclusion
Builder.makeSummaryConclusion = function()
{
    var addressesSummaryTable = summaryJournal.querySelector('.table-summary-addresses');

    if (addressesSummaryTable == null) {

        return true;
    }

    var tbody = Builder.getBodyBySelector('.table-summary-conclusion');
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
Builder.getNumberOfAllMachines = function()
{
    var mashineCountCells = summaryJournal.querySelectorAll('.mashine-count');
    var total = 0;

    for (var i = 0; i < mashineCountCells.length; ++i) {
        total += parse(mashineCountCells[i]);
    }

    return total;
}

// makes total summary
Builder.makeTotalSummary = function()
{
    var tableContainer = summaryJournal.querySelector('.table-container tbody');

    if (tableContainer == null) {

        return true;
    }

    var tr = document.createElement('tr');
    var td = document.createElement('td');
    var mashinesCount = Builder.getNumberOfAllMachines();
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
Builder.makeTotalAverage = function()
{
    var tableContainer = summaryJournal.querySelector('.table-container tbody');

    if (tableContainer == null) {

        return true;
    }

    var tr = document.createElement('tr');
    var td = document.createElement('td');
    var mashinesCount = Builder.getNumberOfAllMachines();
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
Builder.makeCellColor = function(booleanIsDeleted, timestampInserted, timestampDeletedAt, cell)
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
Builder.updateCellColorMarking = function()
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
        Builder.makeCellColor(booleanIsDeleted, timestampInserted, timestampDeletedAt, row.querySelector('td.address'));

        // update balance holder status (added,  deleted)
        var balanceHolderTr = tableAddressContainerRows[i].closest('.balance-address-container').closest('tr');
        var booleanIsDeleted = parseInt(balanceHolderTr.querySelector('td.is_deleted').innerHTML);
        var timestampDeletedAt = parseInt(balanceHolderTr.querySelector('td.deleted_at').innerHTML);
        var timestampInserted = preciseNumber(balanceHolderTr.querySelector('td.date-inserted').innerHTML);
        Builder.makeCellColor(booleanIsDeleted, timestampInserted, timestampDeletedAt, balanceHolderTr.querySelector('.cell-device'));
    }
}

// updates cell color marking for mashines number (green and red mark added or deleted ones)
Builder.numberMashinesCellColorMarking = function()
{
    var cells = summaryJournal.querySelectorAll('.timestamp.green-color, .timestamp.red-color, .timestamp.blue-color');

    for (var i = 0; i < cells.length; ++i) {
        var addressId = cells[i].dataset.addressId;
        var addressCell = summaryJournal.querySelector('.address-id-' + addressId);

        if (cells[i].classList.contains('green-color')) {
            addressCell.classList.add('green-color');
        }

        if (cells[i].classList.contains('red-color')) {
            addressCell.classList.add('red-color');
        }
    }
}

// applies all functions
Builder.make = function()
{
    Builder.removeEmptyBalanceHolders();
    Builder.makeSerialColumn();
    Builder.makeIncomesSummaryByAddresses();
    Builder.makeIncomesAverageSummaryByAddresses();
    Builder.makeIncomesAverageMashineSummaryByAddresses(<?= $isDetailed ?>);
    Builder.makeIncomesAverageCitizensSummaryByAddresses();
    Builder.makeConsolidatedSummaryByAddresses();
    Builder.makeExpectation();
    Builder.makeExpectationByBalanceHolders();
    Builder.makeIdleDays();
    Builder.makeIdleDamages();
    Builder.makeSummaryConclusion();
    Builder.makeTotalSummary();
    Builder.makeTotalAverage();
    Builder.updateCellColorMarking();
    Builder.numberMashinesCellColorMarking();
}

// clones idle hours journal 
Builder.cloneJournal = function()
{
    var body = document.querySelector('body');
    var wrap = body.querySelector('.wrap');
    wrap.style.height = 'auto';
    wrap.style.minHeight = '0';
    wrap.style.paddingBottom = '0';
    wrap.style.marginBottom = '0';

    var wrapClone = wrap.cloneNode(true);
    wrapClone.style.marginTop = '-60px';

    var filterType = body.querySelector('.filter-type');
    filterType.style.cursor = 'pointer';
    var filterTypeClone = filterType.cloneNode(true);
    filterTypeClone.querySelector('.expand-incomes').dataset.selector = '.summary-journal-clone';
    filterTypeClone.querySelector('.expand-incomes').classList.remove('glyphicon-minus');
    filterTypeClone.querySelector('.expand-incomes').classList.add('idle-incomes');

    wrapClone.classList.add('wrap-clone');
    wrapClone.querySelector('.container h1, .container .navbar').remove();
    wrapClone.querySelector('.container').style.paddingTop = '0';
    var summaryJournalNode = wrapClone.querySelector('.container form.summary-journal-form div.summary-journal').cloneNode(true);
    summaryJournalNode.classList.add('summary-journal-clone');
    var formSummaryJournalNode = wrapClone.querySelector('.container form.summary-journal-form');
    var summaryJournalSpans = summaryJournalNode.querySelectorAll('td.timestamp span.td-cell');

    for (var i = 0; i < summaryJournalSpans.length; ++i) {
        var span = summaryJournalSpans[i];
        var td = span.closest('td');

        if (td.classList.contains('not-set-income')) {
            continue;
        }

        if (typeof td.dataset.idleHours != 'undefined') {
            span.innerHTML = td.dataset.idleHours;
        }
    }

    formSummaryJournalNode.innerHTML = '';
    formSummaryJournalNode.appendChild(summaryJournalNode);

    body.appendChild(wrapClone);
    summaryJournalNode.parentNode.insertBefore(filterTypeClone, summaryJournalNode);
}

// updates total incomes
Builder.updateTotalIncomes = function()
{
    var summaryTotalCells = summaryJournal.querySelectorAll('.summary-total-cell');
    var summaryCountTotalCells = summaryJournal.querySelectorAll('.summary-count-total');

    for (var i = 0; i < summaryTotalCells.length; ++i) {
        var cell = summaryTotalCells[i];
        cell.innerHTML = preciseNumber(cell.dataset.idlesTotal);
    }

    for (var i = 0; i < summaryCountTotalCells.length; ++i) {
        var cell = summaryCountTotalCells[i];
        cell.innerHTML = preciseNumber(cell.dataset.idlesTotal / cell.dataset.countTotal);
    }

    var i = 0;
    do {
        var lastChild = summaryJournal.querySelector('table.table-mashines-count:nth-last-child(1)');

        if (lastChild != null) {
            lastChild.remove();
        }

        ++i;
    } while(i < 2);

    var preLastConclusion = document.querySelector('.summary-journal .table-summary-conclusion tr:nth-last-child(2)');
    var lastConclusion = document.querySelector('.summary-journal .table-summary-conclusion tr:nth-last-child(1)');

    if (preLastConclusion != null && lastConclusion != null) {
        summaryJournal.querySelector('.table-summary-conclusion tr:nth-last-child(2)').innerHTML = preLastConclusion.innerHTML;
        summaryJournal.querySelector('.table-summary-conclusion tr:nth-last-child(1)').innerHTML = lastConclusion.innerHTML;
    }
}

// updates column headers
Builder.updateColumnHeaders = function()
{
    var thIncomes = summaryJournal.querySelector('th.incomes');
    thIncomes.innerHTML = "<?= Yii::t('summaryJournal', 'Idle Hours') ?>";

    var thIncomesByDay = summaryJournal.querySelector('th.incomes-by-day');
    thIncomesByDay.innerHTML = "<?= Yii::t('summaryJournal', 'Idle Hours By Day') ?>";

    var thIncomesByMashine = summaryJournal.querySelector('th.incomes-by-mashine');

    if (thIncomesByMashine != null) {
        thIncomesByMashine.innerHTML = "<?= Yii::t('summaryJournal', 'Idle Hours By Mashine') ?>";
    }

    var thIncomesByCitizens = summaryJournal.querySelector('th.incomes-by-citizens');
    thIncomesByCitizens.innerHTML = "<?= Yii::t('summaryJournal', 'Idle Hours By Citizen') ?>";

    var thConsolidatedSummary = summaryJournal.querySelector('th.consolidated-summary');
    thConsolidatedSummary.innerHTML = "<?= Yii::t('summaryJournal', 'Idle Hours Consolidated') ?>";

    var thExpectation = summaryJournal.querySelector('th.expectation');
    thExpectation.innerHTML = "<?= Yii::t('summaryJournal', 'Idle Hours Expectation') ?>";

    var thExpectationByBalanceHolders = summaryJournal.querySelector('th.expectation-by-balance-holders');
    thExpectationByBalanceHolders.innerHTML = "<?= Yii::t('summaryJournal', 'Idle Hours Expectation By Balance Holders') ?>";

    var nodes = summaryJournal.querySelectorAll('input[name=cancel-income], span.cancel-income');

    Array.prototype.forEach.call(nodes, function(node) {
        node.parentNode.removeChild(node);
    });
}

// expand/wrap statistics
Builder.expandStatistics = function()
{
    var expandIncomes = document.querySelectorAll('.expand-incomes');

    Array.prototype.forEach.call(expandIncomes, function(expandIncome)
    {
        expandIncome.closest('div').onclick = function()
        {
            var expandIncomes = this.querySelector('.expand-incomes');
            var journal = document.querySelector(expandIncomes.dataset.selector);
            var glyphicon = this.querySelector('.glyphicon');

            if (journal.style.display != 'none') {
                journal.style.display = 'none';
                glyphicon.classList.add('glyphicon-plus');
                glyphicon.classList.remove('glyphicon-minus');

                if (expandIncomes.classList.contains('idle-incomes')) {
                    expandIncomes.innerHTML = "<?= $expandIdles ?>";
                } else {
                    expandIncomes.innerHTML = "<?= $expandIncomes ?>";
                }

            } else {
                journal.style.display = 'block';
                glyphicon.classList.add('glyphicon-minus');
                glyphicon.classList.remove('glyphicon-plus');

                if (expandIncomes.classList.contains('idle-incomes')) {
                    expandIncomes.innerHTML = "<?= $wrapIdles ?>";
                } else {
                    expandIncomes.innerHTML = "<?= $wrapIncomes ?>";
                }
            }
        }

        if (expandIncome.classList.contains('idle-incomes')) {
            expandIncome.closest('div').click();
        } else {
            expandIncome.closest('div').click();
            expandIncome.closest('div').click();
        }
    });
}

// cancel income per a certain day
Builder.updateIncomeCancel = function()
{
    var cancelIncomes = summaryJournal.querySelectorAll('input[name=cancel-income]');

    // cancellation income process function
    function cancelIncomeProcess(element)
    {
        var dataId = element.dataset.random;
        var form = summaryJournal.querySelector('form.c' + dataId);

        if (typeof form == 'undefined' || form == null) {
            var div = summaryJournal.querySelector('div.d' + dataId);
            form = document.createElement('form');
            form.method = 'post';
            form.appendChild(div);
            document.body.appendChild(form);
        }

        form.querySelector('button[type=submit]').click();
    }

    // set up handles for cancel incomes on 'change' event
    for (var i = 0; i < cancelIncomes.length; ++i) {
        var dataCancelled = cancelIncomes[i].dataset.cancelled;

        if (dataCancelled == 1) {
            cancelIncomes[i].checked = 'checked';
        }

        cancelIncomes[i].onchange = function()
        {
            cancelIncomeProcess(this);
        }
    }
}

// main journal
Builder.makeJournal = function()
{
    // main build function
    Builder.make();

    // update cancel income
    Builder.updateIncomeCancel();
}

//main journal clone
Builder.makeJournalClone = function()
{
    Builder.make();
    Builder.updateTotalIncomes();
    Builder.updateColumnHeaders();
    Builder.expandStatistics();
}

</script>