        <b>
            <?= $model->attributeLabels()['imei'] ?>
        </b>
        <br>
        <?= $model->imeiRelation->imei ?>
        <input
            type="hidden" 
            class="search-imei-value" 
            value="<?= mb_strtolower($model->imeiRelation->imei) ?>"
        />
        <br>
    </td>
</tr>
<tr>
    <td class="cell-remote-connection">
        <b>
            <?= $model->imeiRelation->attributeLabels()['phone_module_number'] ?>
        </b>
        <br>
        <?= $model->imeiRelation->phone_module_number ?>
        <br>
    </td>
</tr>
<tr>
    <td>
        <b>
            <?= 
                \frontend\services\globals\EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/level_signal.png',
                    ],
                    $model->attributeLabels()['level_signal']
                )
            ?>
        </b>
        <br>
        <?= $model->level_signal ?>
        <br>
    </td>
</tr>
<tr class="modem-card-last-row">
    <td>
        <b>
            <?= 
                \frontend\services\globals\EntityHelper::makePopupWindow(
                    [
                        '/static/img/monitoring/money_in_banknotes.png',
                    ],
                    Yii::t('frontend', 'On the bill of SIM')
                )
            ?>
        </b>
        <br>
        <?= \Yii::$app->formatter->asDecimal($model->on_modem_account, 0) ?>
        <br>
    </td>
</tr>