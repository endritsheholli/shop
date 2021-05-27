<?php

namespace App\Http\Controllers;

use App\Models\Categories;
use App\Models\CatParam;
use App\Models\Parameters;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Services\DocumentManagement;

class CategoriesController extends Controller
{

    private $dms;

    public function __construct(DocumentManagement $dms)
    {

        $this->dms = $dms;
    }

    public function create(Request $request)
    {
        try {
            DB::beginTransaction();
            $validation = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'isImportant' => 'required',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $categories = new Categories();
                $categories->name = $request->input('name');
                $categories->slug = $this->slugify($request->input('name'));
                $parent_id = $categories->parentId = $request->input('parentId');
                $categories->isImportant = is_null($parent_id) ? json_decode($request->input('isImportant')) : $request->input('isImportant');
                $categories->save();

                if (is_null($parent_id)) {
                    if ($request->file('images')) {
                        $path = $this->dms->storeFile($request->file('images'), 'categories', $categories->id);
                        $categories->image_urls = $path;
                        $categories->save();
                    }
                }

                $cat_param = $request->input('paramters');
                if ($cat_param && is_array($cat_param)) {
                    foreach ($cat_param as $p) {
                        $cat_parameters = new CatParam();
                        $cat_parameters->category_id = $categories->id;
                        $cat_parameters->parameter_id = $p['id'];
                        $cat_parameters->save();
                    }
                }
                DB::commit();
                return $this->respondWithSuccess($categories);
            }
        } catch (Exception $ex) {
            DB::rollBack();
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
                'name' => 'required|max:255',
                'isImportant' => 'required',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $categories = Categories::where(['id' => $id])->first();

                if (!$categories) {
                    return $this->respondWithError('Record not found!');
                }

                $categories->name = $request->input('name');
                $parent_id = $categories->parentId = $request->input('parentId');
                $categories->isImportant = $parent_id == 0 || is_null($parent_id) ? json_decode($request->input('isImportant')) : $request->input('isImportant');
                $categories->save();
                if ($parent_id == 0 || is_null($parent_id)) {
                    if ($request->file('images')) {
                        $this->dms->removeFromS3($categories->image_urls);
                        $path = $this->dms->storeFile($request->file('images'), 'categories', $categories->id);
                        $categories->image_urls = $path;
                        $categories->save();
                    }
                }


                CatParam::where('category_id', $categories->id)->delete();
                $cat_param = $request->input('parameters');
                if ($cat_param) {
                    foreach ($cat_param as $pc) {
                        $emp_cat = new CatParam();
                        $emp_cat->category_id = $categories->id;
                        $emp_cat->parameter_id = $pc['id'];
                        $emp_cat->save();
                    }
                }

                DB::commit();
                $dataCat = $this->getById($categories->id);
                // dd($dataCat);
                return $dataCat;
            }
        } catch (Exception $ex) {
            DB::rollBack();
            return $this->respondWithError($ex->getResponse(), 'exception');
        }
    }

    public function remove($id)
    {
        try {
            $categories = Categories::find($id);
            if (!$categories) {
                return $this->respondWithError('Record not found!');
            }

            $categories->delete();

            Categories::where('parentId', $id)->delete();
            CatParam::where('category_id', $id)->delete();

            return $this->respondWithSuccess('Success');
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getById($id)
    {
        $result = Categories::find($id);
        if ($result) {
            $children = Categories::where('parentId', $id)->get();
            $params = Parameters::select(\DB::raw("parameters.id as id, parameters.name as name"))
                ->leftJoin('cat_param as cp', 'parameters.id', 'cp.parameter_id')
                ->where('cp.category_id', $id)
                ->get();
            $result->children = $children;
            $result->params = $params;
            return $this->respondWithSuccess($result);
        } else {
            return $this->respondWithError("Categories not  found");
        }
    }

    public function getParametersByCategoryId($id)
    {
        try {
            $result = Parameters::with('children')->where('parent_id', null);
            if ($id > 0) {
                $result = $result->where('category_id', $id);
            }
            $result = $result->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError("Parameters not  found");
        }
    }

    public function getBySlug($slug)
    {
        $result = Categories::where('slug', $slug)->first();
        if ($result) {
            $children = Categories::where('slug', $slug)->get();
            $params = Parameters::select(\DB::raw("parameters.id as id, parameters.name as name"))
                ->leftJoin('cat_param as cp', 'parameters.id', 'cp.parameter_id')
                ->where('cp.category_id', $result->id)
                ->get();
            $result->children = $children;
            $result->params = $params;
            return $this->respondWithSuccess($result);
        } else {
            return $this->respondWithError("Categories not  found");
        }
    }
    public function getAllCategories()
    {
        try {
            $result = Categories::get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getMainCategories()
    {
        try {
            $categories = Categories::select('categories.*')
                ->whereRaw('parentId is null or parentId = 0')
                ->get();
            return $this->respondWithSuccess($categories);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getMainCategoriesById($id)
    {
        try {
            $categories = Categories::with('children')
                ->whereRaw('parentId is null or parentId = 0');
            if ($id && $id > 0) {
                $categories = $categories->where('categories.id', $id);
            }
            $categories =  $categories->get();
            return $this->respondWithSuccess($categories);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getImportantCategories()
    {
        try {
            $result = Categories::where(['isImportant' => true])->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getCategoryParents()
    {
        try {
            $result = Categories::with('children')
                ->whereRaw('parentId is null or parentId = 0')
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    // Parameters Function

    public function getAllParameters()
    {
        try {
            $result = Parameters::select('parameters.name')->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    // Parameters - Category

    public function getAllParametersOfThisCategory($id)
    {
        try {
            $result = Parameters::where('category_id', $id)->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getParents()
    {
        try {
            $result = Parameters::with('children')->where('parent_id', null)->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function getByParent($id)
    {
        try {
            $result = Parameters::find($id);
            if (!$result) {
                return $this->respondWithError("Nuk u gjet produkt me kete id");
            }
            $result->child = Parameters::where('parent_id', $id)->get();

            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function createParameters(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|max:255',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $parameters = new Parameters();
                $name = $parameters->name = $request->input('name');
                $parameters->parent_id = $request->input('parent_id');
                if (!Parameters::where('name', $name)->exists()) {
                    $parameters->category_id = $request->input('category_id');
                    if ($request->input('parent_id') || $request->input('parent_id') > 0) {
                        $parent_id = Parameters::find($request->input('parent_id'));
                        $parameters->category_id = $parent_id->category_id;
                        if ($parent_id) {
                            $parameters->parent_id = $request->input('parent_id');
                        } else {
                            return $this->respondWithError("You can't create this parameters");
                        }
                    }

                    $parameters->save();
                }
                return $this->respondWithSuccess($parameters);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function updateParameters(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|max:255',
                'id' => 'required|max:255',
            ]);

            if ($validation->fails()) {
                $errors = $validation->errors();
                return $this->respondWithError($errors);
            } else {
                $parameters = Parameters::find($request->input('id'));
                $parameters->name = $request->input('name');
                if ($request->input('parent_id') || $request->input('parent_id') > 0) {
                    $parent_id = Parameters::find($request->input('parent_id'));
                    if ($parent_id) {
                        $parameters->parent_id = $request->input('parent_id');
                    } else {
                        return $this->respondWithError("You can't to create this parameters");
                    }
                }
                $parameters->save();
                return $this->respondWithSuccess($parameters);
            }
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }
    public function deleteParameters($id)
    {
        try {
            $parameters = Parameters::find($id);
            $parameters->delete();
            return $this->respondWithSuccess($parameters);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getCategorieswWithParameters()
    {
        try {
            $result = Categories::select(DB::raw('categories.id as category_id ,
            categories.name as category_name,
            categories.parentId as categories_parent_id,
            p.name as parameter_name,
            p.id as paramete_id'))
                ->join('cat_param as cp', 'cp.category_id', 'categories.id')
                ->join('parameters as p', 'p.id', 'cp.parameter_id')
                ->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
    }

    public function getCompleteCategories()
    {
        $completeMenu = array();
        try{
            $result = Categories::with('children')->orderBy('id','asc')->get();
            return $this->respondWithSuccess($result);
        } catch (Exception $ex) {
            return $this->respondWithError($ex->getResponse());
        }
        
    }
}
