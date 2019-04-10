<?php
error_reporting( E_ERROR );

use yii\jui\DatePicker;
use frontend\models\Devices;
use frontend\models\Zlog;

$dateToday = time();
$kk = 0;
$ksum = 0;
$iday = 0;

$xx = [];
$yy = [];

$dev = new Devices();
$devices = $dev->all_dev();

$zlog = new Zlog();

$dateCurrent = $dateFrom;
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/2.0.0/jquery.min.js"></script>
<script src="/frontend/web/static/js/echarts-2.2.7/build/dist/echarts.js"></script>
<div class="row">
    <div class="col-md-8">
        Дата начала:
        <?php echo DatePicker::widget([
			'name' => 'date_start',
			'model' => $model,
			'attribute' => 'date_start',
				'language' => 'ru',
				'dateFormat' => 'yyyy-MM-dd',
			]);?>

        Конечная дата:
        <?php 
		echo DatePicker::widget([
			'name' => 'date_end',
			'model' => $model,
			'attribute' => 'date_end',
				'language' => 'ru',
				'dateFormat' => 'yyyy-MM-dd',
			]);?>
        
        <div class="btn btn-sm btn-info" id="per">Перерасчет</div>
    </div>

</div>
<hr>


<style>
    tr:hover{
        background-color: #fffbc1;
    }
    td {
        border: 1px dotted #dcd;
        padding: 2px;
        background-color: #ffffff;
        text-align: center;
		font-size: 10px;
    }
    td:hover {
        background-color: #fffbc1;
    }
    thead tr th {
		
        text-align: center;
    }
    #chart {
        width: 400px;
        height: 200px;
        background-color: #ffffff;
    }
</style>
<div style="float:right;">
<div>Sum - Сумма*</div>
<div>S - Среднее*</div>
<div>W - Ожидания*</div>
<h4>Месяц: <?=date('m', $dateCurrent)?></h4>
</div>
<table>
    <tr>
        <td>Автомат</td>
        <?php
        while ($dateCurrent <= $dateTo) {
            ?>
			
            <td style="background-color: papayawhip"><i><?=date('d', $dateCurrent)?></i></td>
            <?php
            $dateCurrent+= 86400;
        }
        ?>
        <td>Sum</td>
        <td>S</td>
        <td>W</td>
    </tr>
    
    <?php

    foreach ($devices as $device) {
        $dateCurrent = $dateFrom;
        $kk = 0;
        $ksum = 0;
        $iday = 0;
        ?>
        <tr>
            <td><?=$device['name']?></td>
            <?php
             while ($dateCurrent <= $dateTo) {
                $ss = $zlog->get_sum($device['id_dev'], date('Y-m-d H:i:s', $dateCurrent), date('Y-m-d H:i:s', ($dateCurrent + 86399)))->esum;
                
                if ($dateCurrent <= $dateToday) {
                    $ksum+= $ss;
                    $kk++;
                }
                $iday++;
                if ($ss > 0) {
                    $sum = number_format($ss, 2, ',', ' ');
                } else {
                    $sum = '';
                }
                ?>
                <td><?=$sum?></td>
                <?php
                $dateCurrent+= 86400;
            }
            ?>
            <td style="background-color: #e6db74"><b><?=number_format($zlog->get_sum($device['id_dev'], date('Y-m-d H:i:s', $dateFrom), date('Y-m-d H:i:s', ($dateTo + 86399)))->esum, 2, ',', ' ')?></b></td>
            <?php
            $average = 0;
            $ozid = 0;
            if ($kk != 0) {
                $average = number_format(($ksum / $kk), 2, ',', ' ');
                $ozid = number_format((($ksum / $kk) * $iday), 2, ',', ' ');
            }
            ?>
            <td><b><?=$average?></b></td>
            <td><b><?=$ozid?></b></td>
        </tr>
        <?php
    }
    ?>


    <tr>
        <td>Итог: </td>
        <?php
        $dateCurrent = $dateFrom;
        while ($dateCurrent <= $dateTo) {
            $ss = $zlog->get_sum_a(date('Y-m-d H:i:s', $dateCurrent), date('Y-m-d H:i:s', ($dateCurrent + 86399)))->esum;
            if ($ss > 0) {
                array_push($xx, date('d-m', $dateCurrent));
                array_push($yy, $ss);
            } else {
                array_push($xx, date('d-m', $dateCurrent));
                array_push($yy, 0);
            }
            ?>
            <td style="background-color: #e6db74"><b><i><?=number_format($ss, 2, ',', ' ')?></i></b></td>
            <?php
            $dateCurrent+= 86400;
        }
        ?>
        <td></td>
        <td></td>
        <td></td>
    </tr>
