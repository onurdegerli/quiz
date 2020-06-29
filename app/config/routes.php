<?php

return [
    [
        'route' => '/',
        'controller' => 'User',
        'action' => 'register',
        'methods' => [
            'GET',
        ]
    ],
    [
        'route' => '/user/:id/result/:quiz_id',
        'controller' => 'User',
        'action' => 'result',
        'methods' => [
            'GET',
        ]
    ],
    [
        'route' => '/user',
        'controller' => 'User',
        'action' => 'save',
        'methods' => [
            'POST',
        ]
    ],
    [
        'route' => '/quiz/:id/question',
        'controller' => 'Quiz',
        'action' => 'question',
        'methods' => [
            'GET',
        ]
    ],
    [
        'route' => '/quiz/answer',
        'controller' => 'Quiz',
        'action' => 'answer',
        'methods' => [
            'POST',
        ]
    ]
];