<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class News extends Model
{
    use HasFactory;
    use Filterable;
    use AsSource;
    public $timestamps = false;

    protected $fillable = [
        'guid',
        'title',
        'description',
        'pubdate',
        'author',
        'image'
    ];

    protected $allowedSorts = [
        'pubdate'
    ];


    public function __construct(array $attributes = [])
    {
        $this->primaryKey = 'guid';
        $this->keyType = 'string';
        parent::__construct($attributes);
    }
}
