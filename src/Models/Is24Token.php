<?php

namespace VOLLdigital\LaravelImmobilienscout24\Models;

use Illuminate\Database\Eloquent\Model;

class Is24Token extends Model
{

    protected $table = 'is24_tokens';

    protected $fillable = ['is_identifier', 'is_secret',];

}