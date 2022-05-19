<?php

use yii\grid\GridView;
use yii\widgets\Pjax;

//echo $dataProvider->query->createCommand()->rawSql;

?>

<?php Pjax::begin(['id' => 'phrases']); ?>

<?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $model,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'id',
            'phrase',
            'lang',
            'sid',
            'created_at',
            [
                'label'=>'',
                'format'=>'raw',
                'value' => function($data) use ($model) {

                    return $this->render('actions_list', ['data' => ['phraseId' => $data->id]]);
                }
            ],
        ],
    ]);
?>

<?php Pjax::end(); ?>
