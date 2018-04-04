<?php

namespace App\Observers;

use Log;

class DocumentObserver {
    
    public function created($manifest) {
        Log::info("creating document...");
        //TODO:: Send an email
        $email = "saidfuad91@gmail.com ";
        Mail::to($email)->send(new DemoMail());

        $manifest->save();
    }


}
