<?php

namespace System\Core;

Route::set('page', 'page')
    ->defaults(array(
        'controller' => 'Page',
        'action' => 'index',
    ));

Route::set('error', 'error')
    ->defaults(array(
        'controller' => 'Error',
        'action' => 'index',
    ));

Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'controller' => 'Index',
        'action' => 'index',
    ));
