<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', 'Auth\LoginController@logout');

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('settings/profile', 'Settings\ProfileController@update');
    Route::post('update/profile', 'Settings\ProfileController@update2');
    Route::patch('settings/password', 'Settings\PasswordController@update');
    Route::get('userDate', 'Settings\ProfileController@getUser');

    Route::post('account/deactivate', 'Settings\ProfileController@deactivateAccount');

    //Brands routs
    Route::post('breands/create', 'BrandsController@create');
    Route::post('breands/update', 'BrandsController@update');
    Route::post('breands/remove/{id}', 'BrandsController@remove');
    Route::get('breands/getById/{id}', 'BrandsController@getById');
    Route::get('breands/getAllBrands', 'BrandsController@getAllBrands');
    //Categories routs
    Route::post('categories/create', 'CategoriesController@create');
    Route::post('categories/update', 'CategoriesController@update');
    Route::post('categories/remove/{id}', 'CategoriesController@remove');
    //Products routs
    Route::post('products/create', 'ProductsController@create');
    Route::post('products/update', 'ProductsController@update');
    Route::post('products/remove/{id}', 'ProductsController@remove');
    Route::post('products/insertImageForProduct/{id}', 'ProductsController@insertImageForProduct');
    Route::post('products/deleteImageForProduct', 'ProductsController@deleteImageForProduct');
    Route::get('products/myProduct', 'ProductsController@getMyProducts');
    Route::get('products/my-product-paginate', 'ProductsController@getMyProductsPaginate');
    Route::post('products/sold-product/{id}', 'ProductsController@soldProduct');
    Route::get('products/shop-by-id/{id}', 'ProductsController@getProductByIdShop');
    Route::get('get/status/products', 'ProductsController@getProductsByStatus');
    Route::get('get/discount/products', 'ProductsController@getProductsWithDiscount');
    Route::get('get/bestSeller/products', 'ProductsController@getBestSellerProducts'); 
    Route::get('get/searchTopFive/{value}', 'ProductsController@search');


    //Liked Products routs
    Route::post('likedproducts/create', 'LikedProductsController@create');
    Route::post('likedproducts/remove/{id}', 'LikedProductsController@removeByProductId');
    Route::get('likedproducts/getByProductId/{id}', 'LikedProductsController@getByProductId');
    Route::post('likedproducts/setByProductId/{id}', 'LikedProductsController@setByProductId');
    Route::get('likedproducts/get-my-product', 'LikedProductsController@getMyProduct');
    Route::get('is/liked/product/{id}', 'LikedProductsController@isLikeProduct');
    Route::get('get/liked/products', 'LikedProductsController@getLikedProducts');
    //Order routs
    Route::post('Orders/create', 'OrdersController@create');
    Route::post('orders/createfromZogu', 'OrdersController@createOrderFromZoguMall');
    Route::get('Orders/getAllOrdersByUserId/{id}', 'OrdersController@getAllOrdersByUserId');
    Route::get('Orders/getById/{id}', 'OrdersController@getById');
    Route::get('orders/getFromShopId', 'OrdersController@getOrdersFromShopId');
    Route::get('preorders/buyer', 'OrdersController@preOrdersBuyer');
    Route::get('orders/getFromAssignedStatus', 'OrdersController@getOrdersFromAssignedStatus');
    Route::post('preorder/remove', 'OrdersController@removePreOrders');

    Route::post('Orders/remove/{id}', 'OrdersController@remove');
    Route::post('Orders/update', 'OrdersController@update');
    Route::post('orders/assign/status', 'OrdersController@assignStatusToOrders');
    //History routs
    Route::post('histories/create', 'HistoryController@create');
    Route::get('histories/getByBuyerId/{id}', 'HistoryController@getByBuyerId');
    Route::get('histories/getBySellerId/{id}', 'HistoryController@getBySellerId');
    //ShoppingCard routs
    Route::post('shoppingCard/create', 'ShoppingCardController@create');
    Route::post('shoppingCard/remove/{id}', 'ShoppingCardController@remove');
    Route::get('shoppingCard/getByUserId', 'ShoppingCardController@getByUserId');
    Route::get('shoppingCard/getThreeProduct', 'ShoppingCardController@getThreeProduct');
    Route::post('shoppingCard/remoceAllProduct', 'ShoppingCardController@removeAllProduct');
    Route::post('shoppingCard/comment', 'ShoppingCardController@comment');

    Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
        Route::post('add/shop/important', 'Settings\ProfileController@addImportantShop');
    });

    Route::group(['middleware' => ['auth:api', 'role:admin|shop']], function () {
        //Documents routes
        Route::post('documents/create', 'DocumentController@create');
        Route::post('documents/remove/{id}', 'DocumentController@remove');
        Route::get('documents/get-documents', 'DocumentController@getDocuments');
        Route::post('create/parameters', 'CategoriesController@createParameters');
        Route::post('update/parameters', 'CategoriesController@updateParameters');
        Route::post('delete/parameters/{id}', 'CategoriesController@deleteParameters');
        Route::post('products/important/{id}/{status}', 'ProductsController@createOrRemoveImportantProducts');
        Route::post('product/change/important', 'ProductsController@chaneImportant');

        //User
        Route::get('users', 'Settings\ProfileController@getUsers');
    });
});

