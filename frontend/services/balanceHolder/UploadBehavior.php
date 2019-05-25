<?php

namespace frontend\services\balanceHolder;

class UploadBehavior extends \vova07\fileapi\behaviors\UploadBehavior
{
    public function beforeDelete()
    {
        return null;
    }
}
