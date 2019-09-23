<div class="main-page-model">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th><?= Yii::t('frontend', 'BalanceHolder, Address, Name, Phone') ?></th>
                <th><?= Yii::t('frontend', 'Address') ?></th>
                <th><?= Yii::t('frontend', 'Modem') ?></th>
                <th><?= Yii::t('frontend', 'Device Statuses') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($balanceHolders as $balanceHolder): ?>    
            <tr>
                <td class="cell-device">
                    <table>
                        <tr>
                            <td>
                                <?= Yii::$app->commonHelper->link($balanceHolder) ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <?= $balanceHolder->address ?>
                            </td>
                        </tr>
                    <?php if ($balanceHolder->phone): ?>
                        <tr>
                            <td>
                                <?= Yii::t('common', 'Tel.').$balanceHolder->phone ?>
                            </td>
                        </tr>
                    <?php endif; ?>
                    </table>
                </td>
                <td class="cell-device">
                    <table class="table table-striped table-bordered table-address-container">
                    <?php 
                        foreach ($balanceHoldersData[$balanceHolder->id] as $balanceHolderData):
                    ?>
                    <tr style="height: <?= $balanceHolderData['height'] ?>">
                        <td class="cell-device">
                            <?= Yii::$app->commonHelper->link($balanceHolderData['address']) ?>, 
                            <?= $balanceHolderData['address']->floor ?>
                        </td>
                    </tr>
                    <?php
                        endforeach;
                    ?>
                    </table>
                </td>
                <td class="cell-device">
                    <table class="table table-striped table-bordered">
                    <?php foreach ($balanceHoldersData[$balanceHolder->id] as $balanceHolderData): ?>    
                        <tr style="height: <?= $balanceHolderData['height'] ?>">
                            <td class="cell-device">
                            <?php if ($balanceHolderData['address']->imei): ?>
                                IMEI:
                                <?= Yii::$app->commonHelper->link($balanceHolderData['address']->imei) ?>
                                Init: 
                                <?= $balanceHolderData['address']->imei->getInit() ?>
                                <?= \Yii::$app->formatter->asDate($balanceHolderData['address']->imei->updated_at, 'short') ?>
                            <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </table>
                </td>
                <td class="cell-device device-statuses">
                    <table class="table table-striped table-bordered table-address-container">
                    <?php 
                        foreach ($balanceHoldersData[$balanceHolder->id] as $balanceHolderData):
                            foreach ($balanceHolderData['mashines'] as $mashine):
                    ?>
                        <tr class="device-status-row">
                            <td>
                                <?=
                                    Yii::$app->commonHelper->link(
                                        $mashine,
                                        [],
                                        Yii::t('frontend', 'WM').' '.$mashine->number_device.
                                        '(status:'.Yii::t('frontend', $mashine->getState()).')'
                                    )
                                ?>
                            </td>
                        </tr>
                    <?php
                            endforeach;
                            if (count($balanceHolderData['mashines']) == 0):
                    ?>
                         <tr class="device-status-row">
                            <td>
                                ---
                            </td>
                        </tr>
                    <?php
                            endif;
                        endforeach;
                    ?>
                    </table>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>