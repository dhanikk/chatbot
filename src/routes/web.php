<?php
    use Ips\Chatbot\Http\Controllers\ChatbotController;
    // YourVendor\contactform\src\routes\web.php
    Route::get('contact', function(){
        return view('chatbot::index');
    });
    Route::post('sendmessage', [ChatbotController::class, 'sendrequest'])->name('user.sendmessage');
?>