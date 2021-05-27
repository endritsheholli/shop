<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Image;

class DocumentManagement
{
    private $user_id = 0;

    public function __construct()
    {
        if (\Auth::user()) {
            $this->user_id = \Auth::user()->id;
        }
    }
    public function storeFile($file, $table_name, $record_id)
    {
        try {
            $file_name = uniqid() . '.' . $file->getClientOriginalExtension();
            $original_name = $file->getClientOriginalName();

            $img = Image::make($file);
            $width = $img->width();
            if ($width > 900) {
                // resize the image to a width of 300 and constrain aspect ratio (auto height)
                $img->resize(900, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }

            $height = $img->height();
            if ($height > 900) {
                // resize the image to a height of 200 and constrain aspect ratio (auto width)
                $img->resize(null, 900, function ($constraint) {
                    $constraint->aspectRatio();
                });
            }
            // $img->insert('file:///C:/xampp/htdocs/postashop/public/images/watermark-sm.png', 'center');

            $img->insert('https://dy-po.com/images/watermark-sm.png', 'bottom-right');
            $resource = $img->stream()->detach();

            $path = "images/originals/" . $file_name;

            $pathName = Storage::disk('s3')->put($path, $img->__toString(), 'public');
            // $pathName = Storage::disk('s3')->put($path, file_get_contents($file), 'public');
            $path_s3 = Storage::disk('s3')->url($path);
            $doc = new Document();
            $doc->table_name = $table_name;
            $doc->record_id = $record_id;
            $doc->file_name = $file_name;
            $doc->path = $path_s3;
            $doc->original_name = $original_name;
            $doc->user_id = $this->user_id;

            $doc->save();

            return $path_s3;
        } catch (Exception $ex) {
            return dd($ex->getMessage());
        }
    }

    public function removeFile($table_name, $record_id)
    {
        try {
            $doc = Document::where(['table_name' => $table_name, 'record_id' => $record_id])->first();
            if ($doc) {
                $url = 'public/' . $doc->path;

                if (Storage::exists($url)) {
                    Storage::delete($url);
                }

                $doc->delete();
            }
        } catch (Exception $ex) {
            return '';
        }
    }

    public function removeMultipleFile($table_name, $record_id, $paths)
    {
        try {
            $doc = Document::where(['table_name' => $table_name, 'record_id' => $record_id])->first();
            if ($doc) {
                $parts = $paths;
                foreach ($parts as $part) {
                    $url = 'public/' . $part;
                    if (Storage::exists($url)) {
                        Storage::delete($url);
                    }
                }
                $doc->delete();
            }
        } catch (Exception $ex) {
            return '';
        }
    }

    public function removeFileById($doc_id)
    {
        try {
            $doc = Document::find($doc_id);
            if ($doc) {
                $url = 'public/' . $doc->path;

                if (Storage::exists($url)) {
                    Storage::delete($url);
                }
                $doc->delete();
            }
        } catch (Exception $ex) {
            return '';
        }
    }
    public function removeFromS3($path)
    {
        $path_replace = str_replace("https://dy-po-bucket.s3.eu-central-1.amazonaws.com/", "", $path);
        if (Storage::disk('s3')->exists($path_replace)) {
            Storage::disk('s3')->delete($path_replace);
        }
    }
    public function removeMultipleFromS3($paths)
    {
        $parts = $paths;
        foreach ($parts as $part) {
            $path_replace = str_replace("https://dy-po-bucket.s3.eu-central-1.amazonaws.com/", "", $part);
            if (Storage::disk('s3')->exists($path_replace)) {
                Storage::disk('s3')->delete($path_replace);
            }
        }
    }
}
