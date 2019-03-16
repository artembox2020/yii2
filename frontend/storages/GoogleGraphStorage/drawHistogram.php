<?= Yii::$app->view->render($storage->storagePath.'/auxiliary_functions', ['data' => $data]) ?>

<script>
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    var dataArray = makeDataForHistogram();

    console.log(dataArray);

    function drawChart() {
        /*var data = google.visualization.arrayToDataTable([
          ['', 'All WM', 'At Work', 'Broken'],
          ['12.03.2019', 14, 8, 3],
          ['13.03.2019', 15, 9, 3],
          ['14.03.2019', 15, 9, 3]
        ]);*/
        
        var data = google.visualization.arrayToDataTable(dataArray);

        var options = {
          chart: {
            title: 'WM Mashine statistics',
            subtitle: 'Over March, 12.03.2019 - 14.03.2019',
          },
          animation: {
              easing: 'in',
              duration: '3000',
              startup: true
          },
          backgroundColor: {
              stroke: '#666'
          }
        };

        var chart = new google.charts.Bar(document.querySelector('<?= $selector ?>'));

        chart.draw(data, google.charts.Bar.convertOptions(options));
    }

    
</script>