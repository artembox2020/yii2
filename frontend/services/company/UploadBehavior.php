<?php

namespace frontend\services\company;

class UploadBehavior extends \vova07\fileapi\behaviors\UploadBehavior
{
    public function beforeDelete()
    {
        return null;
    }
}
