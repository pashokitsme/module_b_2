<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class File extends Model
{
    const mimes = ['doc', 'pdf', 'docx', 'zip', 'jpeg', 'jpg', 'png'];

    use HasFactory;

    protected $fillable = [
        'hash',
        'name',
        'author_ref',
    ];

    public function author() {
        return $this->belongsTo(User::class, 'author_ref');
    }
    public function accessed() {
        return $this->hasManyThrough(User::class, Accesses::class, 'file_ref', 'id', 'id', 'user_ref');
    }
}
