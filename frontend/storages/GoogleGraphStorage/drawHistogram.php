<?= Yii::$app->view->render($storage->storagePath.'/auxiliary_functions', ['data' => $data]) ?>

<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    var dataArray = makeDataForHistogram();
    var options = makeOptionsForHistogram();

    //draws chart
    function drawChart() {
        var data = google.visualization.arrayToDataTable(dataArray);

        var formatter = new google.visualization.NumberFormat({
            negativeColor: 'red', negativeParens: true, pattern: '######.##'
        });

        for (var i = 1; i < dataArray[0].length; ++i) {
            formatter.format(data, i);
        }

        var chart = new google.visualization.ColumnChart(document.querySelector('<?= $selector ?>'));
        options.legend = { position: 'top'};
        chart.draw(data, options);
        graphBuilder.isGraphBusy = false;
    }
</script>