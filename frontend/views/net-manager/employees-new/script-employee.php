<script>
    var netManager = document.querySelector('.net-manager-new');
    var table = netManager.querySelector('.user-info table');
    var searchInput = netManager.querySelector('#createuser-search');
    var numberShowed = netManager.querySelector('.createuser__search .number-showed');
    var numberAll = netManager.querySelector('.createuser__search .number-all');
    var buttonMore = netManager.querySelector('span.showmore');
    var buttonLess = netManager.querySelector('span.showless');
    var direction = 'up';

    // search by search input value  
    searchInput.onchange = function()
    {
        var value = this.value.toUpperCase();

        var trs = table.querySelectorAll('tr.tr');
        var count = 0;
        var pageSize = 0;

        for (var i = 0; i < trs.length; ++i) {
            var tr  = trs[i];
            var name = tr.querySelector('td.name').innerHTML.toUpperCase();
            var position = tr.querySelector('td.position').innerHTML.toUpperCase();
            if (name.includes(value) || position.includes(value)) {

                if (tr.classList && tr.classList.contains('hidden-row')) {
                    tr.classList.remove('hidden-row');
                }

                if (!tr.classList || (!tr.classList.contains('hidden') && !tr.classList.contains('hidden-row'))) {
                    ++pageSize;
                }

                ++count;
            } else {
                tr.classList.add('hidden-row');
            }
        }

        numberAll.innerHTML = count;
        netManager.querySelector('.page-size').value = pageSize;
        showlessClickProcess(buttonLess);
        if (parseInt(netManager.querySelector('.page-size').value) == 0) {
            showmoreClickProcess(buttonMore);
        }

        makeButtonsVisibility(buttonLess, buttonMore, count);
        updateStatusString(netManager.querySelector('.page-size').value, count);
    }

    // redirect to search on press <ENTER>
    searchInput.onclick = function(e)
    {
        if (e.keyCode == 13) {
            this.change();
        }
    }

    // show more button click
    buttonMore.onclick = function()
    {
        showmoreClickProcess(this, '.user-info table tbody tr.tr:not(.hidden-row)');
    }

    // show less button click
    buttonLess.onclick = function()
    {
        showlessClickProcess(this, '.user-info table tbody tr.tr:not(.hidden-row)');
    }

    <?=Yii::$app->view->render('/net-manager/main-new/baseScript') ?>

    // updates status string
    function updateStatusString(pageSize, count)
    {
        if (count != null) {
            numberAll.innerHTML = count;
        }

        numberShowed.innerHTML = pageSize;
    }

</script>