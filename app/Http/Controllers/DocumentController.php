<?php

namespace App\Http\Controllers;

use App\User;
use App\Document;
use App\Category;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DocumentController extends Controller {

    protected $title;

    public function __construct() {
        $this->middleware(['auth']);
        $this->title = "Documents";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $title = $this->title;
        $categories = Category::select(['id', 'category'])->where('status', '=', ACTIVE)->get();

        return view('documents.index', compact('title', 'categories'));
    }

    /**
     * Get datatables grid data
     * @param Request $request
     * @return type
     */
    public function grid(Request $request) {
        return datatables(
                        DB::table('documents')
                                ->leftJoin('categories', 'documents.category_id', '=', 'categories.id')
                                ->leftJoin('sub_categories', 'documents.sub_category_id', '=', 'sub_categories.id')
                                ->leftJoin('users AS u2', 'documents.created_by', '=', 'u2.id')
                                ->leftJoin('users AS u3', 'documents.updated_by', '=', 'u3.id')
                                ->leftJoin('document_statuses', 'documents.document_status_id', '=', 'document_statuses.id')
                                ->select([
                                    "documents.id",
                                    "filename",
                                    "description",
                                    "documents.category_id",
                                    "category",
                                    "sub_category_id",
                                    "sub_category",
                                    "file_path",
                                    "document_status_id",
                                    "document_statuses.status",
                                    DB::raw("CONCAT(u2.first_name,' ',u2.last_name) AS created_by"),
                                    DB::raw("DATE_FORMAT(documents.created_at,'%d-%m-%Y') AS created_at"),
                                    DB::raw("CONCAT(u3.first_name,' ',u3.last_name) AS updated_by"),
                                    DB::raw("DATE_FORMAT(documents.updated_at,'%d-%m-%Y') AS updated_at"),
                                    DB::raw("(documents.id +1) AS action")
                                ])->orderBy('documents.id', 'DESC')->groupBy(['documents.id']))->toJson();
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
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        //dd($request);
        $validator = Validator::make($request->all(), [
                    'category_id' => 'required|integer',
                    'sub_category_id' => 'required|integer',
                    'filename' => 'required'
                        ]
        );

        if ($validator->passes()) {

            $time = Carbon::now();

            $image = $request->file('documents');

            $extension = $image->getClientOriginalExtension();

            $filename = $image->getClientOriginalName() . str_random(5) . date_format($time, 'd') . "_" . rand(1, 9) . "_" . date_format($time, 'h') . "." . $extension;

            $uploadSuccess = $image->move(public_path('uploads/documents'), $filename);

            if ($uploadSuccess) {
                $result = true;

                $filePath = '/' . $filename;

                DB::transaction(function () use ($request, $result, $filePath) {
                    try {

                        $document = new Document();
                        $document->category_id = $request->category_id;
                        $document->sub_category_id = $request->sub_category_id;
                        $document->filename = $request->filename;
                        $document->file_path = $filePath;
                        $document->created_by = Auth::user()->id;
                        $document->created_at = Carbon::now();
                        $document->save();
                        //dd($document);
                    } catch (\Exception $e) {
                        $result = false;
                    }
                });

                if ($result) {
                    return response()->json(array("type" => "success", "text" => "Document added successfully"), 200);
                } else {
                    return response()->json(array("type" => "error", "text" => "Adding document failed"), 200);
                }
            } else {
                return response()->json('error', 400);
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {

        return view('documents.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {

        $validator = Validator::make($request->all(), [
                    'category_id' => 'required|integer',
                    'sub_category_id' => 'required|integer',
                    'filename' => 'required'
                        ]
        );

        if ($validator->passes()) {

            $time = Carbon::now();

            $image = $request->file('documents');

            $extension = $image->getClientOriginalExtension();

            $filename = $image->getClientOriginalName() . str_random(5) . date_format($time, 'd') . "_" . rand(1, 9) . "_" . date_format($time, 'h') . "." . $extension;

            $uploadSuccess = $image->move(public_path('uploads/documents'), $filename);

            if ($uploadSuccess) {
                $result = true;

                $filePath = '/' . $filename;

                DB::transaction(function () use ($id,$request, $result, $filePath) {
                    try {

                        $document = Document::find($id);
                        $document->category_id = $request->category_id;
                        $document->sub_category_id = $request->sub_category_id;
                        $document->filename = $request->filename;
                        $document->file_path = $filePath;
                        $document->updated_by = Auth::user()->id;
                        $document->updated_at = Carbon::now();
                        $document->save();
                        
                    } catch (\Exception $e) {
                        $result = false;
                    }
                });

                if ($result) {
                    return response()->json(array("type" => "success", "text" => "Updated document successfully"), 200);
                } else {
                    return response()->json(array("type" => "error", "text" => "Updating document failed"), 200);
                }
                
            } else {
                return response()->json('error', 400);
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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id) {
        $role = Document::findOrFail($id);
        $role->delete();

        return redirect()->route('documents.index')
                        ->with('flash_message', 'Role deleted!');
    }

}
