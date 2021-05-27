<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    public function create(Request $request)
    {
        try{
            $validation = Validator::make($request->all(), [
                //'key' => 'required|max:255',
                'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg',
                'content' => 'required',
                'image_position' => 'required'
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $section = new Section();
                $section->key = rand(0,9999999);
               

                $section->image = $request->input('image');
                $section->content = $request->input('content');
                $section->image_position = $request->input('image_position');

                
                if($request->hasFile('image') && $request->image != $section->image){
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time().'.'.$extension;       
                    $file->move(public_path('/uploads'), $filename);
                    $section->image = $filename;    
                }
               


                $section->save();

                return $this->respondWithSuccess($section);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            $id = $request->input('id');

            $validation = Validator::make($request->all(), [
                'id' => 'required',
                'content' => 'required',
                'image_position' => 'required'
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $section = Section::where(['id' => $id])->first();

                if (!$section) {
                    return $this->respondWithError('Record not found!');
                }

                // $section->key = rand(0,9999999);
                // $section->image = $request->input('image');
                $section->content = $request->input('content');
                $section->image_position = $request->input('image_position');
                if($request->hasFile('image') && $request->image != $section->image){
                    $file = $request->file('image');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time().'.'.$extension;       
                    $file->move(public_path('/uploads'), $filename);
                    $section->image = $filename;    
                }


                $section->save();
                DB::commit();

                return $this->respondWithSuccess($brand);
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->respondWithError($ex->getResponse(), 'exception');
        }
    }



	public function search(Request $request , $value)
	{
		Section::query()
				->where('content', 'LIKE', "%{$value}%") 
				->orWhere('key', 'LIKE', "%{$value}%") 
				->get();
    }
    

    public function remove($id)
    {
        try {
            $section = Section::find($id);
            if (!$section) {
                return $this->respondWithError('Record not found!');
            }

            if($section->image){
                $filename=$section->image;
                Storage::disk('public')->delete("/uploads/" . $filename);
            }

            $section->delete();

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getById($id)
    {
        $result = Section::find($id);
        return $this->respondWithSuccess($result);
    }
    public function getAllSections()
    {
        try {
            $result = Section::get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
}
