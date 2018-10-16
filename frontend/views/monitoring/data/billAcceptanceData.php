<table class="table table-striped table-bordered table-bill-acceptance">
    <tbody>
        <tr>
            <td class="cell-bill-acceptance">
                <b><?= $model->attributeLabels()['status'] ?></b>
                <br>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <b><?= (float)$fullness ?>%</b>
                <?= 
                    \frontend\services\globals\EntityHelper::makePopupWindow(
                        [],
                        $model->attributeLabels()['bill_acceptance_fullness'],
                        'top: -26px',
                        'height: 8px'
                    )
                ?>
                <br>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <b>
                    <?= 
                        \frontend\services\globals\EntityHelper::makePopupWindow(
                            [
                                '/static/img/monitoring/in_banknotes.png',
                            ],
                            $model->attributeLabels()['in_banknotes']
                        )
                    ?>
                </b>
                <br>
                <?= $model->in_banknotes ?>
                <br>
            </td>
        </tr>
    </tbody>
</table>