<?php

use Illuminate\Support\Facades\Route;

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
    return view('auth.login');
});

//Route::post('/log_in', [App\Http\Controllers\Auth\LoginController::class, 'log_in'])->name('log_in');
Route::get('/sso', [App\Http\Controllers\Auth\SSOLoginController::class, 'attempSSOLogin'])->name('sso');

Route::get('/redirect', [App\Http\Controllers\Auth\SsoController::class, 'redirect'])->name('redirect');
Route::get('/callback', [App\Http\Controllers\Auth\SsoController::class, 'callback'])->name('callback');
Route::get('/login', [App\Http\Controllers\Auth\SsoController::class, 'login'])->name('sso.login');

//Route::get('/newUser', [App\Http\Controllers\FrontController::class, 'newUser'])->name('newUser');
//Route::post('/save_new_user', [App\Http\Controllers\FrontController::class, 'save_new_user'])->name('save_new_user');
Route::get('/getDepartments/{id}', [App\Http\Controllers\FrontController::class, 'getDepartments'])->name('getDepartments');
Route::get('/getUser/{id}', [App\Http\Controllers\FrontController::class, 'getUser'])->name('getUser');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');

Auth::routes();

// Route::group(['middleware' => 'web'], function(){

//     Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// });
Route::group(['middleware'=>['auth','is_admin']], function () {
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/getDataWidget', [App\Http\Controllers\DashboardController::class, 'getDataWidget'])->name('getDataWidget');
    
    /*************************************************** Pentadbiran Sistem ***************************************************/
    // Pengguna
    Route::get('/user', [App\Http\Controllers\UserController::class, 'index'])->name('user');
    Route::get('/profile', [App\Http\Controllers\UserController::class, 'profile'])->name('profile');
    Route::post('/user', [App\Http\Controllers\UserController::class, 'store'])->name('save_user');
    Route::get('/user/create', [App\Http\Controllers\UserController::class, 'create'])->name('new_user');
    Route::get('/user/{id}', [App\Http\Controllers\UserController::class, 'show'])->name('show_user');
    Route::get('/user/{id}/edit', [App\Http\Controllers\UserController::class, 'edit'])->name('edit_user');
    Route::put('/user/{id}', [App\Http\Controllers\UserController::class, 'update'])->name('upd_user');
    Route::put('/profile/{id}', [App\Http\Controllers\UserController::class, 'update_profile'])->name('update_profile');
    Route::put('/update_password/{id}', [App\Http\Controllers\UserController::class, 'update_password'])->name('update_password');
    Route::put('/user_reset/', [App\Http\Controllers\UserController::class, 'reset'])->name('user_reset');
    Route::put('/user_del/', [App\Http\Controllers\UserController::class, 'delete'])->name('user_del');
    Route::put('/activate_user/', [App\Http\Controllers\UserController::class, 'activate'])->name('activate_user');

    // Pasukan
    Route::get('/unit', [App\Http\Controllers\UnitController::class, 'index'])->name('unit');
    Route::post('/add_unit', [App\Http\Controllers\UnitController::class, 'store'])->name('add_unit');
    Route::get('/get_Unit/{id}', [App\Http\Controllers\UnitController::class, 'get_Unit'])->name('get_Unit');
    Route::post('/update_unit', [App\Http\Controllers\UnitController::class, 'update'])->name('update_unit');
    Route::post('/delete_unit', [App\Http\Controllers\UnitController::class, 'delete'])->name('delete_unit');

    // pembekal
    Route::get('/pembekal', [App\Http\Controllers\PembekalController::class, 'index'])->name('pembekal');
    Route::post('/add_pembekal', [App\Http\Controllers\PembekalController::class, 'store'])->name('add_pembekal');
    Route::get('/getPembekal/{id}', [App\Http\Controllers\PembekalController::class, 'getPembekal'])->name('getpembekal');
    Route::post('/update_pembekal', [App\Http\Controllers\PembekalController::class, 'update'])->name('update_pembekal');
    Route::post('/delete_pembekal', [App\Http\Controllers\PembekalController::class, 'delete'])->name('delete_pembekal');
    Route::put('/activate_pembekal/', [App\Http\Controllers\PembekalController::class, 'activate_pembekal'])->name('activate_pembekal');

    // Kategori
    Route::get('/category', [App\Http\Controllers\CategoryController::class, 'index'])->name('category');
    Route::post('/add_category', [App\Http\Controllers\CategoryController::class, 'store'])->name('add_category');
    Route::get('/getCategory/{id}', [App\Http\Controllers\CategoryController::class, 'getCategory'])->name('getcategory');
    Route::get('/getUnit/{id}', [App\Http\Controllers\CategoryController::class, 'getUnit'])->name('getUnit');
    Route::post('/update_category', [App\Http\Controllers\CategoryController::class, 'update'])->name('update_category');
    Route::post('/delete_category', [App\Http\Controllers\CategoryController::class, 'delete'])->name('delete_category');

    // Sub-kategori
    Route::get('/sub-category', [App\Http\Controllers\SubCategoryController::class, 'index'])->name('sub-category');
    Route::post('/add_subcategory', [App\Http\Controllers\SubCategoryController::class, 'store'])->name('add_subcategory');
    Route::get('/getSubCategory/{id}', [App\Http\Controllers\SubCategoryController::class, 'getSubCategory'])->name('getSubCategory');
    Route::get('/getSubCat/{id}', [App\Http\Controllers\SubCategoryController::class, 'getSubCat'])->name('getSubCat');
    Route::post('/update_subcategory', [App\Http\Controllers\SubCategoryController::class, 'update'])->name('update_subcategory');
    Route::post('/delete_subcategory', [App\Http\Controllers\SubCategoryController::class, 'delete'])->name('delete_subcategory');

    // Soalan Lazim
    Route::get('/faq', [App\Http\Controllers\FaqController::class, 'index'])->name('faq');
    Route::post('/add_faq', [App\Http\Controllers\FaqController::class, 'store'])->name('add_faq');
    Route::get('/getfaq/{id}', [App\Http\Controllers\FaqController::class, 'getfaq'])->name('getfaq');
    Route::post('/update_faq', [App\Http\Controllers\FaqController::class, 'update'])->name('update_faq');
    Route::post('/delete_faq', [App\Http\Controllers\FaqController::class, 'delete'])->name('delete_faq');

    // Cuti Umum
    Route::get('/holidays', [App\Http\Controllers\HolidayController::class, 'index'])->name('holidays');

    // Perincian
    Route::get('/detail', [App\Http\Controllers\DetailController::class, 'index'])->name('detail');
    Route::post('/add_detail', [App\Http\Controllers\DetailController::class, 'store'])->name('add_detail');
    Route::get('/get_Detail/{id}', [App\Http\Controllers\DetailController::class, 'get_Detail'])->name('get_Detail');
    Route::get('/getDetails/{id}', [App\Http\Controllers\DetailController::class, 'getDetails'])->name('getDetails');
    Route::post('/update_detail', [App\Http\Controllers\DetailController::class, 'update'])->name('update_detail');
    Route::post('/delete_detail', [App\Http\Controllers\DetailController::class, 'delete'])->name('delete_detail');
    Route::get('/getSubCat/{id}', [App\Http\Controllers\DetailController::class, 'getSubCat'])->name('getSubCat');

    // Sektor
    // Route::get('/sector', [App\Http\Controllers\ComplaintlistController::class, 'index'])->name('sector');

    // Bahagian
    // Route::get('/department', [App\Http\Controllers\ComplaintlistController::class, 'index'])->name('department');
    Route::get('/get_Departments/{id}', [App\Http\Controllers\DepartmentController::class, 'get_Departments'])->name('get_Departments');
    /*************************************************** !end Pentadbiran Sistem ***************************************************/

    /*************************************************** Senarai Aduan ***************************************************/
    // Arkib
    Route::get('/ontask', [App\Http\Controllers\ComplaintlistController::class, 'ontask'])->name('ontask');

    // Senarai Aduan
    Route::post('/ajaxComplaint', [App\Http\Controllers\ComplaintlistController::class, 'ajaxComplaint'])->name('ajaxComplaint');
    Route::get('/complaintlist', [App\Http\Controllers\ComplaintlistController::class, 'index'])->name('complaintlist');
    Route::get('/complaintlist/{id}', [App\Http\Controllers\ComplaintlistController::class, 'show'])->name('show');
    Route::get('/show_technical/{id}', [App\Http\Controllers\ComplaintlistController::class, 'show_technical'])->name('show_technical');
    Route::get('/show_coordinator/{id}', [App\Http\Controllers\ComplaintlistController::class, 'show_coordinator'])->name('show_coordinator');
    Route::get('/show_verify/{id}', [App\Http\Controllers\ComplaintlistController::class, 'show_verify'])->name('show_verify');
    Route::get('/show_officer/{id}', [App\Http\Controllers\ComplaintlistController::class, 'show_officer'])->name('show_officer');
    Route::get('/show_disabled/{id}', [App\Http\Controllers\ComplaintlistController::class, 'show_disabled'])->name('show_disabled');
    Route::get('/show_done/{id}', [App\Http\Controllers\ComplaintlistController::class, 'show_done'])->name('show_done');
    Route::post('/update_complaint', [App\Http\Controllers\ComplaintlistController::class, 'update_complaint'])->name('update_complaint');
    Route::post('/update_complaint_coordinator', [App\Http\Controllers\ComplaintlistController::class, 'update_complaint_coordinator'])->name('update_complaint_coordinator');
    Route::post('/update_complaint_technical', [App\Http\Controllers\ComplaintlistController::class, 'update_complaint_technical'])->name('update_complaint_technical');
    Route::post('/update_complaint_verify', [App\Http\Controllers\ComplaintlistController::class, 'update_complaint_verify'])->name('update_complaint_verify');
    Route::post('/kpp_update_complaint', [App\Http\Controllers\ComplaintlistController::class, 'kpp_update_complaint'])->name('kpp_update_complaint');
    Route::post('/officer_update_complaint', [App\Http\Controllers\ComplaintlistController::class, 'officer_update_complaint'])->name('officer_update_complaint');
    Route::get('/getOfficers', [App\Http\Controllers\ComplaintlistController::class, 'getOfficers'])->name('getOfficers');
    Route::post('/complaintDelete', [App\Http\Controllers\ComplaintlistController::class, 'delete'])->name('complaintDelete');
    Route::post('/auditPDF', [App\Http\Controllers\ComplaintlistController::class, 'auditPDF'])->name('auditPDF');
    Route::get('/logPDF/{id}', [App\Http\Controllers\ComplaintlistController::class, 'logPDF'])->name('logPDF');
    Route::get('/getRemark/{id}', [App\Http\Controllers\ComplaintlistController::class, 'getRemark'])->name('getRemark');
    Route::get('/getTimeline/{id}', [App\Http\Controllers\ComplaintlistController::class, 'getTimeline'])->name('getTimeline');
    Route::post('/delRemark', [App\Http\Controllers\ComplaintlistController::class, 'delRemark'])->name('delRemark');
    Route::post('/assigntask', [App\Http\Controllers\ComplaintlistController::class, 'assigntask'])->name('assigntask');
    Route::post('/send_emel', [App\Http\Controllers\ComplaintlistController::class, 'send_emel'])->name('send_emel');
    Route::post('/updRemark', [App\Http\Controllers\ComplaintlistController::class, 'updRemark'])->name('updRemark');
    Route::post('/getComplaint', [App\Http\Controllers\ComplaintlistController::class, 'getComplaint'])->name('getComplaint');

    // Senarai Mesyuarat
    Route::get('/meetinglist', [App\Http\Controllers\MeetingController::class, 'index'])->name('meetinglist');
    Route::get('/edit_meeting/{id}', [App\Http\Controllers\MeetingController::class, 'edit'])->name('edit_meeting');
    Route::post('/update_meeting', [App\Http\Controllers\MeetingController::class, 'update_meeting'])->name('update_meeting');
    Route::get('/show_meeting/{id}', [App\Http\Controllers\MeetingController::class, 'show'])->name('show_meeting');
    Route::post('verify', [App\Http\Controllers\MeetingController::class, 'verify'])->name('verify');

    // Aduan Baru
    Route::get('/newcomplaint', [App\Http\Controllers\NewComplaintController::class, 'index'])->name('newcomplaint');
    Route::post('/newcomplaint', [App\Http\Controllers\NewComplaintController::class, 'store'])->name('save_newcomplaint');
    Route::get('/getStaff/{id}', [App\Http\Controllers\NewComplaintController::class, 'getStaff'])->name('getStaff');

    // Arkib
    Route::get('/archive', [App\Http\Controllers\ArchiveController::class, 'index'])->name('archive');
    Route::get('/archive/{id}', [App\Http\Controllers\ArchiveController::class, 'show'])->name('show_archive');
    Route::get('getArchive', [App\Http\Controllers\ArchiveController::class, 'getArchive'])->name('getArchive');
    /*************************************************** !end Senarai Aduan ***************************************************/

    /*************************************************** Senarai Aduan ***************************************************/
    // Senarai Pelaksana
    Route::get('/task', [App\Http\Controllers\TaskController::class, 'index'])->name('task');
    /*************************************************** !end Senarai Aduan ***************************************************/

    /*************************************************** Laporan ***************************************************/
    Route::get('/category_report', [App\Http\Controllers\ReportController::class, 'category_report'])->name('category_report');
    Route::get('/staff_detail', [App\Http\Controllers\ReportController::class, 'staff_detail_report'])->name('staff_detail');
    Route::get('/staff_stat', [App\Http\Controllers\ReportController::class, 'staff_stat_report'])->name('staff_stat');
    Route::get('/staff_kpi', [App\Http\Controllers\ReportController::class, 'staff_kpi_report'])->name('staff_kpi');
    Route::get('/categoryPDF/{sDate}/{eDate}/{cat}', [App\Http\Controllers\ReportController::class, 'categoryPDF'])->name('categoryPDF');
    Route::get('/staffStatPDF/{sDate}/{eDate}/{staff}/{section}', [App\Http\Controllers\ReportController::class, 'staffStatPDF'])->name('staffStatPDF');
    Route::get('/staffKpiPDF/{sDate}/{eDate}/{staff}', [App\Http\Controllers\ReportController::class, 'staffKpiPDF'])->name('staffKpiPDF');
    Route::get('/staffDetailPDF/{sDate}/{eDate}/{staff}/{status}/{kpi}', [App\Http\Controllers\ReportController::class, 'staffDetailPDF'])->name('staffDetailPDF');
    Route::get('/staffDetailExcel/{sDate}/{eDate}/{staff}/{status}/{kpi}', [App\Http\Controllers\ReportController::class, 'staffDetailExcel'])->name('staffDetailExcel');
    Route::get('/staffKpiExcel/{sDate}/{eDate}/{staff}', [App\Http\Controllers\ReportController::class, 'staffKpiExcel'])->name('staffKpiExcel');
    Route::get('/staffStatExcel/{sDate}/{eDate}/{staff}/{section}', [App\Http\Controllers\ReportController::class, 'staffStatExcel'])->name('staffStatExcel');
    Route::get('/categoryExcel/{sDate}/{eDate}/{cat}', [App\Http\Controllers\ReportController::class, 'categoryExcel'])->name('categoryExcel');
    Route::get('/get_Staff/{id}', [App\Http\Controllers\ReportController::class, 'get_Staff'])->name('get_Staff');
    Route::get('/getUlasan/{id}', [App\Http\Controllers\ReportController::class, 'getUlasan'])->name('getUlasan');
    Route::get('/getThreeDays', [App\Http\Controllers\ReportController::class, 'getThreeDays'])->name('getThreeDays');
    Route::get('/getFiveDays', [App\Http\Controllers\ReportController::class, 'getFiveDays'])->name('getFiveDays');
    /*************************************************** !end Laporan ***************************************************/

    // Arkib
    Route::get('/audit', [App\Http\Controllers\AuditController::class, 'index'])->name('audit');
    Route::get('getAudit', [App\Http\Controllers\AuditController::class, 'getAudit'])->name('getAudit');
    /*************************************************** !end Senarai Aduan ***************************************************/
});

