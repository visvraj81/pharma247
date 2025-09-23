<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\EmailQueriesController;
use App\Http\Controllers\Admin\OfflineRequestController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\SuperAdminController;
use App\Http\Controllers\Admin\TranscationsController;
use App\Http\Controllers\Admin\SupportController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\IteamController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\Api\User\SalesController;
use App\Http\Controllers\DistributerController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\MenuController;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\YoutubeController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FAQController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\Api\Admin\SubscriptionPlanController;
use App\Http\Controllers\Api\User\PurchesController;
use App\Http\Controllers\Api\User\SalesReturnController;
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

Route::get('subscription-cron',[SubscriptionController::class,'subscriptionCron']);
Route::get('free-tryl-cron',[SubscriptionController::class,'freeTrylCron']);

Route::get('/pharmacy-billing-software',function(){
   return view('pharmacy_billing_software');
});

Route::get('/chemist-billing-software',function(){
   return view('chemist_billing_software');
});

Route::get('/cloud-based-pharmacy-software',function(){
   return view('cloud_based_pharmacy_software');
});
Route::get('/patient-privacy-policy',function(){
   return view('patient_privacy_policy');
});

Route::get('/example-qr-code-view', function() {
  return view('example_qr_code');
});

Route::get('/migrate', function () {
  //\Artisan::call('migrate --path=database/migrations/2024_08_27_115016_create_youtube_table.php');
  // \Artisan::call('config:clear');
  //\Artisan::call('route:clear');
  //\Artisan::call('view:clear');
  //\Artisan::call('db:seed', [
  //'--class' => 'DummyCustomer',
  //'--force' => true,
  //]);
  //  \Artisan::call('db:seed', [
  //'--class' => 'deleteTabel',
  //]);

  dd('All caches cleared!');
});

Route::get('/admin/login', function () {
  return view('auth.login');
});

Route::get('/', [MenuController::class, 'frontindex'])->name('front.index');

Auth::routes();
Route::get('/product-features/', [MenuController::class, 'productFeaturesIndex'])->name('product.features.index');
Route::post('/blog-comments/', [MenuController::class, 'blogComment'])->name('blog.comments');

Route::get('generate-pdf/{id}',[PurchesController::class,'generatePdf'])->name('generate.pdf');
Route::get('/pdf/data/{user_id}/{start_date}/{end_date}', [PurchesController::class, 'multplePdfGenrate'])->name('multple.pdf.dwonalod');
Route::get('generate-pdf-return/{id}',[PurchesController::class,'getPurcahesRetrunPdfGenrate'])->name('generate.pdf.retrun');
Route::get('/pdf/retrun/data/{user_id}/{start_date}/{end_date}', [PurchesController::class, 'getMultplePdfGenrate'])->name('multple.pdf.dwonalod.retrun');

Route::get('generate-pdf-sales/{id}',[SalesController::class,'getGenrateSalesPdf'])->name('generate.pdf.sales');
Route::get('/pdf/sales/data/{user_id}/{start_date}/{end_date}', [SalesController::class, 'getmultipleSalePdfDownloads'])->name('multple.pdf.sales.dwonalod');
Route::get('generate-pdf-sales-return/{id}',[SalesReturnController::class,'generatePdfSalesRetrun'])->name('generate.pdf.sales.retrun');
Route::get('/pdf/sales/retrun/data/{user_id}/{start_date}/{end_date}', [SalesReturnController::class, 'getSalesretrunMultpleGenratePdf'])->name('multple.pdf.sales.retrun.dwonalod');

