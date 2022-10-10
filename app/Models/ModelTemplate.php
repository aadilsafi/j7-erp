<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelTemplate extends Model
{
    use HasFactory;


    public function Model_Templates($model_name){
        return Template::select('templates.*')->join('model_templates','model_templates.template_id' , '=', 'templates.id')->where('model_type', $model_name)->get();
    }
}