Route::group(['middleware' => 'guest:api'], function () {
    Route::post('login', 'Auth\LoginController@login')->name('login');
    Route::post('register', 'Auth\RegisterController@register');
    Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');

        //Facebook routes
    Route::get('auth/facebook', 'FacebookController@redirectToFacebook');
    Route::get('auth/facebook/callback', 'FacebookController@facebookSignin');

    Route::get('/redirect', 'FacebookController@redirect');
    Route::get('/callback', 'FacebookController@callback');

    // Route::get('get/cities', 'CitiesController@getCities');
});
//Brands me u check nashti duhet me u fshi se brendet jan parametra
Route::post('breands/update', 'BrandsController@update');
Route::post('breands/remove/{id}', 'BrandsController@remove');
Route::get('breands/getById/{id}', 'BrandsController@getById');
Route::get('breands/getAllBrands', 'BrandsController@getAllBrands');
Route::get('parameters/getCategory/{id}', 'CategoriesController@getAllParametersOfThisCategory');
//Paremterat
Route::get('parameters/getAllParameters', 'CategoriesController@getAllParameters');
Route::get('parameters/getParents', 'CategoriesController@getParents');
Route::get('parameters/{id}', 'CategoriesController@getByParent');
Route::get('parameters/getByCategoryId/{id}', 'CategoriesController@getParametersByCategoryId');
// Kategorit
Route::get('categories/getAllCategories', 'CategoriesController@getAllCategories');
Route::get('categories/getCompleteCategories', 'CategoriesController@getCompleteCategories');
Route::get('categories/getParents', 'CategoriesController@getCategoryParents');
Route::get('categories/getById/{id}', 'CategoriesController@getById');
Route::get('categories/getBySlug/{slug}', 'CategoriesController@getBySlug');
Route::get('categories/getImportantCategories', 'CategoriesController@getImportantCategories');
Route::get('categories/getCategorieswWithParameters', 'CategoriesController@getCategorieswWithParameters');
Route::get('categories/getMainCategories', 'CategoriesController@getMainCategories');
Route::get('categories/getMainCatById/{id}', 'CategoriesController@getMainCategoriesById');
//Produktet
Route::get('products/search/{title}/{category_id}', 'ProductsController@searchProduct');
// Route::get('products/searchProductPaginate/{title}/{category_id}', 'ProductsController@searchProductPaginate');
Route::post('products/searchProductPaginate', 'ProductsController@searchProductPaginate');
Route::get('products/searchTitle/{title}', 'ProductsController@searchTitle');
Route::get('products/getById/{id}', 'ProductsController@getById');
Route::get('products/getBySlug/{slug}', 'ProductsController@getBySlug');
Route::get('products/getProductsByCategoryId/{id}', 'ProductsController@getProductsByCategoryId');
Route::get('products/getProductsByCategorySlug/{id}', 'ProductsController@getProductsByCategorySlug');
Route::get('products/getProductsByBrandId/{id}', 'ProductsController@getProductsByBrandId');
Route::get('products/getProductsByUserId/{id}', 'ProductsController@getProductsByUserId');
Route::get('products/getAllProducts', 'ProductsController@getAllProducts');
Route::get('products/getLikedProducts', 'ProductsController@likedProducts');
Route::get('products/getAllProductsWithPagination', 'ProductsController@getAllProductsWithPagination');
Route::get('products/getProductsByShopName', 'ProductsController@getProductsByShopName');
Route::get('user/DataByShopName/{shop_name}', 'Settings\ProfileController@getUserDataByShopName');
Route::get('products/importantProducts', 'ProductsController@getImportantProducts');
Route::get('products/lastProduct', 'ProductsController@getLastProducts');
Route::get('products/relatedProducts/{cat_id}/{id}', 'ProductsController@getRelatedProducts');
Route::get('products/popularProducts', 'ProductsController@getPopularProducts');
Route::get('products/bestSellings', 'ProductsController@getBestSellings');

