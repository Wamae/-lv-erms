<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model {
    
    public function category() {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }
    
     public function subCategory() {
        return $this->hasOne(SubCategory::class, 'id', 'sub_category_id');
    }

    public function creator() {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function updater() {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }

}
