<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Auth;
use Spatie\Permission\Models\Role;
use App\Permission;
use App\Module;
use Session;
use App\Http\Controllers\Controller;
use DB;
use Validator;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class PermissionController extends Controller {

    public function __construct() {
        //$this->middleware(['auth', 'isAdmin']); //isAdmin middleware lets only users with a //specific permission permission to access these resources
        $this->middleware(['auth']);
        $this->title = "Permissions";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $modules = Module::all();
        $title = $this->title;

        return view('permissions.index', compact('modules','title'));
    }

    /**
     * Get datatables grid data
     * @param Request $request
     * @return type
     */
    public function grid(Request $request) {
        return datatables(
                        DB::table('permissions')
                                ->leftJoin('users AS u2', 'permissions.created_by', '=', 'u2.id')
                                ->leftJoin('users AS u3', 'permissions.updated_by', '=', 'u3.id')
                                ->leftJoin('modules', 'permissions.module_id', '=', 'modules.id')
                                ->select([
                                    "permissions.id",
                                    "module_id",
                                    "module",
                                    "permissions.name",
                                    DB::raw("DATE_FORMAT(permissions.created_at,'%d-%m-%Y') AS created_at"),
                                    DB::raw("DATE_FORMAT(permissions.updated_at,'%d-%m-%Y') AS updated_at"),
                                    DB::raw("CONCAT(u2.first_name,' ',u2.last_name) AS created_by"),
                                    DB::raw("CONCAT(u3.first_name,' ',u3.last_name) AS updated_by"),
                                    DB::raw("(permissions.id +1) AS action")
                                ])->orderBy('module', 'ASC')->groupBy(['permissions.id']))->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $roles = Role::get(); //Get all roles

        return view('permissions.create')->with('roles', $roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
                    'module_id' => 'required|integer',
                    'name' => 'required|unique:permissions|max:40',
                        ]
        );

        if ($validator->passes()) {

            $result = true;

            DB::transaction(function () use ($request, $result) {
                try {

                    $permission = new Permission();
                    $permission->module_id = $request->module_id;
                    $permission->name = strtolower($request->name);
                    $permission->created_by = Auth::user()->id;
                    $permission->created_at = Carbon::now();

                    $permission->save();
                } catch (\Exception $e) {
                    $result = false;
                }
            });

            if ($result) {
                return response()->json(array("type" => "success", "text" => "Created permission successfully"), 200);
            } else {
                return response()->json(array("type" => "error", "text" => "Creating permission failed!"), 200);
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

        /* if (!empty($request['roles'])) { //If one or more role is selected
          foreach ($roles as $role) {
          $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record

          $permission = Permission::where('name', '=', $name)->first(); //Match input //permission to db record
          $r->givePermissionTo($permission);
          }
          } */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('permissions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $permission = Permission::findOrFail($id);

        return view('permissions.edit', compact('permission'));
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
                    'module_id' => 'required|integer',
                    'name' => ['required', Rule::unique('permissions')->ignore($id)]
                        ]
        );

        if ($validator->passes()) {

            $result = true;

            DB::transaction(function () use ($id, $request, $result) {
                try {
                    $permission = Permission::find($id);
                    $permission->module_id = $request->module_id;
                    $permission->name = strtolower($request->name);
                    $permission->updated_by = Auth::user()->id;
                    $permission->updated_at = Carbon::now();
                    $permission->save();
                } catch (\Exception $e) {
                    $result = false;
                }
            });

            if ($result) {
                return response()->json(array("type" => "success", "text" => "Updated role successfully"), 200);
            } else {
                return response()->json(array("type" => "error", "text" => "Updating role failed"), 200);
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
        $permission = Permission::findOrFail($id);

        //Make it impossible to delete this specific permission 
        if ($permission->name == "Administer roles & permissions") {
            return redirect()->route('permissions.index')
                            ->with('flash_message', 'Cannot delete this Permission!');
        }

        $permission->delete();

        return redirect()->route('permissions.index')
                        ->with('flash_message', 'Permission deleted!');
    }

}