Route::group(['middleware'=>['auth','is_user']], function (){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/user/profile', [App\Http\Controllers\HomeController::class, 'profile'])->name('user.profile');
    Route::get('new_complaint', [App\Http\Controllers\HomeController::class, 'new_complaint'])->name('new_complaint');
    Route::post('save_complaint', [App\Http\Controllers\HomeController::class, 'save_complaint'])->name('save_complaint');
    Route::get('show_complaint/{id}', [App\Http\Controllers\HomeController::class, 'show_complaint'])->name('show_complaint');
    Route::get('edit_complaint/{id}', [App\Http\Controllers\HomeController::class, 'edit_complaint'])->name('edit_complaint');
    Route::post('upd_complaint', [App\Http\Controllers\HomeController::class, 'upd_complaint'])->name('upd_complaint');
    Route::post('del_complaint', [App\Http\Controllers\HomeController::class, 'del_complaint'])->name('del_complaint');
    Route::post('add_file', [App\Http\Controllers\HomeController::class, 'add_file'])->name('add_file');
    Route::get('del_file/{id}', [App\Http\Controllers\HomeController::class, 'del_file'])->name('del_file');
    Route::get('faq_list', [App\Http\Controllers\HomeController::class, 'faq'])->name('faq_list');
    Route::get('user_profile', [App\Http\Controllers\HomeController::class, 'user_profile'])->name('user_profile');
    Route::put('upd_profile/{id}', [App\Http\Controllers\HomeController::class, 'upd_profile'])->name('upd_profile');
    Route::put('upd_password/{id}', [App\Http\Controllers\HomeController::class, 'upd_password'])->name('upd_password');
    Route::post('verified', [App\Http\Controllers\HomeController::class, 'verified'])->name('verified');
    Route::post('unverified', [App\Http\Controllers\HomeController::class, 'unverified'])->name('unverified');
    Route::get('getSubCatUser/{id}', [App\Http\Controllers\HomeController::class, 'getSubCatUser'])->name('getSubCatUser');
    //Route::get('/user/complaintlist', [App\Http\Controllers\HomeController::class, 'complaintlist'])->name('user.complaintlist');
});
