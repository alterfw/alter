<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/05/14
 * Time: 02:40 PM
 */

class App {

    public function registerModel($model){

        $modelName = str_replace('model', '', strtolower(get_class($model)));
        $this->{$modelName} = $model;

    }

} 