<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;

use App\Http\Controllers\Backend\BannerController;
use App\Http\Controllers\Backend\BlogController;
use App\Http\Controllers\Backend\BrandController;
use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CouponController;
use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\ProductController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\ReturnController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\ShippingAreaController;
use App\Http\Controllers\Backend\SliderController;
use App\Http\Controllers\Backend\SubCategoryController;
use App\Http\Controllers\Backend\VendorOrderController;
use App\Http\Controllers\Backend\VendorProductController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/dashboard', [UserController::class, 'UserDashboard'])->name('dashboard');
});



// Admin Profile
Route::get('admin-profile', [AdminController::class, 'AdminProfile']);
Route::post('admin-profile-store', [AdminController::class, 'AdminProfileStore']);
Route::post('admin-update-password', [AdminController::class, 'AdminUpdatePassword']);

// Admin-Vendor Approval
Route::get('inactive-vendor', [AdminController::class, 'InactiveVendor']);
Route::get('active-vendor', [AdminController::class, 'ActiveVendor']);
Route::get('inactive-vendor-details/{id}', [AdminController::class, 'InactiveVendorDetails']);
Route::post('active-vendor-approve', [AdminController::class, 'ActiveVendorApprove']);
Route::get('active-vendor-details/{id}', [AdminController::class, 'ActiveVendorDetails']);
Route::post('inactive-vendor-approve', [AdminController::class, 'InActiveVendorApprove']);

// Admin Management
Route::get('all-admin', [AdminController::class, 'AllAdmin']);
Route::get('add-admin', [AdminController::class, 'AddAdmin']);
Route::post('admin-user-store', [AdminController::class, 'AdminUserStore']);
Route::get('edit-admin-role/{id}', [AdminController::class, 'EditAdminRole']);
Route::post('admin-user-update/{id}', [AdminController::class, 'AdminUserUpdate']);
Route::delete('delete-admin-role/{id}', [AdminController::class, 'DeleteAdminRole']);

//User management
Route::get('/user-dashboard', [UserController::class, 'UserDashboard']);
Route::post('/user-profile-store', [UserController::class, 'UserProfileStore']);
Route::post('/user-update-password', [UserController::class, 'UserUpdatePassword']);
Route::post('/user-logout', [UserController::class, 'UserLogout']);


// Vendor management
Route::get('/vendor/profile', [VendorController::class, 'VendorProfile']);
Route::post('/vendor/profile/store', [VendorController::class, 'VendorProfileStore']);
Route::post('/vendor/update-password', [VendorController::class, 'VendorUpdatePassword']);
Route::post('/vendor/register', [VendorController::class, 'VendorRegister']);


// Banner Routes
Route::get('/all_banners', [BannerController::class, 'allBanners']);
Route::post('/store_banner', [BannerController::class, 'storeBanner']);
Route::post('/update_banner', [BannerController::class, 'updateBanner']);
Route::delete('/delete_banner/{id}', [BannerController::class, 'deleteBanner']);


// Blog Category Routes
Route::get('/all_blog_categories', [BlogController::class, 'allBlogCategories']);
Route::post('/store_blog_category', [BlogController::class, 'storeBlogCategory']);
Route::post('/update_blog_category', [BlogController::class, 'updateBlogCategory']);
Route::delete('/delete_blog_category/{id}', [BlogController::class, 'deleteBlogCategory']);


// Blog Post Routes
Route::get('/all_blog_posts', [BlogController::class, 'allBlogPosts']);
Route::post('/store_blog_post', [BlogController::class, 'storeBlogPost']);
Route::get('/edit_blog_post/{id}', [BlogController::class, 'editBlogPost']);
Route::post('/update_blog_post', [BlogController::class, 'updateBlogPost']);
Route::delete('/delete_blog_post/{id}', [BlogController::class, 'deleteBlogPost']);


// Frontend Blog Routes
Route::get('/all_blogs', [BlogController::class, 'allBlog']);
Route::get('/blog_details/{id}/{slug}', [BlogController::class, 'blogDetails']);
Route::get('/blog_post_category/{id}/{slug}', [BlogController::class, 'blogPostCategory']);


