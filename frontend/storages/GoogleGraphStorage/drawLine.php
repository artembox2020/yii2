<?= Yii::$app->view->render($storage->storagePath.'/auxiliary_functions', ['data' => $data]) ?>

<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawLine);

    var dataArray = makeDataForHistogram();
    var options = {
        curveType: 'function',
        legend: { position: 'right' }
    };

    //draws line
    function drawLine() {
        var data = google.visualization.arrayToDataTable(dataArray);

        var formatter = new google.visualization.NumberFormat({
            negativeColor: 'red', negativeParens: true, pattern: '######.##'
        });

        for (var i = 1; i < dataArray[0].length; ++i) {
            formatter.format(data, i);
        }

        var chart = new google.visualization.LineChart(document.querySelector('<?= $selector ?>'));
        chart.draw(data, options);
        graphBuilder.isGraphBusy = false;
    }
</script>