<?php

Route::group(['as' => 'page::'], function() {

    Route::get('/', ['as' => 'home', 'uses' => 'DefaultController@homePage']);

    Route::get('/about', ['as' => 'about', 'uses' => 'DefaultController@aboutPage']);

    Route::get('/terms', ['as' => 'terms', 'uses' => 'DefaultController@termsPage']);

    Route::get('/contact', ['as' => 'contact', 'uses' => 'DefaultController@contactPage']);

    Route::post('/contact', ['uses' => 'DefaultController@contact']);

    Route::get('/digital', ['as' => 'digital', 'uses' => 'DefaultController@digitalPage']);

    Route::post('/digital', ['uses' => 'DefaultController@digital']);

    Route::get('/services', ['as' => 'services', 'uses' => 'DefaultController@servicesPage']);

});

Route::group(['as' => 'painting::'], function() {

    Route::get('/paintings', ['as' => 'paintings', 'uses' => 'PaintingController@paintingsPage']);

    Route::get('/painting/{link}', ['as' => 'painting', 'uses' => 'PaintingController@paintingPage']);

});

Route::group(['as' => 'cart::'], function() {

    Route::get('/cart', ['as' => 'cart', 'uses' => 'CartController@cartPage']);

    Route::post('/cart/count', ['uses' => 'CartController@cartCount']);

    Route::post('/cart/painting', ['uses' => 'CartController@cartManagePainting']);

});

Route::group(['as' => 'admin::'], function() {

    Route::get('/dashboard', ['as' => 'dashboard', 'uses' => 'AdminController@dashboardPage']);

    // страница со списком всех картин
    Route::get('/dashboard/paintings', ['as' => 'paintings', 'uses' => 'AdminController@paintingsPage']);

    // страница добавления новой картины
    Route::get('/dashboard/painting/add', ['as' => 'addPainting', 'uses' => 'AdminController@addPaintingPage']);

    // добавление новой картины
    Route::post('/dashboard/painting/add', ['uses' => 'AdminController@addPainting']);

    // страница редактирования картины
    Route::get('/dashboard/painting/{link}', ['as' => 'editPainting', 'uses' => 'AdminController@editPaintingPage']);

    // редактирование картины
    Route::post('/dashboard/painting/edit', ['uses' => 'AdminController@editPainting']);

    // загрузка изображений для картин
    Route::post('/dashboard/painting/upload', ['as' => 'paintingUpload', 'uses' => 'AdminController@paintingUpload']);

    // картина снова в наличии
    Route::post('/dashboard/painting/available', ['uses' => 'AdminController@setAvailablePainting']);

    // удаление картины
    Route::post('/dashboard/painting/delete', ['uses' => 'AdminController@deletePainting']);

    Route::get('/dashboard/orders', ['as' => 'orders', 'uses' => 'AdminController@ordersPage']);

    Route::get('/dashboard/order/{id}', ['as' => 'order', 'uses' => 'AdminController@orderPage']);

    Route::post('/dashboard/order/delete', ['uses' => 'AdminController@deleteOrder']);

    // страница с новостями
    Route::get('/dashboard/events', ['as' => 'events', 'uses' => 'AdminController@eventsPage']);

    // страница добавления новости
    Route::get('/dashboard/event/add', ['as' => 'addEvent', 'uses' => 'AdminController@addEventPage']);

    // добавление новости
    Route::post('/dashboard/event/add', ['uses' => 'AdminController@addEvent']);

    // страница редактирования новости
    Route::get('/dashboard/event/{id}', ['as' => 'editEvent', 'uses' => 'AdminController@editEventPage']);

    // добавление новости
    Route::post('/dashboard/event/edit', ['uses' => 'AdminController@editEvent']);

    // удаление новости
    Route::post('/dashboard/event/delete', ['uses' => 'AdminController@deleteEvent']);

    // страница с featured artists
    Route::get('/dashboard/featured', ['as' => 'featured', 'uses' => 'AdminController@featuredPage']);

    // страница добавления featured artist
    Route::get('/dashboard/featured/add', ['as' => 'addFeatured', 'uses' => 'AdminController@addFeaturedPage']);

    // добавление featured artist
    Route::post('/dashboard/featured/add', ['uses' => 'AdminController@addFeatured']);

    // обновить текущий featured artist
    Route::post('/dashboard/featured/update', ['uses' => 'AdminController@updateCurrentFeatured']);

    // удаление featured artist
    Route::post('/dashboard/featured/delete', ['uses' => 'AdminController@deleteFeatured']);
    
});

Route::group([], function() {

    Route::get('/login', ['as' => 'login', 'uses' => 'AuthController@loginPage']);

    Route::post('/login', ['uses' => 'AuthController@login']);

    Route::get('/logout', ['as' => 'logout', 'uses' => 'AuthController@logout']);

});

Route::group(['as' => 'payment::', 'prefix' => 'payment'], function() {

    Route::post('/form', ['uses' => 'PaymentController@formHandler']);

    Route::post('/paypal/ipn', ['uses' => 'PaymentController@paypalIpn']);

    Route::get('/paypal/success', ['uses' => 'PaymentController@paypalSuccess']);

    Route::post('/stripe', ['as' => 'stripe', 'uses' => 'PaymentController@stripePay']);

    Route::get('/success', ['as' => 'success', 'uses' => 'PaymentController@successPage']);

});
