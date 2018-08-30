<table class="table table-striped table-bordered table-bill-acceptance">
    <tbody>
        <tr>
            <td>
                <b><?= $model->attributeLabels()['status'] ?></b>
                <br>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <i><?= $model->attributeLabels()['bill_acceptance_fullness'] ?></i>
                <br>
                <b><?= $fullness ?></b>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <b><?= $model->attributeLabels()['in_banknotes'] ?></b>
                <br>
                <?= $model->in_banknotes ?>
                <br>
            </td>
        </tr>
    </tbody>
</table>