<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait {

    public function uploadImage($file){

        return Storage::disk('public')->putFile('invoices', $file,'public');
        
    }






}
