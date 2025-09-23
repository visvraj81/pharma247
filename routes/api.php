<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\PharmaShopController;
use App\Http\Controllers\Api\Admin\SubscriptionPlanController;
use App\Http\Controllers\Api\Admin\TranscationsController;
use App\Http\Controllers\Api\Admin\OfflineRequestController;
use App\Http\Controllers\Api\Admin\EmailInqueryController;
use App\Http\Controllers\Api\Admin\ProfileController;
use App\Http\Controllers\Api\Admin\SuperAdminController;
use App\Http\Controllers\Api\Admin\AgentPlanController;
use App\Http\Controllers\Api\Admin\SupportTicketController;
use App\Http\Controllers\Api\User\BatchController;
use App\Http\Controllers\Api\User\RegisterController;
use App\Http\Controllers\Api\User\LoginController;
use App\Http\Controllers\Api\User\CustomerController;
use App\Http\Controllers\Api\User\DistributerController;
use App\Http\Controllers\Api\User\ItemCategoryController;
use App\Http\Controllers\Api\User\PackageController;
use App\Http\Controllers\Api\User\IteamController;
use App\Http\Controllers\Api\User\SalesController;
use App\Http\Controllers\Api\Admin\PaymentController;
use App\Http\Controllers\Api\User\DoctorController;
use App\Http\Controllers\Api\User\PurchesController;
use App\Http\Controllers\Api\User\SalesReturnController;
use App\Http\Controllers\Api\User\PaymentPurchesController;
use App\Http\Controllers\Api\User\LedgerController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\Api\User\SalesReportController;
use App\Http\Controllers\Api\User\IteamMarginReportController;
use App\Http\Controllers\Api\User\StockController;
use App\Http\Controllers\Api\User\ScheduleController;
use App\Http\Controllers\UniteTableController;
use App\Http\Controllers\GSTController;
use App\Http\Controllers\Api\User\CompanyController;
use App\Http\Controllers\Api\User\IteamPurchesController;
use App\Http\Controllers\Api\User\BankController;
use App\Http\Controllers\Api\User\ExpenseContorller;
use App\Http\Controllers\Api\User\IteamViseMarginController;
use App\Http\Controllers\Api\User\DashboardController;
use App\Http\Controllers\Api\User\FrontRoleController;
use App\Http\Controllers\Api\User\ManageStaffController;
use App\Http\Controllers\Api\User\AboutController;
use App\Http\Controllers\DrugGroupController;
use App\Http\Controllers\YoutubeController;
use App\Http\Controllers\Api\User\ReconciliationController;
use App\Http\Controllers\Api\Patients\PatientsRegisterController;
use App\Http\Controllers\Api\Patients\PatientsEditProfileController;
use App\Http\Controllers\Api\Patients\PatientsFamilyController;
use App\Http\Controllers\Api\Patients\PatientsAddressController;
use App\Http\Controllers\Api\Patients\IteamPatientsController;
use App\Http\Controllers\Api\Patients\PatientHomeController;
use App\Http\Controllers\Api\Patients\PatientOrderController;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

// patient register Api
Route::post('patient-register',[PatientsRegisterController::class,'patientRegister']);
Route::post('patient-login',[PatientsRegisterController::class,'patientLogin']);
Route::post('patient-resend-otp',[PatientsRegisterController::class,'patientResendOtp']);
Route::post('patient-forget-password', [PatientsRegisterController::class, 'patientforgotPassword']);
Route::post('patient_version_code', [PatientsRegisterController::class, 'patientVersionCodeData']);

// pharma shop Route
Route::post('create-pharma-shop', [PharmaShopController::class, 'createPharma']);
Route::get('pharma-list', [PharmaShopController::class, 'pharmaList']);
Route::post('pharma-shop-delete', [PharmaShopController::class, 'pharmaDelete']);
Route::post('pharma-shop-edit', [PharmaShopController::class, 'pharmaEdit']);
Route::post('update-pharma-shop', [PharmaShopController::class, 'pharmaUpdate']);
Route::post('pharma-plan', [PharmaShopController::class, 'pharmaPlan']);
Route::post('pharma-plan-details', [PharmaShopController::class, 'pharmaPlanDetails']);

// subscription plan Route
Route::post('create-plan', [SubscriptionPlanController::class, 'createPlan']);
Route::get('plan-list', [SubscriptionPlanController::class, 'planList']);
Route::post('plan-delete', [SubscriptionPlanController::class, 'planDelete']);
Route::post('update-plan', [SubscriptionPlanController::class, 'planUpdate']);
Route::post('edit-plan', [SubscriptionPlanController::class, 'editPlan']);
Route::post('plan-feature-delete', [SubscriptionPlanController::class, 'planFeatureDelete']);