Route::get('/pricing', [MenuController::class, 'pricingIndex'])->name('pricing.index');
Route::get('/blogs/{title}', [MenuController::class, 'singleblogData'])->name('singleblog');
Route::get('/demo-training/', [MenuController::class, 'demotrainIndex'])->name('demotrain.index');
Route::get('/contact-us', [MenuController::class, 'contactusIndex'])->name('contactus.index');
Route::get('/about-us', [MenuController::class, 'aboutusIndex'])->name('aboutus.index');
Route::get('/refer-earn/', [MenuController::class, 'referandearnIndex'])->name('referandearn.index');
Route::get('/blogs/', [MenuController::class, 'blogsIndex'])->name('blogs.index');
Route::get('/privacy-policys', [MenuController::class, 'privacypolicyIndex'])->name('privacy-policys');
Route::get('/cancellation-policy', [MenuController::class, 'cancellationpolicyIndex'])->name('cancellationpolicy.index');
Route::get('/term-conditions', [MenuController::class, 'termConditions'])->name('term-conditions');
Route::get('/book/training/index', [MenuController::class, 'bookTrainingIndex'])->name('book.training.index');
Route::post('/contactus/store', [MenuController::class, 'contactusStore'])->name('contactus.store');
Route::post('insert_contact',[MenuController::class,'insertContact'])->name('insert_contact');
Route::post('ready/to/get/store',[MenuController::class,'readyToGetStore'])->name('ready.to.get.store');

Route::get('video-index',[HomeController::class,'videoIndex'])->name('video.index');

