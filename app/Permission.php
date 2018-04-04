<?php
/**
 * Created by IntelliJ IDEA.
 * User: wamaebenson06@gmail.com
 * Date: 24-Jan-18
 * Time: 4:23 PM
 */

namespace App;


class Permission extends \Spatie\Permission\Models\Permission{

    public function module(){
        return $this->hasOne(Module::class, 'id', 'module_id');
    }

}