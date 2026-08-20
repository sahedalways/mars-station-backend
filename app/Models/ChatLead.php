<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChatLead extends Model
{
    protected $fillable = ['name', 'email', 'agent'];
}
