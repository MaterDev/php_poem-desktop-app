<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poem extends Model
{
    // Poem will be represented by an icon on the 'desktop'
    // will open to reveal contents.

    protected $fillable = [
        'title',
        'content',
        'icon_path',
        'icon_position_x',
        'icon_position_y',
        'window_position_x',
        'window_position_y'
    ] ;
}
