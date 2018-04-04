<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use Illuminate\Http\Request;

use Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Session;
use App\Http\Controllers\Controller;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{

    protected $title;

    public function __construct()
    {
        //$this->middleware(['auth', 'isAdmin']);//isAdmin middleware lets only users with a //specific permission permission to access these resources
        $this->middleware(['auth']);
        $this->title = "Roles";
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$roles = Role::all();//Get all roles
        $permissions = Permission::all();//Get all permissions
        $title = $this->title;
        //$permissions = Auth::user()->assignRole('root');
        //dd($permissions);
        //return view('roles.index', compact('title', 'roles', 'permissions'));
        return view('roles.index', compact('title', 'permissions'));
    }

    /**
     * Get datatables grid data
     * @param Request $request
     * @return type
     */
    public function grid(Request $request)
    {
        return datatables(
            DB::table('roles')
                ->leftJoin('role_has_permissions', 'role_has_permissions.role_id', '=', 'roles.id')
                ->leftJoin('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
                ->leftJoin('users AS u2', 'roles.created_by', '=', 'u2.id')
                ->leftJoin('users AS u3', 'roles.updated_by', '=', 'u3.id')
                ->select([
                    "roles.id",
                    "roles.name AS role",
                    "permissions.name AS permission",
                    DB::raw("DATE_FORMAT(roles.created_at,'%d-%m-%Y') AS created_at"),
                    DB::raw("DATE_FORMAT(roles.updated_at,'%d-%m-%Y') AS updated_at"),
                    DB::raw("CONCAT(u2.first_name,' ',u2.last_name) AS created_by"),
                    DB::raw("CONCAT(u3.first_name,' ',u3.last_name) AS updated_by"),
                    DB::raw("(roles.id +1) AS action")
                ])->orderBy('roles.name', 'DESC')->groupBy(['permissions.id', 'roles.id']))->toJson();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::all();//Get all permissions

        return view('roles.create', ['permissions' => $permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
                'name' => 'required|unique:roles|max:10',
                'permissions' => 'required',
            ]
        );

        if ($validator->passes()) {

            $result = true;

            DB::transaction(function () use ($request, $result) {
                try {

                    $name = $request['name'];
                    $role = new Role();
                    $role->name = $name;
                    $role->created_by = Auth::user()->id;
                    $role->created_at = Carbon::now();

                    $permissions = $request['permissions'];

                    $role->save();

                    foreach ($permissions as $permission) {
                        $p = Permission::where('id', '=', $permission)->firstOrFail();

                        $role = Role::where('name', '=', $name)->first();
                        $role->givePermissionTo($p);
                    }

                } catch (\Exception $e) {
                    $result = false;
                }
            });

            if ($result) {
                return response()->json(array("type" => "success", "text" => "Created Role Successfully"), 200);
            } else {
                return response()->json(array("type" => "error", "text" => "Creating Role Failed"), 200);
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
    public function show($id)
    {
        return redirect('roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $validator = Validator::make($request->all(), [
                'name' => ['required', Rule::unique('roles')->ignore($id)],
                'permissions' => 'required',
            ]
        );

        if ($validator->passes()) {

            $result = true;

            DB::transaction(function () use ($id,$request, $result) {
                try {
                    $name = $request['name'];
                    $role = Role::find($id);
                    $role->name = $name;
                    $role->updated_by = Auth::user()->id;
                    $role->updated_at = Carbon::now();
                    $role->save();

                    /*Remove all previous permissions*/
                    $rolePermissions = DB::table("role_has_permissions")->where("role_id",$id);
                    $rolePermissions->delete();


                    $permissions = $request['permissions'];

                    foreach ($permissions as $permission) {
                        $p = Permission::where('id', '=', $permission)->firstOrFail();

                        $role = Role::where('name', '=', $name)->first();
                        $role->givePermissionTo($p);
                    }

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
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return redirect()->route('roles.index')
            ->with('flash_message',
                'Role deleted!');

    }

    /**
     * Get all permissions for a particular role
     *
     * @param $roleId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllPermissions($roleId)
    {
        $allPermissions = Permission::all(["id", "name"]);
        $permissions = DB::table("role_has_permissions")
            ->select(["permission_id"])
            ->where("role_id", $roleId)->get();

        return response()->json(array("all_permissions" => $allPermissions, "roles_permissions" => $permissions), 200);

    }
}
