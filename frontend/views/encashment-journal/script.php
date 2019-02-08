<script>

    // span sign "+", to show/hide banknote nominals grid 
    banknoteNominals = document.querySelectorAll("span.banknote_nominals");

    // banknote nominals click processing
    for (var i = 0; i < banknoteNominals.length; ++i) {
        var banknoteNominal = banknoteNominals[i];
        banknoteNominal.onclick = function() { banknoteFaces(this); };
    }

    // banknote nominals grid
    var banknoteNominalsGrid = document.querySelectorAll('.banknote-nominals-grid');

    // banknote nominals grid design
    for (var i = 0; i < banknoteNominalsGrid.length; ++i) {
        var grid = banknoteNominalsGrid[i];
        grid.querySelector("table thead").remove();
        var nominalsGrid = grid.querySelector('.nominals-grid table');
        var totalGrid = grid.querySelector('.total-grid table');
        var addressGrid = grid.querySelector('.address-grid table');

        nominalsGrid.closest('.banknote-nominals').classList.remove('hide');
        var nominalsHeight = parseInt(nominalsGrid.offsetHeight);
        nominalsGrid.closest('.banknote-nominals').classList.add('hide');

        totalGrid.style.height = nominalsHeight + 'px';
        addressGrid.style.height = nominalsHeight + 'px';
    }

    // encashment summary
    var encashmentValues = document.querySelectorAll('span.encashment-sum');
    var sum = 0;
    var dateStamp = 0;

    for (var i = 0; i < encashmentValues.length; ++i) {
        var encashmentValue = encashmentValues[i];
        var crDateStamp = encashmentValue.dataset.timestamp;
        if (crDateStamp == dateStamp || dateStamp ==0) {
            sum += parseFloat(encashmentValue.innerHTML);
            dateStamp = crDateStamp;
        } else {
            var closestTr = encashmentValue.closest('tr');
            prependSummaryRow(sum, closestTr, dateStamp, true);
            sum = 0;
            dateStamp = 0;
            --i;
        }
    }

    if (encashmentValues.length > 0) {
        var closestTr = encashmentValue.closest('tr');
        prependSummaryRow(sum, closestTr, dateStamp, false);
    }

    // prepend summary row to summarize encashment values
    function prependSummaryRow(sum, closestTr, dateStamp, isBefore)
    {
        var tr = closestTr.cloneNode(true);
        var tds = tr.querySelectorAll('td');

        for (var i = 0; i < tds.length; ++i) {
            var td = tds[i];

            if (td.querySelector('span.encashment-sum') == null) {

                if (i != 0) {
                    td.classList.add('invisible');
                    td.innerHTML = '';
                } else {
                    td.innerHTML = "<?= Yii::t('frontend', 'Print') ?>";
                    td.onclick = function() { printEncashmentSummary(td); };
                }
            }
        }

        tr.querySelector('span.encashment-sum').innerHTML = sum;
        tr.querySelector('span.encashment-sum').dataset.timestamp = dateStamp;
        
        var emptyRow = tr.cloneNode(true);
        
        emptyRow.querySelector('td span.encashment-sum').innerHTML = '';
        emptyRow.querySelector('td span.encashment-sum').dataset.timestamp = '0';
        emptyRow.querySelector('td span.encashment-sum').closest('tr').classList.add('invisible');

        if (isBefore) {
            closestTr.parentNode.insertBefore(tr, closestTr);
            closestTr.parentNode.insertBefore(emptyRow, closestTr);
        } else {
            closestTr.parentNode.insertBefore(emptyRow, closestTr.nextSibling);
            closestTr.parentNode.insertBefore(tr, closestTr.nextSibling);
        }
    }

    // banknote faces grid on click function
    function banknoteFaces(element)
    {
        if (element.classList.contains('glyphicon-plus')) {

            for (var i = 0; i < banknoteNominals.length; ++i) {
                var banknoteNominal = banknoteNominals[i];
                if (banknoteNominal.classList.contains('glyphicon-minus')) {
                    banknoteNominal.click();
                }
            }

            element.classList.add('glyphicon-minus');
            element.classList.remove('glyphicon-plus');
            element.parentNode.querySelector("div.banknote-nominals").classList.remove('hide');
        } else {
            element.classList.add('glyphicon-plus');
            element.classList.remove('glyphicon-minus');
            element.parentNode.querySelector("div.banknote-nominals").classList.add('hide');
        }
    }

    // print encashment summary
    function printEncashmentSummary(td) {
        var href = window.location.href;
        var label = td.closest('tr').querySelector("td span.encashment-sum").dataset.timestamp;

        var encashmentBlock = td.closest('div.encashment-index').cloneNode(true);

        // remove redundant nodes 
        for (var i = 0; i < encashmentBlock.querySelector('.grid-view').childNodes.length; ++i) {
            var node = encashmentBlock.querySelector('.grid-view').childNodes[i];

            if (typeof node.classList == 'undefined' || !node.classList.contains('table')) {

                node.remove();
            }
        }

        encashmentBlock.querySelector('.summary').remove();
        encashmentBlock.querySelector('.pagination').remove();

        var table = encashmentBlock.querySelector('table');

        var trs = table.querySelectorAll('tbody tr');
        var banknotesSummary = getBanknotesSummary(table, label);

        // remove unnecessary table items
        for (var i = 0; i < trs.length; ++i) {
            var tr = trs[i];
            var currentLabel = tr.querySelector("td span.encashment-sum");
            if (currentLabel == null || label != currentLabel.dataset.timestamp) {
                tr.remove();
            }
        }

        table.querySelector('thead tr.filters').remove();
        var cell = table.querySelector('tbody tr:nth-last-child(1) td.banknote-nominals-cell').nextSibling;
        cell.classList.remove('invisible');
        cell.innerHTML = "<?= Yii::t('frontend', 'Total') ?>";

        var summaryBlock = document.createElement('div');
        summaryBlock.classList.add('encashment-general-summary');
        summaryBlock.innerHTML = '<?= $nominalsView ?>';
        summaryBlock.querySelector('.summary').remove();
        summaryBlock = implementBanknotesSummary(banknotesSummary, summaryBlock);
        table.parentNode.insertBefore(summaryBlock, table.nextSibling);

        var banknoteNominals = table.querySelectorAll('td.banknote-nominals-cell');
        for (var i = 0; i < banknoteNominals.length; ++i) {

            banknoteNominals[i].innerHTML = '';
        }

        // fill in the form with necessary data and submit it
        var encashmentSummaryTitle = "<?= Yii::t('logs', 'Encashment Summary') ?> (" + label + ")";
        var encashmentSummaryCaption = '<h2 align=center>' + encashmentSummaryTitle + '</h2>';
        var form = document.querySelector("form.encashment-print-form");
        form.querySelector('input[name=html]').value = '<div class="encashment-index">' + encashmentBlock.innerHTML + '</div>';
        form.querySelector('input[name=filename]').value = 'encashment-summary-' + label;
        form.querySelector('input[name=caption]').value = encashmentSummaryCaption;
        form.querySelector('input[name=title]').value = encashmentSummaryTitle;
        form.submit();
    }

    // calculates and returns banknotes summary info as array
    function getBanknotesSummary(table, label)
    {
        var nominalsGrid = table.querySelectorAll('tr td.banknote-nominals-cell .banknote-nominals-grid table .nominals-grid');

        var banknotesInfo = [];
        var banknotesNominals = [];
        for (var i = 0; i < nominalsGrid.length; ++i) {
            var grid = nominalsGrid[i];
            var tableTrs =  grid.querySelectorAll('tr');
            for (var j = 0; j < tableTrs.length; ++j) {
                var tr = tableTrs[j];

                if (tr.querySelectorAll('td').length < 3) {
                    continue;
                }

                var trLabel = tr.closest('.banknote-nominals-cell').closest('tr').querySelector("span.encashment-sum");

                if (trLabel == null || trLabel.dataset.timestamp != label) {
                    continue;
                }

                var nominal = tr.querySelector('td').innerHTML;
                var nominalNumber = parseInt(tr.querySelector('td:nth-child(2)').innerHTML);
                var nominalSum = parseInt(tr.querySelector('td:nth-child(3)').innerHTML);

                if (typeof banknotesInfo[nominal] == 'undefined' || banknotesInfo[nominal] == null) {
                    banknotesInfo[nominal] = {};
                    banknotesInfo[nominal].q = nominalNumber;
                    banknotesInfo[nominal].sum = nominalSum; 
                } else {
                    banknotesInfo[nominal].q += nominalNumber;
                    banknotesInfo[nominal].sum += nominalSum; 
                }

                if (!banknotesNominals.includes(nominal)) {
                    banknotesNominals.push(nominal);
                }
            }
        }

        banknotesInfo['nominals'] = banknotesNominals;

        return banknotesInfo;
    }

    // appends at the end of the pdf summary general banknotes info
    function implementBanknotesSummary(banknotesSummary, summaryBlock)
    {
        var table = summaryBlock.querySelector('table');
        var tableTrs = table.querySelectorAll('tbody tr, thead tr');
        var tbody = table.querySelector('tbody');

        for (var i = 0; i < tableTrs.length; ++i) {
            var tr = tableTrs[i];
            tr.remove();
        }

        var headTr = table.querySelector('thead');
        var tr = document.createElement('tr');
        
        var headNominalCell = document.createElement('td');
        headNominalCell.innerHTML = "<?= Yii::t('logs', 'Nominal') ?>";
        
        var headNominalQCell = document.createElement('td');
        headNominalQCell.innerHTML = "<?= Yii::t('logs', 'General Number Of Nominals') ?>";

        var headNominalSumCell = document.createElement('td');
        headNominalSumCell.innerHTML = "<?= Yii::t('logs', 'General Sum Of Banknotes') ?>";

        tr.append(headNominalCell);
        tr.append(headNominalQCell);
        tr.append(headNominalSumCell);
        headTr.append(tr);

        for (var i = 0; i < banknotesSummary['nominals'].length; ++i) {
            var nominal = banknotesSummary['nominals'][i];
            var tr = document.createElement('tr');

            var nominalCell = document.createElement('td');
            nominalCell.innerHTML = nominal;

            var nominalQCell = document.createElement('td');
            nominalQCell.innerHTML = banknotesSummary[nominal].q;

            var nominalSumCell = document.createElement('td');
            nominalSumCell.innerHTML = banknotesSummary[nominal].sum;

            tr.append(nominalCell);
            tr.append(nominalQCell);
            tr.append(nominalSumCell);

            tbody.append(tr);
        }

        var generalSummary = document.createElement('h2');
        generalSummary.align = 'center';
        generalSummary.innerHTML = "<?= Yii::t('logs', 'General Summary') ?>";
        summaryBlock.prepend(generalSummary);

        return summaryBlock;
    }
</script>