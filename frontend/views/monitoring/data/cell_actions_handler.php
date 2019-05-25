// process cell action click
var cellActions = monitoring.querySelectorAll('.cell-actions');
for (var i = 0; i < cellActions.length; ++i) {
    var cellAction = cellActions[i];
    cellAction.onclick = function() { cellActionClickProcessing(this); };
}

// cell action click process main function
function cellActionClickProcessing(cellAction)
{
    if (cellAction.classList.contains('active')) {
        cellAction.classList.remove("active");
        updateActionList(cellAction, 1);
    } else {
        cellAction.classList.add("active");
        updateActionList(cellAction, 0);
    }

    var cellActions = cellAction.closest('table').querySelectorAll('.cell-actions');

    for (var i = 0; i < cellActions.length; ++i) {

        if (cellActions[i].dataset.action != cellAction.dataset.action) {
            cellActions[i].classList.remove('active');
        }
    }
}

// makes ajax request for updating action list
function updateActionList(cellAction, isCancelled)
{
    var imei_id = cellAction.dataset.imei_id;
    var imei = cellAction.dataset.imei;
    var action = cellAction.dataset.actionId;

    var queryString = "imeiId=" + imei_id + "&imei=" + imei + "&action=" + action + "&isCancel=" + isCancelled;
    var ajaxActions = new XMLHttpRequest();
    ajaxActions.open("GET", "/monitoring/imei-action?" + queryString, true);
    ajaxActions.send();
}