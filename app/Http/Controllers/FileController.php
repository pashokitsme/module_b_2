<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use App\Http\Requests\RenameRequest;
use App\Http\Requests\Request;
use App\Http\Requests\UploadRequest;
use App\Models\Accesses;
use App\Models\File;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function upload(UploadRequest $req) {
        $result = [];
        foreach ($req->file('files') as $uploaded) {
            $file = $req->user->createFile($uploaded);
            $result[] = $file
                ? [
                    'success' => true,
                    'url' => request()->getSchemeAndHttpHost() . '/files/' . $file->hash,
                    'name' => $file->name,
                    'file_id' => $file->hash
                ]
                : [
                    'success' => false,
                    'message' => 'File not loaded',
                    'name' => $uploaded->getClientOriginalName()
                ];
        }

        return $this->json($result);
    }

    public function index(Request $req, string $hash) {
        $file = $req->user->getFile($hash);
        $path = Storage::path($hash);
        return response()->download($path, $file->name);
    }

    public function rename(RenameRequest $req) {
        $req->file->name = $req->name;
        $req->file->save();
        return $this->json(['success' => true, 'message' => 'Renamed', 'name' => $req->file->name]);
    }

    public function delete(Request $req) {
        Storage::delete($req->file->hash);
        $req->file->delete();
        return $this->json(['success' => true, 'message' => 'File deleted']);
    }

    public function accesses(Request $req, $file = null, $users = null) {
        $file = $file ?: $req->file;
        $users = $users ?: [['fullname' => $req->user->name, 'email' => $req->user->email, 'type' => 'author']];
        foreach ($file->refresh()->accessed->all() as $user) {
            $users[] = ['fullname' => $user->name, 'email' => $user->email, 'type' => 'co-author'];
        }
        return $users;
    }

    public function grant(PermissionRequest $req) {
        if ($req->email == $req->user->email || $req->file->accessed->contains("email", $req->email))
            return $this->json(['success' => false, 'message' => "Couldn't grant permission to file " . $req->file->name . " to user " . $req->email]);
        $user = User::where('email', $req->email)->first();
        Accesses::create(['user_ref' => $user->id, 'file_ref' => $req->file->id]);
        return $this->json($this->accesses($req));
    }

    public function forbid(PermissionRequest $req) {
        if ($req->email == $req->user->email || !$req->file->accessed->contains("email", $req->email))
            return $this->json(['success' => false, 'message' => "Couldn't forbid file " . $req->file->name . " to user " . $req->email]);
        $user = User::where('email', $req->email)->first();
        Accesses::where(['user_ref' => $user->id, 'file_ref' => $req->file->id])->delete();
        return $this->json($this->accesses($req));
    }

    public function listOwnedFiles(Request $req) {
        $files = [];
        foreach(File::where("author_ref", $req->user->id)->get() as $file) {
            $files[] = [
                'file_id' => $file->hash,
                'name' => $file->name,
                'url' => request()->getSchemeAndHttpHost() . '/files/' . $file->hash,
                'accesses' => $this->accesses($req, $file)
            ];
        }

        return $this->json($files);
    }

    public function listAccessedFiles(Request $req) {
        $files = [];
        foreach(Accesses::where("user_ref", $req->user->id)->get() as $access) {
            $file = $access->file;
            $files[] = [
                'file_id' => $file->hash,
                'name' => $file->name,
                'url' => request()->getSchemeAndHttpHost() . '/files/' . $file->hash,
            ];
        }

        return $this->json($files);
    }
}
