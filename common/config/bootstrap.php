<?php
require __DIR__ . '/../../common/env.php';
/**
 * Setting path aliases
 */
Yii::setAlias('root', realpath(__DIR__ . '/../../'));
Yii::setAlias('common', realpath(__DIR__ . '/../../common'));
Yii::setAlias('frontend', realpath(__DIR__ . '/../../frontend'));
Yii::setAlias('console', realpath(__DIR__ . '/../../console'));

/**
 * Setting url aliases
 */
Yii::setAlias('frontendUrl', env('FRONTEND_URL'));
