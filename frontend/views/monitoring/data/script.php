<script>
    (function() 
    {
        var monitoring = document.querySelector('.monitoring');
        var monitoringShapter = monitoring.querySelector('.monitoring-shapter');
        var monitoringDropList = monitoringShapter.querySelector("*[name=monitoring_shapter]");

        // displays by query selector
        function displayByQuerySelector(selector) {
            hideByQuerySelector('.remote-connection, .devices, .terminal');
            var  elementsBySelector = monitoring.querySelectorAll(selector);
            for (var i = 0; i < elementsBySelector.length; ++i) {
                elementsBySelector[i].style.display = 'table-cell';
            }
        }

        // hides by query selector
        function hideByQuerySelector(selector) {
            var  elementsBySelector = monitoring.querySelectorAll(selector);
            for (var i = 0; i < elementsBySelector.length; ++i) {
                elementsBySelector[i].style.display = 'none';
            }
        }

        // displays/hides depending on device width
        if (screen.width >= <?= $largeDeviceWidth ?>) {
            monitoringShapter.style.display = 'none';
            displayByQuerySelector('.remote-connection, .devices, .terminal');
        } else {
            monitoringShapter.style.display = 'block';
            monitoringDropList.onchange = function()
            {
                displayByQuerySelector('.'+this.value);
            };
            monitoringDropList.onchange();
        }
    }());
</script>