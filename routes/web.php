<?php

Route::get('/', 'SiteController@indexResultadoSorteio');

Route::post('/cadastrarAposta', 'SiteController@cadastrarAposta');
