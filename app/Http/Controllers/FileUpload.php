<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FileUpload extends Controller {

    public function index() {
        $files = File::all();
        return view('index', compact('files'));
    }

    public function createForm() {
        return view('file-upload');
    }

    public function fileUpload(Request $req) {
        try {
            $req->validate([
                'file' => 'required|mimes:pdf,txt,word,xml,csv|max:10240'
            ]);

            $fileModel = new File;

            if ($req->file()) {
                $fileName = $req->file->getClientOriginalName();
                $path = $req->file('file')->store('files', 's3');
                Storage::disk('s3')->setVisibility($path, 'public');

                $fileModel->name = $fileName;
                $fileModel->file_path = Storage::disk('s3')->url($path);
                $fileModel->save();

                return back()
                                ->with('success', 'File has been uploaded.')
                                ->with('file', $fileName);
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    public function deleteForm() {
        if (isset($_GET['id'])) {
            try {
                $file = File::find($_GET['id']);
                Storage::disk('s3')->delete($file->file_path);
                $file->delete();
                return true;
            } catch (Exception $ex) {
                return false;
            }
        }
    }

}
