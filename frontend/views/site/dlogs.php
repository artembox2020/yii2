<?php
use yii\grid\GridView;
use yii\helpers\Html; 
use frontend\models\Devices;
?>

<div class="panel panel-body">
    <form action="site/dlogs" method="post">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="imei">IMEI (полностью или часть):</label>
                    <input type="text" class="form-control" id="imei" name="imei" value="<?=Yii::$app->request->get('imei', "")?>">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="type">ТИП УСТРОЙСТВА (полностью или часть):</label>
                    <input type="text" class="form-control" id="type" name="type" value="<?=Yii::$app->request->get('type', "")?>">
                </div>
            </div>
			<br>
			<div class="col-md-4">
                <div class="form-group">
                   <a class="btn btn-group-justified btn-success" href ="/frontend/base">Пакеты состояний</a>
                </div>
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-group-justified btn-success">ПОИСК</button>
            </div>
        </div>
    </form>


</div>


<div class="panel panel-body">

<?= GridView::widget([
       'dataProvider' => $dataProvider,
       'pager' => ['maxButtonCount' => 20],
	   'options'=>['style' => 'font-size: 12px;'],
       'columns' => [
           /* ['class' => 'yii\grid\SerialColumn'], */
           [
               'label' =>"ID",
               'value'=>function($data){return $data['id'];}
           ],
           [
            'label' => 'Дата создания',
            'attribute' => 'datecreate',
            'value' => function($data) { return date("d M Y / H:i:s", strtotime($data['edate'])); },
           ],
		   [
               'label' =>"IMEI",
               'value'=>function($data){return $data['imei'];}
           ],
 		   [
               'label' =>"Type",
               'value'=>function($data){return $data['type'];}
           ],
		    [
               'label' =>"Номер устройства",
               'value'=>function($data){return $data['num_dev'];}
           ],
		   [
               'label' =>"Состояние устройства",
               'value'=>function($data){return Html::encode(Devices::getSob($data->type, $data['status_dev']));}
           ],		   
		   [
               'label' =>Yii::t('base', 'Сумма пополнения'),
               'value'=>function($data){return $data['esum'];}
           ],		   
		   [
               'label' =>Yii::t('base', 'Несгораемый счетчик грн'),
               'value'=>function($data){return $data['ch_uah'];}
           ],
		   [
               'label' =>Yii::t('base', 'Несгораемый счетчик карты'),
               'value'=>function($data){return $data['ch_map'];}
           ],
		   [
               'label' =>Yii::t('base', 'Cчетчик инкасаций'),
               'value'=>function($data){return $data['ch_incasso'];}
           ],
		   [
               'label' =>Yii::t('base', 'Купюр в купюроприемнике'),
               'value'=>function($data){return $data->col_cup;}
           ],
		   		   [
               'label' =>Yii::t('base', 'Уровень связи с модемом'),
               'value'=>function($data){return $data->lmodem;}
           ],
		   [
               'label' =>Yii::t('base', 'Цена услуги'),
               'value'=>function($data){return $data->price;}
           ],
		   [
               'label' =>Yii::t('base', 'Режим стирки'),
               'value'=>function($data){return Html::encode(Devices::getRezim($data->rezim));}
           ],
		   [
               'label' =>Yii::t('base', 'Кол-во денег на счету'),
               'value'=>function($data){return $data->col_mon;}
           ],
		   [
               'label' =>Yii::t('base', 'Температура стирки'),
               'value'=>function($data){return Html::encode(Devices::getTemp($data->tstir));}
           ],
		   [
               'label' =>Yii::t('base', 'Вид отжима'),
               'value'=>function($data){return Html::encode(Devices::getOtzim($data->otzim_type));}
           ],
		   [
               'label' =>Yii::t('base', 'Предварительная стирка'),
               'value'=>function($data){return $data->p_stir;}
           ],
		   [
               'label' =>Yii::t('base', 'Полоскание'),
               'value'=>function($data){return $data->polosk;}
           ],
		   [
               'label' =>Yii::t('base', 'Связь с устройством'),
               'value'=>function($data){return $data->sv;}
           ],
		   [
               'label' =>Yii::t('base', 'На счету устройства'),
               'value'=>function($data){return $data->nch;}
           ],
		   [
               'label' =>Yii::t('base', 'Кол-во геля'),
               'value'=>function($data){return $data->col_gel;}
           ],
		   [
               'label' =>Yii::t('base', 'Выдано геля за сеанс'),
               'value'=>function($data){return $data->by_gel;}
           ],
       ],
   ]); ?>

</div>