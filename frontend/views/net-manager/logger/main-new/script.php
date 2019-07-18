<script>
    var netManager = document.querySelector('.net-manager-new');
    var buttonMore = netManager.querySelector('div.showmore');
    var buttonLess = netManager.querySelector('div.showless');
    var dateSort = netManager.querySelector('.dropdown.date, .dropup.date');
    var numberSort = netManager.querySelector('.dropdown.number, .dropup.number');
    var min = -999999999999;
    var max= 999999999999;

    // show more button click
    buttonMore.onclick = function()
    {
        showmoreClickProcess(this);
    }

    // show less button click
    buttonLess.onclick = function()
    {
        showlessClickProcess(this);
    }

    // date sort process click
    dateSort.onclick = function()
    {
        sortFunc(this, ".hidden.date");
    }

    // number sort process click
    numberSort.onclick = function()
    {
        sortFunc(this, ".hidden.number");
    }

    // show more button click
    function showmoreClickProcess(button)
    {
        var trs = netManager.querySelectorAll('table tbody tr');
        var showNumber = netManager.querySelector('.page-size-initial').value;
        var pageInitSize = parseInt(showNumber);
        var i = 0;

        for (; i < trs.length; ++i) {
            if (trs[i].classList && trs[i].classList.contains('hidden')) {
                trs[i].classList.remove('hidden');
                --showNumber;
            }

            if (showNumber <= 0) {
                break;
            }
        }
        
        if (i == trs.length) {
            button.classList.add('hidden');
            buttonLess.classList.remove('hidden');
        }
        
        var pageSize = netManager.querySelector('.page-size');
        pageSize.value = parseInt(pageSize.value) + pageInitSize;
    }

    // show less button click
    function showlessClickProcess(button)
    {
        var trs = netManager.querySelectorAll('table tbody tr');
        var showNumber = netManager.querySelector('.page-size-initial').value;
        var pageInitSize = parseInt(showNumber);
        var i = trs.length-1;

        for (; i >= pageInitSize; --i) {
            if (!trs[i].classList || !trs[i].classList.contains('hidden')) {
                trs[i].classList.add('hidden');
                --showNumber;
            }

            if (showNumber <= 0) {
                break;
            }
        }

        if (i <= pageInitSize) {
            button.classList.add('hidden');
            buttonMore.classList.remove('hidden');
        }

        var pageSize = netManager.querySelector('.page-size');
        pageSize.value = parseInt(pageSize.value) - pageInitSize;
    }

    // sort function
    function sortFunc(sortElem, selector)
    {
        var sortDir = 'up';
        if (sortElem.classList.contains('dropdown')) {
            sortElem.classList.remove('dropdown');
            sortElem.classList.add('dropup');
            sortDir = 'up'; 
        } else {
            sortElem.classList.remove('dropup');
            sortElem.classList.add('dropdown');
            sortDir = 'down'; 
        }

        var inputs = netManager.querySelectorAll(selector);

        for (var i = inputs.length-1; i > 0; --i) {
            var initial = inputs[0];

            for (var j = 1; j <= i; ++j) {
                if (sortDir == 'up') {
                    if (!initial.value  || initial.value == max || initial.value == min) {
                        initial.value = max;
                    }
                    if (!inputs[j].value  || inputs[j].value == max || inputs[j].value == min) {
                        inputs[j].value = max;
                    }
                    var condition = parseInt(initial.value) < parseInt(inputs[j].value);
                } else {
                    if (!initial.value || initial.value == min || initial.value == max) {
                        initial.value = min;
                    }
                    if (!inputs[j].value || inputs[j].value == min || inputs[j].value == max) {
                        inputs[j].value = min;
                    }
                    var condition = parseInt(initial.value) > parseInt(inputs[j].value);
                }

                if (condition) {
                    initial = inputs[j];
                }
            }

            inputs[i].closest('tr').after(initial.closest('tr'));
            var inputs = netManager.querySelectorAll(selector);
        }
        updateTable();
    }

    // shows/hides table rows after sorting
    function updateTable()
    {
        var pageSize = netManager.querySelector('.page-size').value;
        var trs = netManager.querySelectorAll('table tbody tr');

        for (var i = 0; i < trs.length; ++i) {
            if (i < pageSize) {
                trs[i].classList.remove('hidden');
            } else {
                trs[i].classList.add('hidden');
            }
        }
    }
</script>