// Brand Routes
Route::get('/all_brands', [BrandController::class, 'allBrands']);
Route::post('/store_brand', [BrandController::class, 'storeBrand']);
Route::post('/update_brand', [BrandController::class, 'updateBrand']);
Route::delete('/delete_brand/{id}', [BrandController::class, 'deleteBrand']);


// Category Routes
Route::get('/all_categories', [CategoryController::class, 'allCategories']);
Route::post('/store_category', [CategoryController::class, 'storeCategory']);
Route::post('/update_category', [CategoryController::class, 'updateCategory']);
Route::delete('/delete_category/{id}', [CategoryController::class, 'deleteCategory']);


// Coupon Routes
Route::get('/all_coupons', [CouponController::class, 'allCoupons']);
Route::post('/store_coupon', [CouponController::class, 'storeCoupon']);
Route::post('/update_coupon', [CouponController::class, 'updateCoupon']);
Route::delete('/delete_coupon/{id}', [CouponController::class, 'deleteCoupon']);


// Order Routes
Route::get('/pending_orders', [OrderController::class, 'pendingOrders']);
Route::get('/admin_order_details/{order_id}', [OrderController::class, 'adminOrderDetails']);
Route::get('/admin_confirmed_order', [OrderController::class, 'adminConfirmedOrder']);
Route::get('/admin_processing_order', [OrderController::class, 'adminProcessingOrder']);
Route::get('/admin_delivered_order', [OrderController::class, 'adminDeliveredOrder']);
Route::post('/pending_to_confirm/{order_id}', [OrderController::class, 'pendingToConfirm']);
Route::post('/confirm_to_process/{order_id}', [OrderController::class, 'confirmToProcess']);
Route::post('/process_to_delivered/{order_id}', [OrderController::class, 'processToDelivered']);
Route::get('/admin_invoice_download/{order_id}', [OrderController::class, 'adminInvoiceDownload']);


// Product Routes
Route::get('/all_products', [ProductController::class, 'allProduct']);
Route::post('/store_product', [ProductController::class, 'storeProduct']);
Route::post('/update_product', [ProductController::class, 'updateProduct']);
Route::post('/update_product_thumbnail', [ProductController::class, 'updateProductThambnail']);
Route::post('/update_product_multiimage', [ProductController::class, 'updateProductMultiimage']);
Route::delete('/multi_image_delete/{id}', [ProductController::class, 'mulitImageDelelte']);
Route::post('/product_inactive/{id}', [ProductController::class, 'productInactive']);
Route::post('/product_active/{id}', [ProductController::class, 'productActive']);
Route::delete('/product_delete/{id}', [ProductController::class, 'productDelete']);
Route::get('/product_stock', [ProductController::class, 'productStock']);


// Order Search Routes
Route::post('/search_by_date', [ReportController::class, 'searchByDate']);
Route::post('/search_by_month', [ReportController::class, 'searchByMonth']);
Route::post('/search_by_year', [ReportController::class, 'searchByYear']);
Route::get('/orders_by_user', [ReportController::class, 'orderByUser']);
Route::post('/search_by_user', [ReportController::class, 'searchByUser']);


// Return Request Routes
Route::get('/return_requests', [ReturnController::class, 'returnRequest']);
Route::post('/return_requests_approved/{order_id}', [ReturnController::class, 'returnRequestApproved']);
Route::get('/completed_return_requests', [ReturnController::class, 'completeReturnRequest']);


// Role Routes
Route::get('/all_permissions', [RoleController::class, 'allPermission']);
Route::post('/store_permission', [RoleController::class, 'storePermission']);
Route::put('/update_permission/{id}', [RoleController::class, 'updatePermission']);
Route::delete('/delete_permission/{id}', [RoleController::class, 'deletePermission']);
Route::get('/all_roles', [RoleController::class, 'allRoles']);
Route::post('/store_roles', [RoleController::class, 'storeRoles']);
Route::put('/update_roles/{id}', [RoleController::class, 'updateRoles']);
Route::delete('/delete_roles/{id}', [RoleController::class, 'deleteRoles']);
Route::get('/add_roles_permission', [RoleController::class, 'addRolesPermission']);
Route::post('/role_permission_store', [RoleController::class, 'rolePermissionStore']);
Route::get('/all_roles_permissions', [RoleController::class, 'allRolesPermission']);
Route::put('/admin_roles_update/{id}', [RoleController::class, 'adminRolesUpdate']);
Route::delete('/admin_roles_delete/{id}', [RoleController::class, 'adminRolesDelete']);


