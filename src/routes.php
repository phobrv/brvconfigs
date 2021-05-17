<?php

Route::middleware(['web', 'auth', 'auth:sanctum', 'lang', 'verified'])->namespace('Phobrv\BrvConfigs\Controllers')->group(function () {
	Route::middleware(['can:config_manage'])->prefix('admin')->group(function () {
		Route::get('/config-sidebar', 'ConfigController@sidebar')->name('config.sidebar');
		Route::post('/config/updateSidebarConfig', 'ConfigController@updateSidebarConfig')->name('config.updateSidebarConfig');

		Route::get('/config-system', 'ConfigController@system')->name('config.system');
		Route::get('/config-website', 'ConfigController@website')->name('config.website');
		Route::get('/config-widget', 'ConfigController@widget')->name('config.widget');
		Route::post('/config/update', 'ConfigController@update')->name('config.update');

		Route::post('/config-website/maintenanceWebsite', 'ConfigAPIController@maintenanceWebsite')->name('config.maintenanceWebsite');
		Route::post('/configAPI/update', 'ConfigAPIController@update')->name('configAPI.update');
		Route::post('/configAPI/uploadFile', 'ConfigAPIController@uploadFile')->name('configAPI.uploadFile');
		Route::get('/config-icon', 'ConfigController@showIcon')->name('config.showIcon');

		Route::resource('configlang', LangController::class)->only([
			'index', 'store',
		]);
		Route::get('/configlang/removeLang/{lang}', 'LangController@removeLang')->name('configlang.removeLang');
		Route::get('/configlang/changeMainLang/{lang}', 'LangController@changeMainLang')->name('configlang.changeMainLang');

	});
});