// transcations api
Route::get('transcations-list', [TranscationsController::class, 'transcationsList']);

// offline request Route
Route::post('offline-request', [OfflineRequestController::class, 'offlineRequest']);
Route::post('offline-request-delete', [OfflineRequestController::class, 'offlineRequestDelete']);
Route::post('offline-request-resonse', [OfflineRequestController::class, 'offlineReasone']);

// email inquery Route
Route::post('email-inuqery', [EmailInqueryController::class, 'emailInuqery']);
Route::post('email-replay', [EmailInqueryController::class, 'emailIReplay']);

// super admin Route
Route::post('create-super-admin', [SuperAdminController::class, 'superAdmin']);
Route::get('super-admin-list', [SuperAdminController::class, 'superAdminList']);
Route::post('super-admin-delete', [SuperAdminController::class, 'superAdminDelete']);
Route::post('super-admin-edit', [SuperAdminController::class, 'superAdminEdit']);
Route::post('super-admin-update', [SuperAdminController::class, 'superAdminUpdate']);

// profile Route
Route::post('update-profile', [ProfileController::class, 'updateProfile']);

// agent commision Route
Route::post('agent-create', [AgentPlanController::class, 'agentCreate']);
Route::get('agent-list', [AgentPlanController::class, 'agentList']);
Route::post('agent-edit', [AgentPlanController::class, 'agentEdit']);
Route::post('agent-delete', [AgentPlanController::class, 'agentDelete']);
Route::post('agent-update', [AgentPlanController::class, 'agentUpdate']);

// support tikect Route
Route::post('add-ticket', [SupportTicketController::class, 'addTicket']);
Route::get('list-ticket', [SupportTicketController::class, 'listTicket']);
Route::post('delete-ticket', [SupportTicketController::class, 'deleteTicket']);
Route::post('edit-ticket', [SupportTicketController::class, 'editTicket']);
Route::post('update-ticket', [SupportTicketController::class, 'updateTicket']);

// user register Api
Route::post('resgiter', [RegisterController::class, 'regsiterCreate']);

// user login Api
Route::post('login', [LoginController::class, 'userLogin']);
Route::post('logout', [LoginController::class, 'logout']);
Route::post('otp-resend', [RegisterController::class, 'otpResend']);
Route::get('help-support',[YoutubeController::class,'youtubeGet']);

// Forgot Password
Route::post('forget-password', [LoginController::class, 'forgotPassword']);
Route::post('version_code',[LoginController::class, 'versionCodeData']);
Route::post('switch_account_user_list', [LoginController::class, 'switchAccountUserList']);
  
Route::get('check-chemist-notification-send',[PatientOrderController::class,'checkChemistNotificationSend']);
Route::get('check-patient-notification-send',[PatientOrderController::class,'checkPatientNotificationSend']);

