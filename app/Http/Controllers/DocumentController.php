<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\DocumentManagement;
use App\Models\Document;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    private $dms;
    private $role;
    private $broker_id = 0;

    public function __construct(DocumentManagement $dms)
    {
        $this->dms = $dms;
        $this->broker_id = \Auth::user() ? \Auth::user()->id : 0;
        $this->role = \Auth::user() ? \Auth::user()->role : "";
    }

    public function create(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'table_name' => 'required',
                'record_id' => 'required',
                'document' => 'required|file|mimes:jpeg,png,jpg,gif,svg,xlsx,xls,doc,docx,ppt,pptx,txt,pdf|max:1000048',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $doc = $request->document;
                $table_name = $request->input('table_name');
                $record_id = $request->input('record_id');

                $this->dms->storeFile($doc, $table_name, $record_id);

                return $this->respondWithSuccess('');
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function remove($id)
    {
        try {
            $this->dms->removeFileById($id);

            return $this->respondWithSuccess('');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getDocuments(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'table_name' => 'required',
                'record_id' => 'required',
            ]);
            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $table = $request->input('table_name');
                $record_id = $request->input('record_id');

                $result = Document::where([
                    'table_name' => $table,
                    'record_id' => $record_id,
                ])->get();

                return $this->respondWithSuccess($result);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

}
