<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth/login');
});

Route::post('logout', 'AuthController@logout');

Route::resource('/users', 'Admin\UserController');
Route::get('users_grid', 'Admin\UserController@grid');
Route::post('/change_password', 'Admin\UserController@changePassword');
Route::get('/update_password', 'Admin\UserController@updatePassword');

Route::resource('/roles', 'Admin\RoleController');
Route::get('role_permissions/{roleId}', 'Admin\RoleController@getAllPermissions');
Route::get('roles_grid', 'Admin\RoleController@grid');
Route::resource('/roles/create', 'Admin\RoleController');

Route::resource('/permissions', 'Admin\PermissionController');
Route::get('permissions_grid', 'Admin\PermissionController@grid');

Route::resource('/documents', 'DocumentController');
Route::get('documents_grid', 'DocumentController@grid');

Route::resource('/letters', 'LetterController');
Route::get('letters_grid', 'LetterController@grid');

Route::resource('/memos', 'MemoController');
Route::get('memos_grid', 'MemoController@grid');

Route::resource('/housing', 'HousingController');
Route::get('housing_grid', 'HousingController@grid');

Route::resource('/lands', 'LandsController');
Route::get('lands_grid', 'LandsController@grid');

Route::resource('/planning', 'PlanningController');
Route::get('planning_grid', 'PlanningController@grid');

Route::resource('/administration', 'AdministrationController');
Route::get('administration_grid', 'AdministrationController@grid');

Route::resource('/categories', 'CategoryController');
Route::get('categories_grid', 'CategoryController@grid');

Route::resource('/sub_categories', 'SubCategoryController');
Route::get('sub_categories_grid', 'SubCategoryController@grid');
Route::get('get_sub_categories/{category_id}', 'SubCategoryController@getSubCategories');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
