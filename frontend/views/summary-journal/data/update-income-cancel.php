
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