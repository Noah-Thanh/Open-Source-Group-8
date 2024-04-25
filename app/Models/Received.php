<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Received extends Model
{
    protected $fillable=['product_id','user_id','order_id'];
}
