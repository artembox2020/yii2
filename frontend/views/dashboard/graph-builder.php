<script>
    var graphBuilder = {};
    
    graphBuilder.isGraphBusy = false; // global indicator whether graph is busy
    graphBuilder.isIndicatorForModem = 0;
    graphBuilder.initializationSign = true;

    // universal form submission function
    graphBuilder.submitAjaxForm = function(form, responseText) {
        var response = JSON.parse(responseText);
        var start = response['start'];
        var end = response['end'];
        var active = response['active'];
        var other = response['other'];

        form.querySelector('input[name=start]').value = start;
        form.querySelector('input[name=end]').value = end;
        form.querySelector('input[name=active]').value = active;
        form.querySelector('input[name=other]').value = other;

        if (response['actionBuilder']) {
            form.querySelector('input[name=actionBuilder]').value = response['actionBuilder'];
        }

        form.querySelector('button').click();
    };

    // play button click processing
    graphBuilder.playButtonClick = function(playButton) {
        var container = playButton.closest('.filter-type').querySelector('.container-block');

        if (playButton.classList.contains('rotate90')) {
            container.style.display = 'none';
            playButton.classList.remove('rotate90');
        } else {
            container.style.display = 'block';
            playButton.classList.add('rotate90');
        }
    }

    // play button group click processing
    graphBuilder.playButtonGroupClick = function(playButtons) {
        for (var i = 0; i < playButtons.length; ++i) {
            playButtons[i].onclick = function ()
            {
                graphBuilder.playButtonClick(this);
            }
        }
    }

    // filter wheel click processing
    graphBuilder.filterPromptClick = function(filterPrompt, random) {
        var graphContainer = document.querySelector(".graph-container.r" + random);
        var timestampBlock = graphContainer.querySelector('.timestamp-interval-block');

        if (graphBuilder.initializationSign == true) {
            setTimeout(function() { graphBuilder.initializationSign = false;}, 3000);

            return;
        }

        if (timestampBlock.style.display != 'none') {
            timestampBlock.style.display = 'none';
            graphContainer.classList.remove('active');
        } else {
            timestampBlock.style.display = 'block';
            graphContainer.classList.add('active');
        }
    }

    // filter wheel group click processing
    graphBuilder.filterPromptGroupClick = function(filterPrompts, random) {
        var graphContainer = document.querySelector(".graph-container.r" + random);
        var timestampBlock = graphContainer.querySelector('.timestamp-interval-block');
        for (var i = 0; i < filterPrompts.length; ++i) {
            filterPrompts[i].onclick = function ()
            {
                graphBuilder.filterPromptClick(this, random);
            }

            filterPrompts[i].click();

            if (timestampBlock.style.display != 'none') {
                filterPrompts[i].click();
            }
        }
    }

    // submit last days processing
    graphBuilder.submitLastDaysBtnProcess = function(button, random, selector, filterPrompt, additionalString)
    {
            graphBuilder.isGraphBusy = true;
            var active = button.closest(".graph-container").querySelector("select[name=dateOptions" + random + "]").value;
            var date = button.closest(".graph-container").querySelector("input[name=date]").value;
            var form = button.closest('.graph-container').querySelector('.form-dashboard-ajax');
            var ajax = new XMLHttpRequest();
            filterPrompt.click();
            document.querySelector(selector).innerHTML = "<img src='<?= Yii::$app->homeUrl . '/static/gif/loader.gif' ?>'/>";

            ajax.addEventListener("load", function() {
                graphBuilder.submitAjaxForm(form, this.responseText);
            });

            var queryString = "active=" + active + "&date=" + date;

            if (additionalString) {
                queryString += additionalString;
            }

            ajax.open("GET", "/dashboard/get-timestamps-by-drop-down?" + queryString, true);
            ajax.send();
    }

    // submit days between processing
    graphBuilder.submitDaysBetweenBtnProcess = function(button, random, selector, filterPrompt, additionalString)
    {
            var graphContainer = document.querySelector(".graph-container.r" + random);
            var fromDate = graphContainer.querySelector('.timestamp-interval-block input[name=from_date]');
            var toDate = graphContainer.querySelector('.timestamp-interval-block input[name=to_date]');
            var toTimestamp = (new Date(toDate.value)).getTime() / 1000;
            var fromTimestamp = (new Date(fromDate.value)).getTime() / 1000;

            var active = fromTimestamp + "*" + toTimestamp;
            var form = toDate.closest('.graph-container').querySelector('.form-dashboard-ajax');

            graphBuilder.isGraphBusy = true;
            var ajax = new XMLHttpRequest();
            filterPrompt.click();
            document.querySelector(selector).innerHTML = "<img src='<?= Yii::$app->homeUrl . '/static/gif/loader.gif' ?>'/>";
            ajax.addEventListener("load", function() {
                graphBuilder.submitAjaxForm(form, this.responseText);
            });

            var queryString = "active=" + active + "&dateStart=" +fromDate.value + "&dateEnd=" + toDate.value;

            if (additionalString) {
                queryString += additionalString;
            }

            ajax.open("GET", "/dashboard/get-timestamps-by-dates-between?" + queryString, true);
            ajax.send();
    }

    // submit button click processing
    graphBuilder.submitBtnProcess = function(button, random, selector, filterPrompt, additionalString)
    {
        button.onclick = function()
        {
            var graphContainer = document.querySelector(".graph-container.r" + random);
            var fromDate = graphContainer.querySelector('.timestamp-interval-block input[name=from_date]');
            var toDate = graphContainer.querySelector('.timestamp-interval-block input[name=to_date]');

            if (!fromDate.value) {
                graphBuilder.submitLastDaysBtnProcess(button, random, selector, filterPrompt, additionalString);
                return;
            }

            if (!toDate.value) {
                graphBuilder.submitLastDaysBtnProcess(button, random, selector, filterPrompt, additionalString);
                return;
            }

            var toTimestamp = (new Date(toDate.value)).getTime() / 1000;
            var fromTimestamp = (new Date(fromDate.value)).getTime() / 1000;

            if (fromTimestamp > toTimestamp) {
                graphBuilder.submitLastDaysBtnProcess(button, random, selector, filterPrompt, additionalString);
                return;
            }

            graphBuilder.submitDaysBetweenBtnProcess(button, random, selector, filterPrompt, additionalString);
        }
    }

    // submit button modem level click processing
    graphBuilder.submitBtnModemLevelProcess = function(button, random, selector, filterPrompt, checkBoxes)
    {
        button.onclick = function()
        {
            if (graphBuilder.isIndicatorForModem % 2 == 1) {
                graphBuilder.isIndicatorForModem = 0;

                return;
            }
            
            ++graphBuilder.isIndicatorForModem;

            var addressIds = '';
            for (var i = 0; i < checkBoxes.length; ++i) {

                if (checkBoxes[i].checked) {
                    addressIds += checkBoxes[i].value + ',';
                }
            }

            if (addressIds.length > 0) {
                addressIds = addressIds.substring(0, addressIds.length - 1);
            }

            var additionalString = "&other=" + addressIds;

            graphBuilder.submitBtnProcess(button, random, selector, filterPrompt, additionalString);
            button.click();
        }
    }
    
    // init graph function
    graphBuilder.initGraph = function(random)
    {
        document.addEventListener("DOMContentLoaded", function()
        {
            var graphContainer = document.querySelector(".graph-container.r" + random);
            var playButtons = graphContainer.querySelectorAll(".glyphicon.glyphicon-play");

            for (var i = 0; i < playButtons.length; ++i) {
                graphBuilder.playButtonClick(playButtons[i]);
                graphBuilder.playButtonClick(playButtons[i]);
            }

            var hInt = setInterval(function() {

                if (graphBuilder.isGraphBusy) {

                    return;
                }

                var graphContainer = document.querySelector(".graph-container.r" + random);
                var button = graphContainer.querySelector(".submit-container button");
                var graphContainers = document.querySelectorAll(".graph-container");
                var i= 0;

                for (i = 0; i < graphContainers.length; ++i) {
                    button.click();
                }
                button.click();

                clearInterval(hInt);                
            }, 1500);

            var filterPrompt = graphContainer.querySelector('.filter-prompt');
            filterPrompt.click();
        });
    }

    // get initialization packet data
    graphBuilder.getInitializationData = function(addressString, start, end)
    {
        var ajax = new XMLHttpRequest();
        var queryString = "addressString=" + addressString + "&start=" + start + "&end=" + end;
        var data = null;

        ajax.addEventListener("load", function() {
            data = this.responseText;
        });

        ajax.open("GET", "/dashboard/get-initialization-data?" + queryString, false);
        ajax.send();

        return data;
    }
</script>
