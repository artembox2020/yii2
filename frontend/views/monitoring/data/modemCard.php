        <b>
            <?= $model->attributeLabels()['imei'] ?>
        </b>
        <br>
        <?= $model->imei ?>
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
                    $model->attributeLabels()['money_in_banknotes']
                )
            ?>
        </b>
        <br>
        <?= $model->money_in_banknotes ?>
        <br>
    </td>
</tr>