</table>
<br>
<div class="panel panel-body">
    <div class="row" id="idrow">
        <div class="col-md-12">
            <div id="chart"></div>
        </div>
    </div>
</div>

<?php
$x = '';
$y = '';
foreach ($xx as $element) {
    $x.= "'" . $element . "'" . ',';
}
foreach ($yy as $element) {
    $y.= $element . ',';
}
?>

<script>
    $('#per').off().on('click', function(){
        var dateFrom = $('#date_start').val();
        var dateTo = $('#date_end').val();
        location.href = '/frontend/site/zurnal?dateFrom=' + dateFrom + '&dateTo=' + dateTo;
    });

    window.onload = function() {
        
        var mwidth = $('#idrow').width() - 50;
        var mheight = mwidth / 3;
        if (mheight > 400) {
            mheight = 400;
        }
        $('#chart').css({
            'width': mwidth,
            'height': mheight,
        });
 
        
        require.config({
            paths: {
                echarts: '/frontend/web/static/js/echarts-2.2.7/build/dist'
            }
        });

        // use
        require(
            [
                'echarts',
                'echarts/chart/limitless',
                'echarts/chart/bar',
                'echarts/chart/line'
            ],
            function (ec, limitless) {
                // Initialize after dom ready
                var myChart = ec.init(document.getElementById('chart'), limitless);

                var option = {
                    grid: {
                        x: 40,
                        x2: 20,
                        y: 35,
                        y2: 25
                    },

                    tooltip: {
                        trigger: 'axis'
                    },

                    legend: {
                        data: ['Sales']
                    },
                    toolbox: {
                        show: true,
                        feature: {
                            mark: {
                                show: true,
                                title: {
                                    mark: 'Markline switch',
                                    markUndo: 'Undo markline',
                                    markClear: 'Clear markline'
                                }
                            },
                            dataZoom: {
                                show: true,
                                title: {
                                    dataZoom: 'Data zoom',
                                    dataZoomReset: 'Reset zoom'
                                }
                            },
                            dataView: {
                                show: true,
                                readOnly: false,
                                title: 'View data',
                                lang: ['View chart data', 'Close', 'Update']
                            },
                            magicType: {
                                show: true,
                                title: {
                                    line: 'Switch to line chart',
                                    bar: 'Switch to bar chart',
                                },
                                type: ['line', 'bar']
                            },
                            restore: {
                                show: true,
                                title: 'Restore'
                            },
                            saveAsImage: {
                                show: true,
                                title: 'Same as image',
                                lang: ['Save']
                            }
                        }
                    },

                    calculable: true,

                    xAxis: [{
                        type: 'category',
                        boundaryGap: false,
                        data: [
                            <?=$x?>
                        ]
                    }],

                    yAxis: [{
                        type: 'value'
                    }],

                    series: [
                        {
                            name: 'Sales',
                            type: 'line',
                            stack: 'Total',
                            data: [<?=$y?>]
                        }
                    ]
                };
                // Load data into the ECharts instance
                myChart.setOption(option);
            }
        );
    };
</script>