// Shipping Routes
Route::get('/all_divisions', [ShippingAreaController::class, 'allDivision']);
Route::post('/store_division', [ShippingAreaController::class, 'storeDivision']);
Route::put('/update_division/{id}', [ShippingAreaController::class, 'updateDivision']);
Route::delete('/delete_division/{id}', [ShippingAreaController::class, 'deleteDivision']);
Route::get('/all_districts', [ShippingAreaController::class, 'allDistrict']);
Route::post('/store_district', [ShippingAreaController::class, 'storeDistrict']);
Route::put('/update_district/{id}', [ShippingAreaController::class, 'updateDistrict']);
Route::delete('/delete_district/{id}', [ShippingAreaController::class, 'deleteDistrict']);
Route::get('/get_district/{division_id}', [ShippingAreaController::class, 'getDistrict']);
Route::get('/all_states', [ShippingAreaController::class, 'allState']);
Route::post('/store_state', [ShippingAreaController::class, 'storeState']);
Route::put('/update_state/{id}', [ShippingAreaController::class, 'updateState']);
Route::delete('/delete_state/{id}', [ShippingAreaController::class, 'deleteState']);


// Slider Routes
Route::get('/all_sliders', [SliderController::class, 'allSlider']);
Route::post('/store_slider', [SliderController::class, 'storeSlider']);
Route::put('/update_slider/{id}', [SliderController::class, 'updateSlider']);
Route::delete('/delete_slider/{id}', [SliderController::class, 'deleteSlider']);


// SubCategory Routes
Route::get('/all_subcategories', [SubCategoryController::class, 'allSubCategory']);
Route::post('/store_subcategory', [SubCategoryController::class, 'storeSubCategory']);
Route::put('/update_subcategory/{id}', [SubCategoryController::class, 'updateSubCategory']);
Route::delete('/delete_subcategory/{id}', [SubCategoryController::class, 'deleteSubCategory']);
Route::get('/get_subcategories/{category_id}', [SubCategoryController::class, 'getSubCategory']);


// Vendor Order Routes
Route::get('/vendor_orders', [VendorOrderController::class, 'vendorOrder']);
Route::get('/vendor_return_order', [VendorOrderController::class, 'vendorReturnOrder']);
Route::get('/vendor_complete_return_order', [VendorOrderController::class, 'vendorCompleteReturnOrder']);
Route::get('/vendor_order_details/{order_id}', [VendorOrderController::class, 'vendorOrderDetails']);


// Vendor Products Routes
Route::get('/vendor_all_products', [VendorProductController::class, 'VendorAllProduct']);
Route::get('/vendor_get_subcategory/{category_id}', [VendorProductController::class, 'VendorGetSubCategory']);
Route::post('/vendor_store_product', [VendorProductController::class, 'VendorStoreProduct']);
Route::put('/vendor_update_product/{id}', [VendorProductController::class, 'VendorUpdateProduct']);
Route::put('/vendor_update_product_thumbnail/{id}', [VendorProductController::class, 'VendorUpdateProductThabnail']);
Route::put('/vendor_update_product_multi_image', [VendorProductController::class, 'VendorUpdateProductmultiImage']);
Route::delete('/vendor_multi_img_delete/{id}', [VendorProductController::class, 'VendorMultiimgDelete']);
Route::put('/vendor_product_inactive/{id}', [VendorProductController::class, 'VendorProductInactive']);
Route::put('/vendor_product_active/{id}', [VendorProductController::class, 'VendorProductActive']);
Route::delete('/vendor_product_delete/{id}', [VendorProductController::class, 'VendorProductDelete']);