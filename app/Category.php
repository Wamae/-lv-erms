<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public function creator() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updater() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