Route::middleware('auth:sanctum')->group(function () {
	Route::get('patient_delete_account', [PatientsRegisterController::class, 'patientDeleteAccountData']);
    
    Route::post('notification', [LoginController::class, 'notificationData']);
    Route::post('log-out', [LoginController::class, 'userLogout']);
    Route::get('privacy-policy',[LoginController::class,'privacyPolicy']);
    Route::get('permission-list', [LoginController::class, 'permissionList']);
  	Route::get('delete_account',[LoginController::class, 'deleteAccountData']);
  	Route::get('chemist-referral-list',[LoginController::class, 'chemistReferralList']);
  	Route::get('customer-referral-list',[LoginController::class, 'customerReferralList']);
  
    Route::post('role-create',[FrontRoleController::class,'roleCreate']);
    Route::get('role-list',[FrontRoleController::class,'roleList']);
    Route::post('role-status',[FrontRoleController::class,'roleStatus']);
    Route::post('role-edit',[FrontRoleController::class,'roleEdit']);
    Route::post('role-view',[FrontRoleController::class,'roleView']);
    
    Route::post('item-purchase',[IteamPurchesController::class,'itemPurchase']);
    Route::post('item-purchase-delete',[IteamPurchesController::class,'itemDelete']);
    Route::post('item-purchase-delete-all',[IteamPurchesController::class,'itemDeleteAll']);
    Route::post('item-purchase-delete-new-all',[IteamPurchesController::class,'itemDeleteNewAll']);
  
    Route::post('item-purchase-list',[IteamPurchesController::class,'itemList']);
    Route::post('item-purchase-update',[IteamPurchesController::class,'itemPurchasUpdatee']);
    Route::post('item-batch-imports',[IteamPurchesController::class,'iteamBatchList']);
    Route::post('purchase-item-upload',[IteamPurchesController::class,'purchasesIteamUpload']);
  
    Route::post('purchase-item-import',[IteamPurchesController::class,'purchaseItemImport']);
    Route::post('pharmabyte-item-import',[IteamPurchesController::class,'pharmabyteItemImport']);
    Route::post('mahalaxmi-item-import',[IteamPurchesController::class,'mahalaxmiItemImport']);
    Route::post('techno-item-import',[IteamPurchesController::class,'technoItemImport']);
  	Route::post('visual-item-purchase-import',[IteamPurchesController::class,'visualItemPurchaseImport']);
 
    //unit Route
    Route::post('unit-store',[UniteTableController::class,'uniteStore']);
    Route::get('unit-list',[UniteTableController::class,'listUnite']);
    Route::post('unit-edit',[UniteTableController::class,'editUnite']);
    Route::post('unit-update',[UniteTableController::class,'updateUnite']);
    Route::post('unit-delete',[UniteTableController::class,'deleteUnite']);
    
    //company 
    Route::post('company-store',[CompanyController::class,'companyStore']);
    Route::get('company-list',[CompanyController::class,'companyList']);
    Route::post('company-edit',[CompanyController::class,'companyEdit']);
    Route::post('company-update',[CompanyController::class,'companyUpdate']);
    Route::post('company-delete',[CompanyController::class,'companyDelete']);

    //customer Route
    Route::post('create-customer', [CustomerController::class, 'createCustomer']);
    Route::post('list-customer', [CustomerController::class, 'listCustomer']);
    Route::post('customer-list-search',[CustomerController::class, 'customerListSearch']);
    Route::post('edit-customer', [CustomerController::class, 'editCustomer']);
    Route::post('update-customer', [CustomerController::class, 'updateCustomer']);
    Route::post('delete-customer', [CustomerController::class, 'deleteCustomer']);
    Route::post('customer-list-view',[CustomerController::class,'customerListView']);
    Route::post('import-customer',[CustomerController::class,'importCustomer']);
    Route::post('customer-view',[CustomerController::class,'customerView']);
    Route::post('sales-bill-status',[CustomerController::class,'salesBillStatus']);
    Route::post('loyalti-point-add',[CustomerController::class,'royaltiPointAdd']);
    Route::post('loyalti-point-update',[CustomerController::class,'loyaltiPointUpdate']);
    Route::get('loyalti-point-list',[CustomerController::class,'loyaltiPointList']);
    Route::post('loyalti-point-delete',[CustomerController::class,'loyaltiPointDelete']);

    //distributer Route
    Route::post('create-distributer',[DistributerController::class,'createDistributer']);
    Route::post('list-distributer',[DistributerController::class,'listDistributer']);
    Route::post('view-distributer',[DistributerController::class,'viewDistributer']);
    Route::post('update-distributer',[DistributerController::class,'updateDistributer']);
    Route::post('delete-distributer',[DistributerController::class,'deleteDistributer']);
    Route::get('list-distributer',[DistributerController::class,'listsDistributer']);
    Route::post('distributer-purches-list',[DistributerController::class,'distributerPurchesList']);
    Route::post('purches-details',[DistributerController::class,'purchesDetails']);
    Route::post('import-distributer',[DistributerController::class,'importDistributer']);
    Route::post('distributer-company-list',[DistributerController::class,'distributerCompanyList']);

    //item category Route
    Route::post('create-itemcategory',[ItemCategoryController::class,'createItemcategory']);
    Route::get('list-itemcategory',[ItemCategoryController::class,'listItemcategory']);
    Route::post('edit-itemcategory',[ItemCategoryController::class,'editItemcategory']);
    Route::post('update-itemcategory',[ItemCategoryController::class,'updateItemcategory']);
    Route::post('delete-itemcategory',[ItemCategoryController::class,'deleteItemcategory']);
    
    //package Route
    Route::post('create-package',[PackageController::class,'createPackage']);
    Route::get('list-package',[PackageController::class,'listPackage']);
    Route::post('edit-package',[PackageController::class,'editPackage']);
    Route::post('update-package',[PackageController::class,'updatePackage']);
    Route::post('delete-package',[PackageController::class,'deletePackage']);

    //iteams Route
    Route::post('create-iteams',[IteamController::class,'itemsCreate']);
    Route::post('list-iteams',[IteamController::class,'itemsList']);
    Route::post('delete-iteam',[IteamController::class,'itemsDelete']);
    Route::post('edit-iteam',[IteamController::class,'editIteams']);
    Route::post('update-iteam',[IteamController::class,'updateIteams']);
    Route::post('item-search',[IteamController::class,'itemSearch']);
    Route::post('item-import',[IteamController::class,'itemImport']);
    Route::post('item-view',[IteamController::class,'ItemView']);
    Route::post('bulk-edit',[IteamController::class,'bulkEdit']);
    Route::post('item-batch-list',[IteamController::class,'BatchList']);
    Route::post('purches-items',[IteamController::class,'PurchesIteams']);
    Route::post('purche-return-item',[IteamController::class,'purcheReturnItem']);
    Route::post('invetory-sales-item',[IteamController::class,'salesData']);
    Route::post('invetory-sales-return',[IteamController::class,'salesReturnInvetory']);
    Route::post('ledger-item',[IteamController::class,'ledgerInvetory']);
    Route::get('item-location',[IteamController::class,'itemLocation']);
    Route::get('item-location-data',[IteamController::class,'itemLocationData']);
    Route::post('barcode-batch-list',[IteamController::class,'barcodeBatchList']);
    Route::post('multiple-item-batch-list',[IteamController::class,'iteamBatchList']);
    Route::post('qr-code-list',[IteamController::class,'qrCodeList']);
    Route::post('iteam-drug-group',[IteamController::class,'iteamDrugGroup']);
    Route::get('iteam-search-tags',[IteamController::class,'iteamSearchTags']);
  	Route::post('item-multiple-batch-view-list',[IteamController::class,'itemMultipleBatchViewList']);
  	Route::post('item-bulk-qr-code',[IteamController::class,'itemBulkQrCodeData']);
  	Route::post('testing-item-data-import',[IteamController::class,'testingItemDataImport']);
  
    //sales route
    Route::post('create-sales',[SalesController::class,'createSales']);
    Route::post('online-sales-order',[SalesController::class,'onlineSalesOrder']);
    Route::post('online-sales-status-changes',[SalesController::class,'onlineSalesStatusChanges']);
    Route::get('order-status-list',[SalesController::class,'onlineSalesStatus']);
    Route::post('online-bulck-order',[SalesController::class,'onlineBulkOrder']);
    Route::post('online-order-item',[SalesController::class,'onlineOrderItem']);
    Route::post('delete-sales',[SalesController::class,'deleteSales']);
    Route::post('sales-list',[SalesController::class,'listSales']);
    Route::post('sales-pdf',[SalesController::class,'salesPdf']);
    Route::post('sales-view',[SalesController::class,'listView']);
    Route::post('sales-update',[SalesController::class,'SalesUpdate']);
    Route::post('sales-iteam',[SalesController::class,'salesIteam']);
    Route::post('sales-iteam-list',[SalesController::class,'salesIteamList']);
    Route::post('customer-details',[SalesController::class,'customerDetails']);
    Route::post('sales-edit-details',[SalesController::class,'salesEditDetails']);
    Route::post('multiple-sale-pdf-downloads', [SalesController::class, 'multipleSalePdfDownloads']);
    Route::post('sales-history',[SalesController::class,'salesHistory']);
    
    Route::post('sales-item-add',[SalesController::class,'salesItemAdd']);
    Route::post('sales-item-edit',[SalesController::class,'salesItemEdit']);
    Route::post('sales-item-delete',[SalesController::class,'salesItemDelete']);
    Route::post('sales-item-list',[SalesController::class,'salesItemList']);
    Route::post('all-sales-item-delete',[SalesController::class,'allSalesItemDelete']);
    Route::post('sales-pdf-downloads', [SalesController::class, 'salesPdfDownloads']);
    Route::post('multiple-sale-pdf-downloads', [SalesController::class, 'multipleSalePdfDownloads']);
    Route::post('staff-list',[SalesController::class,'staffList']);
    
    //sales return
    Route::post('sales-return-create',[SalesReturnController::class,'salesReturnCreate']);
    Route::post('sale-return-pdf-downloads', [SalesReturnController::class, 'salesReturnPdfDownloads']);
    Route::post('sale-return-multiple-pdf-downloads', [SalesReturnController::class, 'salesReturnMultiplePdfDownloads']);
    Route::post('sales-return-delete',[SalesReturnController::class,'salesReturnDelete']);
    Route::post('sales-return-update',[SalesReturnController::class,'salesReturnUpdate']);
    Route::post('sales-return-list',[SalesReturnController::class,'salesReturnList']);
    Route::post('sales-return-view-details',[SalesReturnController::class,'salesReturnViewDetails']);
    Route::post('sales-return-iteam-list',[SalesReturnController::class,'salesReturnIteamAmount']);
    Route::post('sales-return-edit-iteam',[SalesReturnController::class,'salesReturnEditIteam']);
    Route::post('sales-return-delete-iteam',[SalesReturnController::class,'salesReturnDeleteIteam']);
    Route::post('sales-return-delete-history',[SalesReturnController::class,'salesDeletesHistory']);
    Route::post('sales-return-delete-history-second',[SalesReturnController::class,'salesDeletesHistorySecond']);
    Route::post('sales-return-edit-details',[SalesReturnController::class,'salesReturnEditDetails']);
    Route::post('sales-return-edit-iteam-delete',[SalesReturnController::class,'salesReturnEditIteamDelete']);
    Route::post('sales-return-edit-iteam-second',[SalesReturnController::class,'salesReturnEditIteamSecond']);
    
    Route::post('sales-return-edit-history',[SalesReturnController::class,'salesReturnEditHistory']);
    Route::post('sales-return-iteam-select',[SalesReturnController::class,'salesReturnIteamSelect']);
    Route::post('sales-return-edit-history-second',[SalesReturnController::class,'salesReturnEditHistorySecond']);

    Route::post('batch-add',[BatchController::class,'batchCreate']);
    Route::post('batch-list',[BatchController::class,'batchList']);
    Route::post('item-bulk-batch-list',[BatchController::class,'multipleBatchList']);
    Route::post('batch-edit',[BatchController::class,'batchEdit']);
    Route::post('batch-update',[BatchController::class,'batchUpdate']);
    Route::post('batch-delete',[BatchController::class,'batchDelete']);
    Route::post('distributor-batch',[BatchController::class,'distributorBatch']);

    Route::post('payment-create',[PaymentController::class,'paymentCreate']);
    Route::get('payment-list',[PaymentController::class,'paymentList']);
    Route::post('payment-update',[PaymentController::class,'paymentUpdate']);
    Route::post('delete-payment',[PaymentController::class,'paymentDelete']);
    Route::post('payment-edit',[PaymentController::class,'paymentEdit']);

    Route::post('doctor-create',[DoctorController::class,'doctorCreate']);
    Route::post('doctor-update',[DoctorController::class,'doctorUpdate']);
    Route::post('doctor-delete',[DoctorController::class,'doctorDelete']);
    Route::post('doctor-list',[DoctorController::class,'doctorList']);
    Route::post('doctor-sales-list',[DoctorController::class,'doctorSalesList']);
    Route::post('doctor-report',[DoctorController::class,'doctorReport']);
    Route::post('doctor-import',[DoctorController::class,'importDoctor']);
    Route::post('doctor-view',[DoctorController::class,'doctorView']);
    
    Route::post('purches-store',[PurchesController::class,'purchesStore']);
    Route::post('purches-details',[PurchesController::class,'purchesDetails']);
    Route::post('purches-update',[PurchesController::class,'purchesEdit']);
    Route::post('purches-delete',[PurchesController::class,'purchesDeleteData']);
    Route::post('purches-edit-data',[PurchesController::class,'purchesEditData']);
    Route::post('purches-histroy',[PurchesController::class,'purchesHistroy']);
    Route::post('purches-pdf-downloads',[PurchesController::class,'purchesPdfDownloads']);
    Route::post('multiple-purches-pdf-downloads', [PurchesController::class, 'multiplePurchesPdfDownloads']);
    Route::post('multiple-purches-return-pdf-downloads', [PurchesController::class, 'multiplePurchesReturnPdfDownloads']);

    Route::post('purches-list',[PurchesController::class,'purchesList']);
    Route::post('purches-return-delete',[PurchesController::class,'purchesDelete']);
    Route::post('purches-return-store',[PurchesController::class,'purcheReturnStore']);
    Route::post('purches-return-edit',[PurchesController::class,'purcheReturEdit']);
    Route::post('purches-return-list',[PurchesController::class,'purcheReturnList']);
    Route::post('purches-return-details',[PurchesController::class,'purcheReturnDetails']);
    Route::post('purches-return-filter',[PurchesController::class,'purcheReturnFilter']);
    Route::post('purches-return-edit-iteam',[PurchesController::class,'purchesReturnEditIteam']);
    // Route::post('purches-return-edit-data',[PurchesController::class,'purcheReturEditData']);
    Route::post('purches-return-iteam-delete',[PurchesController::class,'purchesReturnIteamDelete']);
    Route::post('purches-return-iteam-histroy',[PurchesController::class,'purchesReturnIteamHistroy']);
    Route::post('purches-return-destroy', [PurchesController::class, 'purcheReturDelete']);
    Route::post('purches-return-edit-data', [PurchesController::class, 'purcheReturnEditData']);
    Route::post('purches-return-iteam-update', [PurchesController::class, 'purchesReturniteamUpdate']);
    Route::post('distributor-payment', [PurchesController::class, 'distributorPayment']);
    Route::post('purches-return-pdf-downloads',[PurchesController::class,'purchesReturnPdf']);
    Route::post('purches-return-pdf',[PurchesController::class,'purchesReturnPdf']);
    Route::post('purchase-return-pending-bills',[PurchesController::class,'purchesReturnPendingBills']);
    Route::post('purchase-return-iteam-select',[PurchesController::class,'purchaseReturnIteamSelect']);
  
    // Route purches payment
    Route::post('purches-payment-list', [PaymentPurchesController::class, 'purchesPayment']);
    Route::post('purches-payment-store', [PaymentPurchesController::class, 'purchesPaymentStore']);
    Route::post('payment-details', [PaymentPurchesController::class, 'purchesDetails']);
    Route::post('add-money', [PaymentPurchesController::class, 'addMoney']);
    Route::post('purches-payment-edit', [PaymentPurchesController::class, 'purchesPaymentEdit']);
    Route::post('payment-purches-list', [PaymentPurchesController::class, 'purchesPaymentList']);
    Route::post('cash-category-list', [PaymentPurchesController::class, 'categoryList']);
    Route::post('cash-managment-create', [PaymentPurchesController::class, 'cashManagmentCreate']);
    Route::post('cash-managment-list', [PaymentPurchesController::class, 'cashManagmentList']);
    Route::post('cash-managment-pdf', [PaymentPurchesController::class, 'cashManagmentPdf']);

    // this sales Ledger route 
    Route::post('ledger',[LedgerController::class,'ledgerlist']);
    Route::post('purches-ledger',[LedgerController::class,'purchesLedger']);

    // this function use route
    Route::post('report-gst-purches',[ReportController::class,'resportPurches']);
    Route::post('day-vise-summry',[ReportController::class,'dayViseSummry']);
    Route::post('report-gst-sales',[ReportController::class,'reportGstSales']);
    Route::post('purches-payment-summary',[ReportController::class,'purchesPaymentSummary']);
    Route::post('item-wise-doctor',[ReportController::class,'itemWiseDoctor']);
    Route::post('company-items-analysis-report',[ReportController::class,'companyItemsAnalysisreport']);
    Route::post('sales-summary',[ReportController::class,'saleSummary']);
    Route::post('gst-one-report',[ReportController::class,'gstOneReport']);
    Route::post('gst-two-report',[ReportController::class,'gsttwoReport']);
    Route::post('gst-three-report',[ReportController::class,'gstThreereport']);

    // this function use sales report
    Route::post('sales-report',[SalesReportController::class,'salesReport']);
    Route::post('sales-bill-report',[SalesReportController::class,'salesBillReport']);
    Route::post('monthly-sales-overview',[SalesReportController::class,'monthlySalesOverview']);
    Route::post('top-selling-items',[SalesReportController::class,'topSellingItems']);
    Route::post('top-customer',[SalesReportController::class,'topCustomer']);
    Route::post('top-distributor',[SalesReportController::class,'topDistributor']);
     
    Route::post('add-bank',[BankController::class,'addBank']);
    Route::post('bank-list',[BankController::class,'bankList']);
    Route::post('bank-details',[BankController::class,'bankDetails']);
    Route::post('pdf-bank',[BankController::class,'pdfBank']);
    Route::post('add-balance',[BankController::class,'addBalance']);
       
    Route::post('add-expense',[ExpenseContorller::class,'addExpense']);
    Route::post('list-expense',[ExpenseContorller::class,'listExpense']);
    Route::post('pdf-expense',[ExpenseContorller::class,'pdfExpense']);
       
    Route::post('item-vise-margin',[IteamViseMarginController::class,'iteamViseReport']);
      
    // this function use iteam Margin Report
    Route::post('item-margin-report',[IteamMarginReportController::class,'IteamMarginReport']);
    Route::post('item-bill-margin',[IteamMarginReportController::class,'iteamBillMargin']);
    Route::post('sales-register',[IteamMarginReportController::class,'salesRegister']);
     
    Route::post('expiry-report',[IteamMarginReportController::class,'expiryReport']);
    Route::post('expiry-item-report',[IteamMarginReportController::class,'expiryIteamReport']);

    // this route use stock list
    Route::post('adjustment-date',[StockController::class,'stockAdujment']);
    Route::post('non-moving-items',[StockController::class,'nonMovingItems']);
    Route::post('iteam-batch-vise-stock',[StockController::class,'iteamBatchViseStock']);
    Route::post('purches-return-report',[StockController::class,'purchesReturnReport']);
    Route::post('adjust-stock',[StockController::class,'adjustStock']);
    Route::post('adjust-stock-list',[StockController::class,'adjustStockList']);
    Route::post('purches-iteam-list',[StockController::class,'purchesIteamlist']);
      
    // this function use schedule route
    Route::post('schedule-list',[ScheduleController::class,'scheduleList']);
    Route::post('staff-activity',[ScheduleController::class,'staffActivity']);

    Route::post('gst-store',[GSTController::class,'GSTStore']);
    Route::get('gst-list',[GSTController::class,'listGST']);
    Route::post('gst-edit',[GSTController::class,'editGST']);
    Route::post('gst-update',[GSTController::class,'updateGST']);
    Route::post('gst-delete',[GSTController::class,'deleteGST']);
    Route::post('gst-hsn-report',[GSTController::class,'gstHsnReport']);
    Route::post('gst-one',[GSTController::class,'gstOne']);
     
    Route::post('dashbord',[DashboardController::class,'dashbordData']);
  	Route::get('testing-loyalti-points',[DashboardController::class,'testingLoyaltiPoints']);
    Route::post('distributor-latest',[DashboardController::class,'distributorLatest']);
    Route::post('customer-latest',[DashboardController::class,'customerLatest']);
    Route::post('expiry-item-dashbord',[DashboardController::class,'expiryItemdashbord']);
    Route::post('bill-dashbord',[DashboardController::class,'billDashbord']);
       
    Route::post('manage-staff',[ManageStaffController::class,'mangeStaff']);
    Route::post('manage-list',[ManageStaffController::class,'managelist']);
    Route::post('manage-edit',[ManageStaffController::class,'manageEdit']);
    Route::post('manage-update',[ManageStaffController::class,'manageUpdate']);
    Route::post('status-change',[ManageStaffController::class,'statusChange']);
       
    Route::post('update-password',[AboutController::class,'updatePassword']);
    Route::post('license-store',[AboutController::class,'licenseStore']);
    Route::post('license-list',[AboutController::class,'licenseList']);
    Route::post('about-pharmacy',[AboutController::class,'aboutPharmacy']);
    Route::post('about-get',[AboutController::class,'getAbout']);
    Route::post('user-permission',[AboutController::class,'getUserPermission']);
    Route::post('roylti-point',[AboutController::class,'royltiPoint']);
    Route::post('chemist-store-details',[AboutController::class,'chemistStoreDetails']);
    Route::post('chemist-order-status',[AboutController::class,'chemistOrderStatus']);
    Route::get('chemist_notification_list',[AboutController::class,'chemistNotificationList']);
        
    Route::post('drug-list',[DrugGroupController::class,'drugList']);
    Route::post('drug-group-store',[DrugGroupController::class,'DrugGroupStore']);
    Route::post('drug-group-update',[DrugGroupController::class,'drugGroupUpdate']);
    Route::post('drug-group-edit',[DrugGroupController::class,'drugGroupEdit']);
    Route::post('drug-group-delete',[DrugGroupController::class,'drugGroupDelete']);
    Route::post('logs-activity',[DrugGroupController::class,'logsActivity']);
    Route::post('drug-item',[DrugGroupController::class,'drugItem']);
        
    Route::post('reconciliation-list',[ReconciliationController::class,'reconciliationList']);
    Route::post('reconciliation-iteam-list',[ReconciliationController::class,'reconciliationIteamList']);
    Route::post('reconciliation-iteam-store',[ReconciliationController::class,'reconciliationIteamStore']);
    Route::post('reconciliation-restart',[ReconciliationController::class,'reconciliationRestart']);
    Route::post('reconciliation-report',[ReconciliationController::class,'reconciliationReport']);
    Route::post('reconciliation-records-update',[ReconciliationController::class,'reconciliationRecordsUpdate']);
  
    Route::post('list-plan', [SubscriptionPlanController::class, 'listPlan']);
    Route::post('payment-details-store', [SubscriptionPlanController::class, 'paymentDetailsStore']);
    Route::get('payment-history', [SubscriptionPlanController::class, 'paymentHistory']);
    Route::get('referral-logs', [SubscriptionPlanController::class, 'referralLogs']);
  
    // Patients
    Route::post('patient-edit-profile',[PatientsEditProfileController::class,'patientEditProfile']);
    Route::get('patient-my-profile',[PatientsEditProfileController::class,'patientMyProfile']);
  	Route::get('patient-pharma-coin-list',[PatientsEditProfileController::class,'patientPharmaCoinList']);
    Route::post('patient-change-password',[PatientsEditProfileController::class,'patientChangePassword']);

    Route::post('patient-family-add',[PatientsFamilyController::class,'patientFamilyAdd']);
    Route::post('patient-family-list',[PatientsFamilyController::class,'patientFamilyList']);
    Route::post('patient-family-delete',[PatientsFamilyController::class,'patientFamilyDelete']);
  	Route::get('blood-group-list',[PatientsFamilyController::class,'bloodGroupList']);
    Route::get('patient-family-relation-list',[PatientsFamilyController::class,'patientFamilyRelationList']);

    Route::post('patient-address-add',[PatientsAddressController::class,'patientAddressAdd']);
    Route::post('patient-address-list',[PatientsAddressController::class,'patientAddressList']);
    Route::post('patient-address-delete',[PatientsAddressController::class,'patientAddressDelete']);
  
    Route::post('patient-iteam-list',[IteamPatientsController::class,'patientIteamList']);
    Route::post('patient-iteam-details',[IteamPatientsController::class,'patientIteamDetails']);
    Route::get('patient-delete-account',[IteamPatientsController::class,'patientDeleteAccount']);
    Route::post('patient-logout',[IteamPatientsController::class,'patientLogOut']);
  
    // Route::get('patient-home',[PatientHomeController::class,'patientHomeScreen']);
    Route::post('patient-add-cart',[PatientHomeController::class,'patientAddCart']);
  	Route::post('single-image-add',[PatientHomeController::class,'singleImageAdd']);
  	Route::post('single-image-delete',[PatientHomeController::class,'singleImageDelete']);
    Route::get('patient-cart-list',[PatientHomeController::class,'patientCartList']);
    Route::post('patient-cart-delete',[PatientHomeController::class,'patientCartDelete']); 
    Route::post('patient-checkout-details',[PatientHomeController::class,'patientChekoutDetails']);
    Route::get('patient-order-summary',[PatientHomeController::class,'patientOrderSummary']);
    Route::get('patient-home',[PatientHomeController::class,'patientHome']);
    Route::post('patient-chemist-search',[PatientHomeController::class,'patientChemistSearch']);
    Route::post('patient-chemist-details',[PatientHomeController::class,'patientChemistDetails']);
    Route::post('patient-preferred-chemist',[PatientHomeController::class,'patientPreferredChemist']);
  	
  	Route::get('patient-order-status-list',[PatientOrderController::class,'patientOrderStatusList']);
  	Route::get('notification_list',[PatientOrderController::class,'notificationList']);
    Route::post('patient-order',[PatientOrderController::class,'patientOrder']);
  	Route::post('patient_reorder',[PatientOrderController::class,'patientReOrder']);
    Route::post('patient-my-order-list',[PatientOrderController::class,'patientMyOrderList']);
    Route::post('patient-order-details',[PatientOrderController::class,'patientOrderDetails']);
    Route::post('patient-cancel-order',[PatientOrderController::class,'patientCancelOrder']);
    Route::post('chemist-order-list',[PatientOrderController::class,'chemistOrder']);
  	Route::post('accept_reject_order',[PatientOrderController::class,'acceptRejectOrder']);
  
  	Route::post('set_refill_reminder',[PatientOrderController::class,'setRefillReminder']);
  	Route::post('refill_reminder_list',[PatientOrderController::class,'RefillReminderList']);
  	Route::post('refill_reminder_edit',[PatientOrderController::class,'RefillReminderEdit']);
  	Route::post('refill_reminder_delete',[PatientOrderController::class,'RefillReminderDelete']);
  
  	Route::post('set_pill_reminder',[PatientOrderController::class,'setPillReminder']);
  	Route::post('pill_reminder_list',[PatientOrderController::class,'pillReminderList']);
  	Route::post('pill_reminder_edit',[PatientOrderController::class,'pillReminderEdit']);
  	Route::post('pill_reminder_delete',[PatientOrderController::class,'pillReminderDelete']);
  	Route::post('pill_reminder_pause_resume',[PatientOrderController::class,'pillReminderPauseResume']);
});