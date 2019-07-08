<script>
    (function() 
    {
        var monitoring = document.querySelector('.monitoring-new');
        var monitoringForm = monitoring.querySelector('.monitoring-filter-form');
        var monitoringFormAddress = monitoringForm.querySelector('input[name=address]');
        var monitoringFormSortOrder = monitoringForm.querySelector('select[name=sortOrder]');
        var monitoringSerialNumbers = monitoring.querySelectorAll('input.address-serial-number');
        
        /*function getActiveTab()
        {
            var genTab = monitoring.querySelector('#tab-gen');
            var finTab = monitoring.querySelector('#tab-fin');
            var techTab = monitoring.querySelector('#tab-tech');
            
            if (typeof genTab == 'undefined' || genTab == null) {
                if (typeof finTab != 'undefined' && finTab != null) {

                    return monitoring.querySelector("#tab-fin");    
                }

                return monitoring.querySelector("#tab-tech");
            }
            
            if (genTab.style && genTab.style.display != 'none') {
                
                return monitoring.querySelector("#tab-gen");
            }
            
            if (finTab.style && finTab.style.display != 'none') {
                
                return monitoring.querySelector("#tab-fin");
            }

            return monitoring.querySelector("#tab-tech");
        }*/

        // applies filter by value 
        function applyFilterByValue(value, inputSelector)
        {
            if (typeof value != 'undefined' && value != null && value.trim() != '') {
                value = value.toLowerCase();
                var inputSearch = monitoring.querySelectorAll('input'+ inputSelector+'[value*="' + value + '"]');
                var upperRows = monitoring.querySelectorAll('.upper-row');

                for (var i = 0; i < upperRows.length; ++i) {
                    if (upperRows[i].querySelectorAll('input'+ inputSelector+'[value*="' + value + '"]').length > 0) {
                        var displayProp = 'table-row';
                    } else {
                        var displayProp = 'none';
                    }
                    var nextSibling = upperRows[i].nextSibling;

                    while(nextSibling) {
                        if (!nextSibling.classList || !nextSibling.classList.contains('upper-row')) {
                            nextSibling.style = "display:" + displayProp;
                        } else {
                            break;
                        }

                        nextSibling = nextSibling.nextSibling;
                    }

                    upperRows[i].style.display = displayProp;
                }
            } else {
                var tables = monitoring.querySelectorAll('.monitoring-grid-view table');
                for (var j = 0; j < tables.length; ++j) {
                    var table = tables[j];
                    var rows  = table.querySelectorAll("tr");

                    for (var i = 0; i < table.querySelectorAll("tr").length; ++i) {
                        rows[i].style = "display: table-row";
                    }
                }
            }
        }

        // monitoring form address change function
        monitoringFormAddress.onchange = function()
        {
            applyFilterByValue(this.value, '.search-address-value');
        }

        // apply monitoring form address change function on <ENTER>
        monitoringFormAddress.onkeypress = function(e)
        {
            if (e.keyCode == 13 ) {
                console.log('enter press');
                e.preventDefault();
                this.onchange();

                return true;
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

        // initialize script
        (function init() {
            var genTab = monitoring.querySelector("#tab-gen");
            var finTab = monitoring.querySelector("#tab-fin");
            var techTab = monitoring.querySelector("#tab-tech");

            if (typeof genTab == "undefined" || genTab == null) {
                if (typeof finTab != "undefined" && finTab != null) {
                    finTab.querySelector("tr th.shapter-technical").remove();
                    var techHeaders = finTab.querySelectorAll('.tech');
                    for (var i = 0; i < techHeaders.length; ++i) {
                        techHeaders[i].remove();
                    }
                    finTab.style = "display: block;";
                } else  if (typeof techTab != "undefined" && techTab != null) {
                    techTab.querySelector("tr th.shapter-financial").remove();
                    var finHeaders = techTab.querySelectorAll('.fin');
                    for (var i = 0; i < finHeaders.length; ++i) {
                        finHeaders[i].remove();
                    }
                    techTab.style = "display: block;";
                }
            } else {
                monitoringForm.querySelector(".tab-navs").style = "display: inline-block";
                finTab.querySelector("tr th.shapter-technical").remove();
                techTab.querySelector("tr th.shapter-financial").remove();

                var techHeaders = finTab.querySelectorAll('.tech');
                for (var i = 0; i < techHeaders.length; ++i) {
                    techHeaders[i].remove();
                }

                var finHeaders = techTab.querySelectorAll('.fin');
                for (var i = 0; i < finHeaders.length; ++i) {
                    finHeaders[i].remove();
                }
            }
        })();

        // update page script
        <?= Yii::$app->view->render('/monitoring/data/ajax_update_handler', ['timestamp' => $timestamp, 'timeOut' => $timeOut]) ?>
    }());
</script>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" />