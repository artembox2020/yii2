<?php

return [
    'class' => 'codemix\localeurls\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'languages' => ['uk', 'en'],
    'rules' => [
        '<module:\w+>/<controller:\w+>/<action:(\w|-)+>' => '<module>/<controller>/<action>',
        '<module:\w+>/<controller:\w+>/<action:(\w|-)+>/<id:\d+>' => '<module>/<controller>/<action>',
    ],
];
