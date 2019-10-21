<?= Yii::$app->view->render(
        $storage->storagePath.'/auxiliary_functions',
        ['data' => $data, 'start' => $start, 'end' => $end]
    )
?>

<script>
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawLine);

    var dataArray =  makeDataForLine(min - 6);
    var maxMin = getMaxMinValue(-128, 0);
    var min = maxMin.min - 1;
    var max = maxMin.max + 20;

    var options = {
        curveType: 'function',
        legend: { position: 'top' },
        hAxis: {
            textStyle: {
                fontSize: 8
            }
        },
        vAxis: {
            minValue: min,
            maxValue: max,
            viewWindow: { min: min, max: max},
            viewWindowMode: 'pretty',
            baseline: -128,
            baselineColor: "black"
        },
        pointSize: 0.2,
        dataOpacity: 0.3
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
        var lastActive = dataArray[0].length;
        google.visualization.events.addListener(chart, 'select', function () {
            var sel = chart.getSelection();
            
            if (sel.length > 0 && sel[0].row === null) {
                var active = sel[0].column;

                if (lastActive < sel[0].column) {
                    --active;
                }

                var dataArray = makeDataForHistogramByActive(active, min - 6);
                lastActive = sel[0].column;
                var data = google.visualization.arrayToDataTable(dataArray);
                chart.draw(data, options);
            }
        });

        chart.draw(data, options);
        graphBuilder.isGraphBusy = false;
    }
</script>