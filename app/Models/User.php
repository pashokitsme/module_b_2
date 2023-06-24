<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password',
        'token'
    ];

    public function getFile(string $fileHash, bool $owned = false): File {
        if (!$file = File::where("hash", $fileHash)->first())
            throw new NotFoundHttpException("File " . $fileHash . " not found");

        if ($file->author_ref == $this->id || (!$owned && Accesses::where('user_ref', $this->id)->where('file_ref', $file->id)->first() != null))
            return $file;

        throw new AccessDeniedHttpException();
    }

    public function createFile(UploadedFile $file): ?File {
        if (in_array($file->getMimeType(), File::mimes) || $file->getSize() > 2048 ** 3)
            return null;
        $hash = Str::random(24);
        $name = $file->getClientOriginalName();
        Storage::disk("local")->put($hash, $file->getContent());
        return File::create(['hash' => $hash, 'author_ref' => $this->id, 'name' => $name]);
    }
}
