<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShortcodePool extends Model
{
    const UPDATED_AT = null;

    protected $table = 'shortcode_pool';

    protected $fillable = ['shortcode'];
}
