// refresh with new data without page reload
var Comet = { "ajax" : new XMLHttpRequest() };

// init ajax request
Comet.init = function ()
{
    Comet.ajax = new XMLHttpRequest();
    Comet.ajax.addEventListener("load", this.transferComplete);
    Comet.ajax.addEventListener("error", this.preInit);
    var deviceIds = monitoring.querySelectorAll('.device-id');
    var deviceIdsString = '';
    for (var i = 0; i < deviceIds.length; ++i) {
        deviceIdsString += deviceIds[i].value + ',';
    }

    if (deviceIdsString != '') {
        deviceIdsString = deviceIdsString.substring(0, deviceIdsString.length -1);
    }

    var queryString = "deviceIds=" + deviceIdsString + "&timestamp=<?= $timestamp ?>";
    Comet.ajax.open("GET", "/monitoring/check-monitoring-wm-update?" + queryString, true);
    Comet.ajax.send();
}

// call init within a timer 
Comet.preInit = function()
{
    setTimeout(function() { Comet.init(); }, <?= (int)$timeOut * 1000 ?>);
}

// call on complete request 
Comet.transferComplete = function()
{
    var response = JSON.parse(Comet.ajax.responseText);

    if (parseInt(response['status']) > 0) {
        var form = monitoring.querySelector('.monitoring-pjax-form');
        form.querySelector('button[type=submit]').click();
    } else {
        Comet.preInit();
    }
}

// call pre-init
Comet.preInit();