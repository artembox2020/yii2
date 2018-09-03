<table class="table table-striped table-bordered table-software-versions" id="table-software-versions">
    <tbody>
        <tr>
            <td class="cell-software">
                <b><?= $model->imeiRelation->attributeLabels()['firmware_version'] ?></b>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <b><?= $model->imeiRelation->firmware_version ?></b>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <b><?= $model->imeiRelation->attributeLabels()['firmware_version_cpu'] ?></b>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <b><?= $model->imeiRelation->firmware_version_cpu ?></b>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <b><?= $model->imeiRelation->attributeLabels()['communication_program_version'] ?></b>
                <br>
            </td>
        </tr>
        <tr>
            <td>
                <b><?= $model->imeiRelation->firmware_6lowpan.' '.$model->imeiRelation->number_channel ?></b>
                <br>
            </td>
        </tr>
    </tbody>
</table>