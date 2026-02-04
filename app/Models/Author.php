<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'books_count',
    ];

    /**
     * Get the books for the author.
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
