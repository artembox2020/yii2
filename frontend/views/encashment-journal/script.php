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
            prependSummaryRow(sum, closestTr, true);
            sum = 0;
            dateStamp = 0;
            --i;
        }
    }

    if (encashmentValues.length > 0) {
        var closestTr = encashmentValue.closest('tr');
        prependSummaryRow(sum, closestTr, false);
    }

    // prepend summary row to summarize encashment values
    function prependSummaryRow(sum, closestTr, isBefore)
    {
        var tr = closestTr.cloneNode(true);
        var tds = tr.querySelectorAll('td');

        for (var i = 0; i < tds.length; ++i) {
            var td = tds[i];

            if (td.querySelector('span.encashment-sum') == null) {

                td.classList.add('invisible');
                td.innerHTML = '';
            }
        }

        tr.querySelector('span.encashment-sum').innerHTML = sum;
        
        var emptyRow = tr.cloneNode(true);
        
        emptyRow.querySelector('td span.encashment-sum').innerHTML = '';
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
</script>