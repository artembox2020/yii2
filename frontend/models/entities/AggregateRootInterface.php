<?php

namespace frontend\models\entities;

interface AggregateRootInterface
{
    public function releaseEvents();
}
