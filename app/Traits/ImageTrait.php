<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait ImageTrait {

    public function uploadImage($file,$old_image){

        if(file_exists(public_path('storage/'.$old_image))){
            unlink(public_path('storage/'.$old_image));
        }

        return Storage::disk('public')->putFile('invoices', $file,'public');

    }






}
