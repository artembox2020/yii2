<?php

/* @var $brand string */
/* @var $brand_url string */
/* @var $menuItems array */

?>
<nav id="w1" class="navbar-inverse navbar-fixed-top navbar">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#w1-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="<?= $brand_url ?>">
                <?= $brand ?>
            </a>
        </div>
        <div id="w1-collapse" class="collapse navbar-collapse">
            <ul id="w2" class="navbar-nav navbar-right nav">
                <li>
                    <a class="add-card">
                        <span class="add-card-label">
                            <span class="label-add-card"><?= Yii::t('map', 'Add Card') ?></span>
                            <span class="glyphicon glyphicon-plus"></span>
                        </span>
                        <?= Yii::$app->view->render(
                            '@frontend/views/map/templates/card_confirmation',
                            [
                                'userId' => Yii::$app->user->id
                            ]
                        )
                        ?>   
                    </a>
                </li>
                <?php
                    foreach ($menuItems as $item):
                ?>
                    <?php if (empty($item['items'])): ?>
                        <li><a href="<?= $item['url'][0] ?>"><?= $item['label'] ?></a></li>
                    <?php else: ?>
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="<?= $item['url'] ?>" data-toggle="dropdown" aria-expanded="false">
                                <?= $item['label'] ?> <span class="caret"></span>
                            </a>
                            <ul id="w3" class="dropdown-menu">
                            <?php foreach ($item['items'] as $key => $row): ?>
                                <li>
                                    <a
                                        <?php if ($key == count($item['items']) - 1): ?> data-method="post" <?php endif; ?>
                                        href="<?= $row['url'][0] ?>" tabindex="-1"
                                    >
                                        <?= $row['label'] ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </li>
                    <?php endif; ?>
                <?php
                    endforeach;
                ?>
            </ul>
        </div>
    </div>
</nav>