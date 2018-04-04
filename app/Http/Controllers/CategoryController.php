<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Category;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class CategoryController extends Controller {

    public function __construct() {
        $this->middleware(['auth']);
        $this->title = "Categories";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $title = $this->title;

        return view('categories.index', compact('title'));
    }

    /**
     * Get datatables grid data
     * @param Request $request
     * @return type
     */
    public function grid(Request $request) {
        return datatables(
                        DB::table('categories')
                                ->leftJoin('users AS u2', 'categories.created_by', '=', 'u2.id')
                                ->leftJoin('users AS u3', 'categories.updated_by', '=', 'u3.id')
                                ->select([
                                    "categories.id",
                                    "category",
                                    DB::raw("DATE_FORMAT(categories.created_at,'%d-%m-%Y') AS created_at"),
                                    DB::raw("DATE_FORMAT(categories.updated_at,'%d-%m-%Y') AS updated_at"),
                                    DB::raw("CONCAT(u2.first_name,' ',u2.last_name) AS created_by"),
                                    DB::raw("CONCAT(u3.first_name,' ',u3.last_name) AS updated_by"),
                                    DB::raw("(categories.id +1) AS action")
                                ])->orderBy('category', 'ASC')->groupBy(['categories.id']))->toJson();
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
                    'category' => 'required|unique:categories|max:50',
                        ]
        );

        if ($validator->passes()) {

            $result = true;

            DB::transaction(function () use ($request, $result) {
                try {

                    $category = new Category();
                    $category->category = ucwords($request->category);
                    $category->created_by = Auth::user()->id;
                    $category->created_at = Carbon::now();

                    $category->save();
                } catch (\Exception $e) {
                    $result = false;
                }
            });

            if ($result) {
                return response()->json(array("type" => "success", "text" => "Created category successfully"), 200);
            } else {
                return response()->json(array("type" => "error", "text" => "Creating category failed!"), 200);
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
                    'category' => ['required', Rule::unique('categories')->ignore($id)]
                        ]
        );

        if ($validator->passes()) {

            $result = true;

            DB::transaction(function () use ($id, $request, $result) {
                try {
                    $category = Category::find($id);
                    $category->category = ucwords($request->category);
                    $category->updated_by = Auth::user()->id;
                    $category->updated_at = Carbon::now();
                    $category->save();
                } catch (\Exception $e) {
                    $result = false;
                }
            });

            if ($result) {
                return response()->json(array("type" => "success", "text" => "Updated category successfully"), 200);
            } else {
                return response()->json(array("type" => "error", "text" => "Updating category failed"), 200);
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
