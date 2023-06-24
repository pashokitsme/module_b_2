<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Accesses extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_ref',
        'user_ref',
    ];

    public function file() {
        return $this->belongsTo(File::class, 'file_ref');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_ref');
    }
}
