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