Route::group(['middleware' => ['auth']], function () {
  Route::get('blog-list', [BlogController::class, 'blogList'])->name('blog-list');
  Route::get('blog-edit/{id}', [BlogController::class, 'blogeditCreate'])->name('blog.edit');
  Route::get('blog-create', [BlogController::class, 'blogCreate'])->name('blog-create');
  Route::post('blog-store', [BlogController::class, 'blogsStore'])->name('blog.store');
  Route::get('blog-delete/{id}', [BlogController::class, 'blogDelete'])->name('blog.delete');
  Route::post('blog-update', [BlogController::class, 'blogUpdate'])->name('update.blog');
  Route::post('/ckeditor/upload', [BlogController::class, 'uploadImage'])->name('ckeditor.upload');
  
  Route::get('blog-category',[BlogCategoryController::class,'blogCategory'])->name('blog.category');
  Route::get('categorys-edit/{id}', [BlogCategoryController::class, 'editCategory'])->name('categorys.edit');
  Route::get('categorys-create', [BlogCategoryController::class, 'categorysCreate'])->name('categorys-create');
  Route::post('categorys-store', [BlogCategoryController::class, 'categoryssStore'])->name('categorys.store');
  Route::get('categorys-delete/{id}', [BlogCategoryController::class, 'categorysDelete'])->name('categorys.delete');
  Route::post('categorys-update', [BlogCategoryController::class, 'categorysUpdate'])->name('update.categorys');

  Route::get('youtue-list', [YoutubeController::class, 'youtubeList'])->name('youtue-list');
  Route::get('youtue-edit/{id}', [YoutubeController::class, 'editCreate'])->name('youtue.edit');
  Route::get('youtue-create', [YoutubeController::class, 'youtubeCreate'])->name('youtue-create');
  Route::post('youtue-store', [YoutubeController::class, 'youtubesStore'])->name('youtube.store');
  Route::get('youtue-delete/{id}', [YoutubeController::class, 'youtubeDelete'])->name('youtue.delete');
  Route::post('youtue-update', [YoutubeController::class, 'youtubeUpdate'])->name('update.youtue');
    
  Route::get('term-conditions-admin',[FAQController::class,'termConditionsAdmin'])->name('term.conditions.admin');
  Route::post('term_conditions-store',[FAQController::class,'term_conditionsStore'])->name('term_conditions.store');
  Route::get('refund-cancellation-data',[FAQController::class,'refundCancellationData'])->name('refund-cancellation-data');
  Route::post('refund-cancellation-store',[FAQController::class,'refundCancellationStore'])->name('refund.cancellation.store');
  
  Route::get('faq-list', [FAQController::class, 'faqList'])->name('faq-list');
  Route::get('faq-edit/{id}', [FAQController::class, 'faqeditCreate'])->name('faq.edit');
  Route::get('faq-create', [FAQController::class, 'faqCreate'])->name('faq-create');
  Route::post('faq-store', [FAQController::class, 'faqsStore'])->name('faq.store');
  Route::get('faq-delete/{id}', [FAQController::class, 'faqDelete'])->name('faq.delete');
  Route::post('faq-update', [FAQController::class, 'faqUpdate'])->name('update.faq');

  Route::get('/home', [HomeController::class, 'index'])->name('home');
  Route::get('slider-index',[HomeController::class, 'sliderIndex'])->name('slider.index');
  Route::get('slider-create',[HomeController::class, 'sliderCreate'])->name('slider.create');
  Route::post('slider-store',[HomeController::class, 'sliderStore'])->name('slider.store');
  Route::get('slider-delete/{id}',[HomeController::class, 'sliderDelete'])->name('slider.delete');
  Route::get('slider-edit/{id}',[HomeController::class, 'sliderEdit'])->name('slider.edit');
  Route::post('slider-update',[HomeController::class, 'sliderUpdate'])->name('slider.update');
  Route::get('video-index',[HomeController::class,'videoIndex'])->name('video.index');

  Route::get('page_meta_tags',[HomeController::class, 'pageMetaTags'])->name('page_meta_tags');
  Route::post('update-page-meta',[HomeController::class, 'updatePageMeta'])->name('update.page.meta');

  Route::get('/privacy-policy', [HomeController::class, 'privacyPolicy'])->name('privacy-policy');
  Route::post('/privacy-policy-store', [HomeController::class, 'privacyPolicyStore'])->name('privacy_policy.store');

  Route::get('pharma-index', [CompanyController::class, 'pharmaIndex'])->name('pharma.index');
  Route::get('pharma-create', [CompanyController::class, 'pharmaCreate'])->name('pharma.create');
  Route::post('pharma-store', [CompanyController::class, 'pharmaStore'])->name('pharma.store');
  Route::get('pharma-delete/{id}', [CompanyController::class, 'pharmaDelete'])->name('pharma.delete');
  Route::get('pharma-edit/{id}', [CompanyController::class, 'pharmaEdit'])->name('pharma.edit');
  Route::post('pharma-update', [CompanyController::class, 'pharmaUpdate'])->name('pharma.update');
  Route::get('pharma-subcription/{id}', [CompanyController::class, 'pharmaSubcription'])->name('pharma.subcription');
  Route::post('pharma-subscription-update', [CompanyController::class, 'pharmaSubscriptionUpdate'])->name('pharma.subscription.update');
  Route::post('/check-email', [CompanyController::class, 'checkEmail'])->name('pharma.checkEmail');
  Route::get('/agent-plan', [CompanyController::class, 'agentPlan'])->name('agent.plan');

  Route::resource('roles', RoleController::class);
  Route::post('/change-role', [RoleController::class, 'changeRoles'])->name('change.roles');
  Route::get('roles-delete/{id}', [RoleController::class, 'rolesDelete'])->name('roles.delete');

  Route::get('/permissions-index', [CompanyController::class, 'permissionsIndex'])->name('permissions.index');
  Route::get('/permissions-delete/{id}', [CompanyController::class, 'permissionsDelete'])->name('permissions.delete');
  Route::get('/permissions-edit/{id}', [CompanyController::class, 'permissionsEdit'])->name('permissions.edit');
  Route::get('/permissions-create', [CompanyController::class, 'permissionsCreate'])->name('permissions.create');
  Route::post('/permissions-store', [CompanyController::class, 'permissionsStore'])->name('permissions.store');
  Route::post('/permissions-update', [CompanyController::class, 'permissionsUpdate'])->name('permissions.update');

  Route::get('subscription-index', [SubscriptionController::class, 'subscriptionIndex'])->name('subscription.index');
  Route::get('subscription-create', [SubscriptionController::class, 'subscriptionCreate'])->name('subscription.create');
  Route::post('subscription-store', [SubscriptionController::class, 'subscriptionStore'])->name('subscription.store');
  Route::get('subscription-delete/{id}', [SubscriptionController::class, 'subscriptionDelete'])->name('subscription.delete');
  Route::get('subscription-edit/{id}', [SubscriptionController::class, 'subscriptionEdit'])->name('subscription.edit');
  Route::post('subscription-update', [SubscriptionController::class, 'subscriptionUpdate'])->name('subscription.update');
  Route::get('plan-feature-delete/{id}', [SubscriptionController::class, 'planFeatureDelete'])->name('plan_feature_delete');

  // agent route
  Route::get('agent-index', [AgentController::class, 'AgentIndex'])->name('agent.index');
  Route::get('agent-create', [AgentController::class, 'AgentCreate'])->name('agent.create');
  Route::post('agent-store', [AgentController::class, 'AgentStore'])->name('agent.store');
  Route::get('agent-delete/{id}', [AgentController::class, 'agentDelete'])->name('agent.delete');
  Route::get('agent-edit/{id}', [AgentController::class, 'agentEdit'])->name('agent.edit');
  Route::post('agent-update', [AgentController::class, 'agentUpdate'])->name('agent.update');

  // transction route
  Route::get('transction', [TranscationsController::class, 'transctionData'])->name('transction.index');
  Route::get('banner-index', [TranscationsController::class, 'bannerIndex'])->name('banner.index');
  Route::get('banner-create', [TranscationsController::class, 'bannerCreate'])->name('banner.create');
  Route::post('banner-store', [TranscationsController::class, 'bannerStore'])->name('banner.store');
  Route::post('banner-update', [TranscationsController::class, 'bannerUpdate'])->name('banner.update');
  Route::get('banner-edit/{id}', [TranscationsController::class, 'bannerEdit'])->name('banner.edit');
  Route::get('banner-delete/{id}', [TranscationsController::class, 'bannerDelete'])->name('banner.delete');

  // offlinerequest route
  Route::get('offlinerequest', [OfflineRequestController::class, 'offlinerequestData'])->name('offlinerequest.index');
  Route::get('offlinerequestapprove', [OfflineRequestController::class, 'offlineRequestApprove'])->name('offlinerequestapprove');
  Route::get('offlinerequestreject', [OfflineRequestController::class, 'offlineRequestReject'])->name('offlinerequestreject');
  Route::post('offlinerequeststatus', [OfflineRequestController::class, 'offlineRequestStatus'])->name('offline.request.status');
  Route::get('add-lead', [OfflineRequestController::class, 'addLead'])->name('add.lead');
  Route::post('lead-store', [OfflineRequestController::class, 'leadStore'])->name('lead.store');

  // emailqueries route
  Route::get('emailqueriesindex', [EmailQueriesController::class, 'emailQueriesData'])->name('emailqueries.index');
  Route::get('emailqueriesreplied', [EmailQueriesController::class, 'emailQueriesReplied'])->name('emailqueriesreplied');

  Route::get('send-email/{id}', [EmailQueriesController::class, 'sendEmail'])->name('send.email');
  Route::post('send-email-store', [EmailQueriesController::class, 'sendEmailStore'])->name('sendemail.store');

  // super admin route
  Route::get('super-admin-index', [SuperAdminController::class, 'superAdminIndex'])->name('superadmin.index');
  Route::get('super-admin-create', [SuperAdminController::class, 'superAdminCreate'])->name('superadmin.create');
  Route::post('super-admin-store', [SuperAdminController::class, 'superAdminStore'])->name('superadmin.store');
  Route::get('super-admin-delete/{id}', [SuperAdminController::class, 'superAdminDelete'])->name('superadmin.delete');
  Route::get('super-admin-edit/{id}', [SuperAdminController::class, 'superAdminEdit'])->name('superadmin.edit');
  Route::post('super-admin-update', [SuperAdminController::class, 'superAdminUpdate'])->name('superadmin.update');

  // profile route
  Route::get('profile-index/{id}', [ProfileController::class, 'profileIndex'])->name('profile.index');
  Route::post('profile-update', [ProfileController::class, 'profileUpdate'])->name('profile.update');
  Route::get('reference-index', [ProfileController::class, 'referenceIndex'])->name('reference.index');
  Route::post('refrence-update', [ProfileController::class, 'refrenceUpdate'])->name('refrence.update');

  Route::get('support-index', [SupportController::class, 'supportIndex'])->name('support.index');
  Route::get('support-delete/{id}', [SupportController::class, 'supportDelete'])->name('support.delete');
  Route::get('support.edit/{id}', [SupportController::class, 'supportEdit'])->name('support.edit');
  Route::post('support.update', [SupportController::class, 'supportUpdate'])->name('support.update');

  // logs module
  Route::get('logs-index', [SupportController::class, 'logsIndex'])->name('logs.index');
  
  // payment 
  Route::get('payment-details', [PaymentController::class, 'paymentDetails'])->name('payment.details');
  Route::get('payment-create', [PaymentController::class, 'PaymentCreate'])->name('payment.create');
  Route::post('payment-store', [PaymentController::class, 'paymentStore'])->name('payment.store');
  Route::get('payment-delete/{id}', [PaymentController::class, 'paymentDelete'])->name('payment.delete');
  Route::get('payment-edit/{id}', [PaymentController::class, 'paymentEdit'])->name('payment.edit');
  Route::post('payment-update', [PaymentController::class, 'paymentUpdate'])->name('payment.update');

  //route iteam route
  Route::post('iteam-create', [IteamController::class, 'iteamCreate'])->name('iteam.create');
  Route::get('iteam-add', [IteamController::class, 'iteamAdd'])->name('iteam.add');
  Route::get('iteam-edit/{id}', [IteamController::class, 'iteamEdit'])->name('iteam.edit');
  Route::post('item-update', [IteamController::class, 'iteamUpdate'])->name('item.update');

  Route::get('iteam-bluk-add', [IteamController::class, 'iteamBlukAdd'])->name('iteam.bluk.add');
  Route::post('iteam-store-bulk',[IteamController::class,'iteamStoreBulk'])->name('iteam.bluk.store');
  Route::get('iteam-lists',[IteamController::class,'iteamLists'])->name('iteam.lists');
  Route::post('toggle-recommend',[IteamController::class,'toggleRecommend'])->name('toggle.recommend');
  
  // route batch create
  Route::get('batch-create/{id}', [BatchController::class, 'batchCreate'])->name('batch.create');
  Route::post('batch-add', [BatchController::class, 'batchAdd'])->name('batch.add');
  Route::get('batch-delete/{id}', [BatchController::class, 'batchDelete'])->name('batch.delete');
  Route::post('batch-update', [BatchController::class, 'batchUpdate'])->name('update.batch');

  Route::get('sales-list', [SalesController::class, 'salesList'])->name('sales.list');

  Route::get('add-distributer', [DistributerController::class, 'AddDistributer'])->name('distributer');
  Route::post('distributor-store', [DistributerController::class, 'distributorStore'])->name('distributor.store');

  Route::get('purches-add', [PurchaseController::class, 'purchaseAdd'])->name('purches.add');
  Route::post('purches-store', [PurchaseController::class, 'purchaseStore'])->name('purches.store');
  Route::get('purches-return', [PurchaseController::class, 'purchesReturn'])->name('purches.return');
  Route::get('purches-data', [PurchaseController::class, 'purchesData'])->name('purches-data');
  Route::post('purches-return-store', [PurchaseController::class, 'purchesReturnData'])->name('purches.return.store');
});
