<?php

use Illuminate\Support\Facades\View;
use Itpathsolutions\Chatbot\Http\Controllers\ChatbotController;
    // YourVendor\contactform\src\routes\web.php
    Route::get('contact', function(){
        return View::make('chatbot::index');
    });
    Route::post('sendmessage', [ChatbotController::class, 'sendrequest'])->name('user.sendmessage');
?>