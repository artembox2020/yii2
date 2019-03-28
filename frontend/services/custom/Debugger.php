<?php

namespace frontend\services\custom;


class Debugger
{
    public static function d($element)
    {
//        echo '<pre>';
        var_dump($element);
    }

    public static function dd($element)
    {
        echo '<pre>';
        var_dump($element);die;
    }
}
