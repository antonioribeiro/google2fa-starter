<?php

Route::get('/', ['uses' => 'Home@index']);
Route::post('check2fa', ['uses' => 'Home@check2fa']);
