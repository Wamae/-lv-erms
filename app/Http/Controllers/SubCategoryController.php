<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\SubCategory;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class SubCategoryController extends Controller {

    public function __construct() {
        $this->middleware(['auth']);
        $this->title = "Sub Categories";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $title = $this->title;
        $categories = Category::select(['id','category'])->where('status','=',ACTIVE)->get();

        return view('sub_categories.index', compact('title','categories'));
    }

    /**
     * Get datatables grid data
     * @param Request $request
     * @return type
     */
    public function grid(Request $request) {
        return datatables(
                        DB::table('sub_categories')
                                ->leftJoin('categories', 'categories.id', '=', 'sub_categories.category_id')
                                ->leftJoin('users AS u2', 'sub_categories.created_by', '=', 'u2.id')
                                ->leftJoin('users AS u3', 'sub_categories.updated_by', '=', 'u3.id')
                                ->select([
                                    "sub_categories.id",
                                    "sub_category",
                                    "sub_categories.category_id",
                                    "category",
                                    DB::raw("DATE_FORMAT(sub_categories.created_at,'%d-%m-%Y') AS created_at"),
                                    DB::raw("DATE_FORMAT(sub_categories.updated_at,'%d-%m-%Y') AS updated_at"),
                                    DB::raw("CONCAT(u2.first_name,' ',u2.last_name) AS created_by"),
                                    DB::raw("CONCAT(u3.first_name,' ',u3.last_name) AS updated_by"),
                                    DB::raw("(sub_categories.id +1) AS action")
                                ])->orderBy('sub_category', 'ASC')->groupBy(['sub_categories.id']))->toJson();
    }
    /**
     * Get sub categories by category id
     * @param type $category_id
     * @return JSON
     */
    public function getSubCategories($category_id){
        $subCategories = "";
        if($category_id == "0") {
            $subCategories = SubCategory::select(['id', 'sub_category'])
                ->where('status', '=', ACTIVE)
                ->get();
        }else{
            $subCategories = SubCategory::select(['id', 'sub_category'])
                ->where('status', '=', ACTIVE)
                ->where('category_id', '=', $category_id)
                ->get();
        }
        
        return response()->json($subCategories);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'category_id' => 'required|integer',
                    'sub_category' => 'required|max:50',
                        ]
        );

        if ($validator->passes()) {

            $result = true;

            DB::transaction(function () use ($request, $result) {
                try {

                    $subCategory = new SubCategory();
                    $subCategory->category_id = $request->category_id;
                    $subCategory->sub_category = ucwords($request->sub_category);
                    $subCategory->created_by = Auth::user()->id;
                    $subCategory->created_at = Carbon::now();

                    $subCategory->save();
                } catch (\Exception $e) {
                    $result = false;
                }
            });

            if ($result) {
                return response()->json(array("type" => "success", "text" => "Created sub category successfully"), 200);
            } else {
                return response()->json(array("type" => "error", "text" => "Creating sub category failed!"), 200);
            }
        } else {

            if ($request->ajax()) {
                return response()->json(array(
                            'success' => false,
                            'message' => 'There are incorect values in the form!',
                            'errors' => $validator->getMessageBag()->toArray()
                                ), 422);
            }

            $this->throwValidationException(
                    $request, $validator
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
                    'category_id' => 'required|integer',
                    'sub_category' => 'required|max:50',
                        ]
        );

        if ($validator->passes()) {

            $result = true;

            DB::transaction(function () use ($id, $request, $result) {
                try {
                    $subCategory = SubCategory::find($id);
                    $subCategory->category_id = $request->category_id;
                    $subCategory->sub_category = ucwords($request->sub_category);
                    $subCategory->updated_by = Auth::user()->id;
                    $subCategory->updated_at = Carbon::now();
                    $subCategory->save();
                } catch (\Exception $e) {
                    $result = false;
                }
            });

            if ($result) {
                return response()->json(array("type" => "success", "text" => "Updated sub category successfully"), 200);
            } else {
                return response()->json(array("type" => "error", "text" => "Updating sub category failed"), 200);
            }
        } else {

            if ($request->ajax()) {
                return response()->json(array(
                            'success' => false,
                            'message' => 'There are incorrect values in the form!',
                            'errors' => $validator->getMessageBag()->toArray()
                                ), 422);
            }

            $this->throwValidationException(
                    $request, $validator
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        
    }

}