Route::get('shop/important', 'Settings\ProfileController@shopImportant');
Route::get('shop', 'Settings\ProfileController@shop');
Route::post('user/image/background', 'Settings\ProfileController@insetImageBackground');

Route::get('get/cities', 'CitiesController@getCities');

//Json product
Route::post('product/json', 'ProductJsonController@createJson');
Route::get('test/api/json', 'ProductJsonController@jsonProductTest');

//Posta zog route
Route::get('posta/company', 'PostaController@company');
Route::post('posta/login', 'PostaController@login');
Route::get('posta/status', 'PostaController@status');
Route::post('posta/orders/salesman', 'PostaController@ordersSalesman');
Route::post('posta/raport/salesman', 'PostaController@raportSalesman');
Route::post('posta/decline/order', 'PostaController@declineOrder');\
Route::post('posta/create/order', 'PostaController@createOrder');
Route::get('posta/user', 'PostaController@user');


//Contact Us routes
Route::post('contactUs/contactForm', 'ContactUsController@contactForm');
Route::post('contactUs/remove/{id}', 'ContactUsController@remove');
Route::get('contactUs/getContact/{id}', 'ContactUsController@getById');
Route::get('contactUs/getContactList', 'ContactUsController@getContactList');

//Review routes
Route::post('review/create', 'ReviewController@create');
Route::post('review/update', 'ReviewController@update');
Route::post('review/remove/{id}', 'ReviewController@remove');
Route::get('review/getReview/{id}', 'ReviewController@getById');
Route::get('review/getAllReviewsPerProduct/{id}', 'ReviewController@getAllReviewsPerProduct');

//Section routes
Route::post('section/create', 'SectionController@create');
Route::post('section/update', 'SectionController@update');
Route::post('section/remove/{id}', 'SectionController@remove');
Route::get('section/getSection/{id}', 'SectionController@getById');
Route::get('section/getSectionList', 'SectionController@getAllSections');
//Route::get('section/search/{value}', 'SectionController@search');

//Subscription routes
Route::post('subscription/create', 'SubscriptionController@create');
Route::post('subscription/remove/{id}', 'SubscriptionController@remove');
Route::get('subscription/getSubscriber/{id}', 'SubscriptionController@getById');
Route::get('subscription/getSubscribersList', 'SubscriptionController@getAllSubscribers');

//Provider Auth routes
Route::post('oauth/{driver}', 'Auth\OAuthController@redirect');
Route::get('oauth/{driver}/callback', 'Auth\OAuthController@handleProviderCallback')->name('oauth.callback');




