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
    CRUDBooster::redirect('/admin/map','');
  //  return view('welcome');
});
// Admin
Route::get('admin/lang/{locale}', 'LocalizationController@index')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::post('/admin/import-pond-states-completed','AdminImportLogsController@getImportDone')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('import-pond-states-completed');
Route::get('/admin/import-pond-states-completed', function () {
        CRUDBooster::redirect('/admin/import_logs','');
        //  return view('welcome');
});
//Frontend Map and Pond
Route::get('admin/map', 'Frontend\PondController@viewFarmMap')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/pond/setting', 'Frontend\PondController@pondSetting')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/farm/settingByFarm', 'Frontend\FarmController@settingByFarm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/viewPond','Frontend\PondController@viewPond')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('viewPond');
Route::get('admin/getCurrentEbiAquacultureOfPond','Frontend\PondController@getCurrentEbiAquacultureOfPond')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/viewShrimpFeed','Frontend\FeedCumulativeController@feedingGraph')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('viewShrimpFeed');
Route::get('admin/tags','Frontend\TagsController@viewTags')
        ->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')
        ->name('getViewTags');

Route::post('admin/savetags','Frontend\TagsController@saveTags')
        ->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')
        ->name('postSaveTags');
// ajax
Route::get('admin/myFarm', 'Frontend\PondController@listMyFarm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/listPondByFarm', 'Frontend\PondController@listPondByFarm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/listPondByFarmMap', 'Frontend\PondController@listPondByFarmMap')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/listAllPonds', 'Frontend\PondController@listAllPonds')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');

//Frontend Water
Route::get('/admin/viewWaterQuality','Frontend\WaterQualityController@viewWaterQuality')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('viewWaterQuality');
Route::get('/admin/viewQualityDetail','Frontend\WaterQualityController@viewWaterQualityDetail')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('viewWaterQualityDetail');
// ajax
Route::get('admin/getWaterStatus', 'Frontend\WaterQualityController@getWaterStatusOfPondByFarm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('/admin/water-criterias/values.json','Frontend\WaterQualityController@getAllWaterQualityCriteriaValues')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('/admin/water-criteria/values.json','Frontend\WaterQualityController@getWaterQualityCriteriaValues')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::post('/admin/water-criteria/confirm-alert','Frontend\WaterQualityController@confirmWaterQualityCriteriaAlert')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('ajaxConfirmAlert');
Route::post('/admin/water-criterias','Frontend\WaterQualityController@getWaterCriteriasByPond')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::post('/admin/farm/create-farm','Frontend\FarmController@createFarm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('ajaxCreateFarm');
Route::post('/admin/farm/update-farm','Frontend\FarmController@updateFarm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('ajaxUpdateFarm');
Route::post('/admin/farm/delete-farm','Frontend\FarmController@deleteFarm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('ajaxDeleteFarm');
Route::get('/admin/getFarmLastAdd', 'Frontend\FarmController@getFarmLastAdd')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('getFarmLastAdd');
Route::post('/admin/addPondsByFarms','Frontend\FarmController@addPondsByFarms')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('addPondsByFarms');

Route::post('/admin/price-save','AdminEbiPriceController@postPriceSave')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('postPriceSave');
Route::post('/admin/post-aquaculture-method','AdminAquacultureMethodController@postAquacultureMethod')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('postAquacultureMethod');
Route::post('/admin/post-kind','AdminEbiKindController@postEbiKind')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('postEbiKind');
Route::post('/admin/feed_price','AdminFeedPriceController@postFeedPrice')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('postFeedPrice');

//Frontend Shrimp
Route::get('/admin/viewShrimpMeasure','Frontend\ShrimpController@viewShrimpMeasure')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('getShrimpManager');// もともとこれもついていた->name('viewShrimpMeasure')
Route::get('/admin/shrimpMigration','Frontend\PondAquaculturesController@shrimpMigrationGet')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('shrimpMigrationGet');
Route::post('/admin/shrimpMigration','Frontend\PondAquaculturesController@shrimpMigrationRegistration')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('shrimpMigrationRegistration');

Route::post('/admin/getWeatherInfo','Frontend\FrontendBaseController@getWeatherInfo')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('getWeatherInfo');

//Frontend Feeding
Route::post('/admin/feeding-cumulatives','Frontend\FeedCumulativeController@getCumulativesByPond')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');

// Import
Route::get('/admin/import_bait','AdminImportLogsController@getImportBait')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('getImportBait');
Route::get('/admin/import_drug','AdminImportLogsController@getImportDrug')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('getImportDrug');

Route::get('/admin/import_bait_completed', function () {
    CRUDBooster::redirect('/admin/import_bait','');
    //  return view('welcome');
});
Route::get('/admin/import-drug-completed', function () {
    CRUDBooster::redirect('/admin/import_drug','');
    //  return view('welcome');
});
Route::post('/admin/import_bait_completed','AdminImportLogsController@getImportBaitDone')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('import_bait_completed');
Route::post('/admin/import_drug_completed','AdminImportLogsController@getImportDrugDone')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('import_drug_completed');

Route::get('admin/aq', 'AdominReportController@farm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::post('/admin/aq','AdminAquaculturesController@post')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');


//収支
Route::get('admin/all', 'AdominReportController@all')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::post('admin/all','AdominReportController@all')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/report_farm', 'AdominReportController@farm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('report_farm');
Route::post('admin/report_farm','AdominReportController@farm')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/report_pond', 'AdominReportController@pond')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('report_pond');
Route::post('admin/report_pond','AdominReportController@pond')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');

//履歴検索
Route::get('admin/report', 'AdminCulutureController@pond')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('report');
Route::post('admin/report','AdminCulutureController@pond')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/shipment_session','AdminCulutureController@shipment_report')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('shipment_report');
Route::post('admin/shipment_session','AdminCulutureController@shipment_report')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
Route::get('admin/total','AdminCulutureController@total')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('total');
Route::post('admin/total','AdminCulutureController@total')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');

Route::get('admin/simulation', 'SimulationController@index')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('simulation');
Route::post('admin/simulation','SimulationController@calculation')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
//費用登録
Route::get('admin/cost_add', 'AdominReportController@cost')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('costadd');
Route::post('admin/cost_add','AdominReportController@costadd')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
//売上登録
Route::get('admin/sell_add', 'AdominReportController@sell')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('selladd');
Route::post('admin/sell_add','AdominReportController@selladd')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend');
//冷凍ストック登録
Route::get('admin/cold', 'AdominReportController@cold_stock')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('cold');
Route::post('admin/cold_stock_add','AdominReportController@cold_stock_add')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('cold_stock_add');
//冷凍在庫一覧
Route::get('admin/cold_stock_list', 'AdominReportController@cold_stock_list')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('cold_stock_list');
//冷凍在庫販売
Route::get('admin/cold_job', 'AdominReportController@cold_job')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('cold_job');
Route::post('admin/cold_job_add', 'AdominReportController@cold_job_add')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('cold_job_add');
//月間レポート
Route::get('admin/month_report', 'AdminMonthreport@month_report')->middleware('\crocodicstudio\crudbooster\middlewares\CBBackend')->name('month_report');