<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function(){
    if (auth()->user()) {
        return redirect(route('platform.main'));
    }
    return redirect(route('platform.login'));
});
