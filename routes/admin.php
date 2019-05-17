<?php
define('CMS_EXT_NAMESPACE', 'Ext\\Liudian\\Cms\\Http\\Controllers\\Admin');
define('LIUDIAN_CMS_NAMESPACE', 'Liudian\\Cms\\Http\\Controllers\\Admin');

Route::group(['middleware' => 'admin_auth', 'prefix' => config('liudian_admin.route_prefix') . '/cms', 'as' => 'admin_cms_'], function (){

    Route::group(['prefix' => 'article', 'as' => 'article_'], function (){

        $controller = getController(CMS_EXT_NAMESPACE, LIUDIAN_CMS_NAMESPACE, 'ArticleController');

        Route::match(['get', 'post'], 'index', $controller . '@index')->name('index');
        Route::match(['get', 'post'], 'create', $controller . '@create')->name('create');
        Route::match(['get', 'post'], 'edit/{id}', $controller . '@edit')->name('edit');
        Route::post('delete/{id}', $controller . '@delete')->name('delete');

        Route::get('show/{id}', $controller . '@show')->name('show');
    });

    Route::group(['prefix' => 'article_comment', 'as' => 'article_comment_'], function (){

        $controller = getController(CMS_EXT_NAMESPACE, LIUDIAN_CMS_NAMESPACE, 'ArticleCommentController');

        Route::match(['get', 'post'], 'index/{id}', $controller . '@index')->name('index');
        Route::match(['get', 'post'], 'reply/{id}', $controller . '@reply')->name('reply');
        Route::post('delete/{id}', $controller . '@delete')->name('delete');
    });

    Route::group(['prefix' => 'article_category', 'as' => 'article_category_'], function (){

        $controller = getController(CMS_EXT_NAMESPACE, LIUDIAN_CMS_NAMESPACE, 'ArticleCategoryController');

        Route::match(['get', 'post'], 'index', $controller . '@index')->name('index');
        Route::match(['get', 'post'], 'create', $controller . '@create')->name('create');
        Route::match(['get', 'post'], 'edit/{id}', $controller . '@edit')->name('edit');
        Route::post('delete/{id}', $controller . '@delete')->name('delete');
        Route::post('move/{id}', $controller . '@move')->name('move');
    });

    // 单页面管理
    Route::group(['prefix' => 'single', 'as' => 'single_'], function (){

        $controller = getController(CMS_EXT_NAMESPACE, LIUDIAN_CMS_NAMESPACE, 'SinglePageController');

        Route::match(['get', 'post'], 'index', $controller . '@index')->name('index');
        Route::match(['get', 'post'], 'create', $controller . '@create')->name('create');
        Route::match(['get', 'post'], 'edit/{id}', $controller . '@edit')->name('edit');
        Route::post('delete/{id}', $controller . '@delete')->name('delete');
        Route::get('show/{id}', $controller . '@show')->name('show');
    });

    // Banner位管理
    Route::group(['prefix' => 'banner/position', 'as' => 'banner_position_'], function (){

        $controller = getController(CMS_EXT_NAMESPACE, LIUDIAN_CMS_NAMESPACE, 'BannerPositionController');

        Route::match(['get', 'post'], 'index', $controller . '@index')->name('index');
        Route::match(['get', 'post'], 'create', $controller . '@create')->name('create');
        Route::match(['get', 'post'], 'edit/{id}', $controller . '@edit')->name('edit');
        Route::post('delete/{id}', $controller . '@delete')->name('delete');
    });

    Route::group(['prefix' => 'banner', 'as' => 'banner_'], function (){

        $controller = getController(CMS_EXT_NAMESPACE, LIUDIAN_CMS_NAMESPACE, 'BannerController');

        Route::match(['get', 'post'], 'index', $controller . '@index')->name('index');
        Route::match(['get', 'post'], 'create', $controller . '@create')->name('create');
        Route::match(['get', 'post'], 'edit/{id}', $controller . '@edit')->name('edit');
        Route::post('delete/{id}', $controller . '@delete')->name('delete');
    });
});