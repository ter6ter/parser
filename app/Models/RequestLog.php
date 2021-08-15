<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class RequestLog extends Model
{
    use HasFactory;
    use AsSource;

    protected $table = 'log';

    protected $fillable = [
        'method',
        'url',
        'response_code',
        'response_body'
    ];
}
