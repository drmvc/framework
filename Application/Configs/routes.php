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

/**
 * Default route:
 * <controller> - application controller name
 * <action>     - "action_*" from controller
 * <id>         - dynamical variable, you can get this via $this->request->param()
 */
Route::set('default', '(<controller>(/<action>(/<id>)))')
    ->defaults(array(
        'controller' => 'Index',
        'action' => 'index',
    ));
