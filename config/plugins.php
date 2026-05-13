<?php

return [
    'DebugKit' => [
        'onlyDebug' => true,
    ],
    'Bake' => [
        'onlyCli' => true,
        'optional' => true,
    ],
    'Migrations' => [
        'onlyCli' => true,
    ],
    'Authentication' => [],
    'ContentBlocks' => ['path' => ROOT . DS . 'plugins' . DS . 'ContentBlocks' . DS],
];
