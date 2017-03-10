<?php
Route::get('/', ['uses' => 'AuthAdminController@login', 'as' => 'adminlogin']);
Route::post('/logincheck',['uses' =>'AuthAdminController@check','as' => 'logincheck','middleware'=>'xss']);
Route::post('/loginselect/{type}',['uses' =>'AuthAdminController@loginselect','as' => 'loginselect','middleware'=>'xss']);
Route::get('/dashboard/{token}', ['uses' => 'AuthAdminController@dashboard', 'as' => 'admindashboard']);
Route::get('/adminlogout',['uses' =>'AuthAdminController@logout','as' => 'adminlogout']);
Route::any('/adminprofile/{token}', ['uses' => 'AuthAdminController@adminprofile', 'as' => 'adminprofile']);

//transactions
Route::get('/transactionReport/{token}/transactions',['uses' => 'TransactionReportController@accountingtransactions', 'as'=> 'transactions']);
Route::any('/transactionReport/transdetail/{token}/{id}', ['uses' => 'TransactionReportController@transdetail', 'as'=>'viewtransaction','middleware'=>'xss']);
Route::get('/transactionReport/void/{id}/{sts}', ['uses' => 'TransactionReportController@voidtransaction', 'as'=>'void']);
Route::get('/transactionReport/emailreceipt', ['uses' => 'TransactionReportController@emailreceipt', 'as'=>'emailreceipt']);

//deposits
Route::get('/depositReport/{token}/deposits', ['uses' => 'DepositReportController@deposits', 'as'=>'deposits']);

//recurring
Route::get('/recurringReport/{token}/recurring',['uses' => 'RecurringReportController@recurringpayments', 'as'=> 'recurring']);
Route::get('/recurringReport/{token}/completed',['uses' => 'RecurringReportController@completedrecurringpayments', 'as'=> 'completed']);
Route::get('/recurringReport/{token}/cancelled',['uses' => 'RecurringReportController@cancelledrecurringpayments', 'as'=> 'cancelled']);
Route::get('/recurringReport/cancel/{id}/{type}', ['uses' => 'RecurringReportController@cancelrecurring', 'as'=>'cancelrecurring']);

// outbound
Route::get('/outbound/{token}/list',['uses' => 'OutboundController@listOutbound', 'as'=> 'outbound']);

// Returned
Route::get('/returned/{token}', ['uses' => 'returnedController@returned', 'as'=>'returned']);

// Payment Categories
Route::get('/paymentcategories/{token}', ['uses' => 'PaymentCategoriesController@paymentCategories', 'as'=>'paymentcategories']);

//application
Route::get('/adminapplication/{token}', ['uses' => 'Application\ApplicationController@adminAppList', 'as'=>'applicationadmin']);

// promotions
Route::get('/promotion/{token}/list',['uses' => 'PromotionController@listPromotion', 'as'=> 'promotion']);

//importbatch
Route::any('/importbatch/{token}', ['uses' => 'ImportBatchController@importBatch', 'as'=>'importbatch']);

//einvoice
Route::get('/einvoice/{token}', ['uses' => 'eInvoiceController@eInvoiceList', 'as'=>'einvoice']);

//verticals
Route::get('/partner/{token}/list', ['uses' => 'PartnerController@partnerlist', 'as'=>'plist']);
Route::get('/partner/pdetail/{token}/{id}', ['uses' => 'PartnerController@partnerdetail', 'as'=>'pdetail']);
Route::post('/partner/pupdate', ['uses' => 'PartnerController@updatepartner', 'as'=>'pupdate','middleware'=>'xss']);

//groups
Route::get('/group/{token}/list', ['uses' => 'GroupController@grouplist', 'as'=>'glist']);
Route::get('/group/gdetail/{token}/{id}', ['uses' => 'GroupController@groupdetail', 'as'=>'gdetail']);
Route::post('/group/gupdate', ['uses' => 'GroupController@updategroup', 'as'=>'gupdate','middleware'=>'xss']);

//merchants
Route::get('/merchant/{token}/list', ['uses' => 'MerchantController@merchantlist', 'as'=>'mlist']);
Route::get('/merchant/{token}/profile/{id}', ['uses' => 'MerchantController@merchantprofile', 'as'=>'merchantprofile']);
Route::get('/merchant/mdetail/{token}/{id}', ['uses' => 'MerchantController@merchantdetail', 'as'=>'mdetail']);
Route::post('/merchant/mupdate', ['uses' => 'MerchantController@updatemerchant', 'as'=>'mupdate','middleware'=>'xss']);
Route::get('/merchant/{token}/paymentcredentials/{id}', ['uses' => 'MerchantController@merchantpaymentcredentials', 'as'=>'paymentcredentials']);
Route::get('/merchant/{token}/applications/{id}', ['uses' => 'MerchantController@merchantapplicationlist', 'as'=>'applications']);
Route::get('/merchant/{token}/contracthistory/{id}', ['uses' => 'MerchantController@merchantcontractshistory', 'as'=>'contracthistory']);
Route::get('/merchant/{token}/ivraccount/{id}', ['uses' => 'MerchantController@merchantivraccount', 'as'=>'ivraccount']);
Route::get('/merchant/{token}/velocities/{id}', ['uses' => 'MerchantController@merchantvelocities', 'as'=>'velocities']);
Route::get('/merchant/{token}/eventhistory/{id}', ['uses' => 'MerchantController@merchanteventhistory', 'as'=>'eventhistory']);
Route::get('/merchant/{token}/ticketreport/{id}', ['uses' => 'MerchantController@merchantticketreport', 'as'=>'ticketreport']);
Route::get('/merchant/{token}/fraudcontrol/{id}', ['uses' => 'MerchantController@merchantfraudcontrol', 'as'=>'fraudcontrol']);
Route::post('/merchant/{token}/profile/{id}/store', ['uses' => 'MerchantController@merchantprofilestore', 'as'=>'merchantprofilestore','middleware'=>'xss']);
Route::post('/merchant/{token}/movemerchantsubmit/{id}', ['uses' => 'MerchantController@movemerchantsubmit', 'as'=>'movemerchantsubmit','middleware'=>'xss']);
Route::post('/merchant/{token}/ivraccount/{id}/store', ['uses' => 'MerchantController@merchantivraccountStore', 'as'=>'ivraccountstore','middleware'=>'xss']);
Route::get('/adminapplication/companies/{id_partfner}', ['uses' => 'Application\ApplicationController@companiesByPartner', 'as'=>'companiesbypartner']);
Route::post('/merchant/{token}/paymentcredentials/{id}/echeckstore', ['uses' => 'MerchantController@merchantPCEcheckStore', 'as'=>'merchantpcecheckstore','middleware'=>'xss']);
Route::get('/merchant/{token}/removecredentials/{type}/{id}', ['uses' => 'MerchantController@removeCredentials', 'as'=>'removecredentials']);
Route::post('/merchant/{token}/paymentcredentials/{id}/ccstore', ['uses' => 'MerchantController@merchantPCCCStore', 'as'=>'merchantpcccstore','middleware'=>'xss']);
Route::post('/merchant/{token}/fraudcontrol/{id}/store', ['uses' => 'MerchantController@merchantfraudcontrolstore', 'as'=>'fraudcontrolstore','middleware'=>'xss']);
Route::get('/merchant/mChangeStatusWarning/{token}/{id}', ['uses' => 'MerchantController@changeMerchantStatusWarning', 'as'=>'mChangeStatusWarning']);
Route::post('/merchant/mUpdateStatus', ['uses' => 'MerchantController@updateMerchantStatus', 'as'=>'mUpdateStatus','middleware'=>'xss']);

//web users
Route::get('/webuser/{token}/list', ['uses' => 'WebUserController@webuserlist', 'as'=>'wulist']);
Route::get('/webuser/{token}/edit/{id}', ['uses' => 'WebUserController@editwebuser', 'as'=>'edit']);
Route::post('/webuser/save', ['uses' => 'WebUserController@savewebuser', 'as'=>'saveusr','middleware'=>'xss']);
Route::get('/webusers/deleteWebUser/{id}', ['uses' => 'WebUserController@deleteWebUser', 'as'=>'delwebuserdetail']);
Route::post('/webuser/webuserforgetpassword', ['uses' => 'WebUserController@resetpasswordwu', 'as'=>'wuresetpasseord','middleware'=>'xss']);

//pricing
Route::get('/pricing/{token}', ['uses' => 'PricingController@adminPricing', 'as'=>'adminpricing']);
Route::get('/pricing/{token}/new', ['uses' => 'PricingController@adminNewPricing', 'as'=>'adminnewpricing']);
Route::post('/pricing/{token}/new/store', ['uses' => 'PricingController@adminNewPricingStore', 'as'=>'adminnewpricingstore']);
Route::get('/pricing/{token}/open/{id_table}', ['uses' => 'PricingController@adminOpenPricing', 'as'=>'adminopenpricing']);
Route::post('/pricing/{token}/edit/{id_table}/store', ['uses' => 'PricingController@adminEditPricingStore', 'as'=>'admineditpricingstore']);
Route::get('/pricing/{token}/edit/{id_table}', ['uses' => 'PricingController@adminEditPricing', 'as'=>'admineditpricing']);
Route::get('/pricing/{token}/delete/{id_table}', ['uses' => 'PricingController@adminDeletePricing', 'as'=>'admindeletepricing']);

//admins
Route::get('/admins/{token}/list', ['uses' => 'AdminsController@adminlist', 'as'=>'adminlist']);
Route::get('/admins/admindetail/{token}/{id}', ['uses' => 'AdminsController@admindetail', 'as'=>'adminedit']);
Route::post('/admins/adminedit', ['uses' => 'AdminsController@updateadmin', 'as'=>'updateadmin','middleware'=>'xss']);
Route::get('/admins/{token}/deleteAdminUser/{id}', ['uses' => 'AdminsController@deleteAdminUser', 'as'=>'deladminuserdetail']);

//settings
Route::get('/settings/{token}', ['uses' => 'SettingsManagerController@settings', 'as'=>'settings']);

// sm
Route::get('/smgeneral/{token}', ['uses' => 'SettingsManagerController@general', 'as'=>'smgeneral']);

//tickets
Route::get('/tickets/{token}/tckhistory/{adm}',['uses' => 'TicketController@tckhistory', 'as'=> 'tckhistory']);

// emaf
Route::get('/emaf/{token}/list',['uses' => 'EmafController@listEmaf', 'as'=> 'emaf']);

// account
Route::get('/account/{token}/list',['uses' => 'AccountController@listAccounts', 'as'=> 'account']);

//Some formats export
Route::get('/formatsexport/{query_base64}/{type}',['uses' => 'ExportController@export', 'as'=> 'formatsexport']);

//api
Route::get('/api2/autopay/editautocat/{token}/{trans_id}/', ['uses' => 'ApiController@setautopaycat', 'as' => 'api2setautopaycat','middleware'=>'xss']);
Route::get('/api2/autopay/editautofreq/{token}/{trans_id}/', ['uses' => 'ApiController@setautopayfreq', 'as' => 'api2setautopayfreq','middleware'=>'xss']);
Route::get('/api2/autopay/editautometh/{token}/{trans_id}/', ['uses' => 'ApiController@setautopaymeth', 'as' => 'api2setautopaymeth','middleware'=>'xss']);
Route::get('/api2/autopay/savecategories/{token}/{info}/', ['uses' => 'ApiController@savecategories', 'as' => 'api2savecategories','middleware'=>'xss']);
Route::get('/api2/autopay/savefrequence/{token}/{info}/', ['uses' => 'ApiController@savefrequence', 'as' => 'api2savefrequence','middleware'=>'xss']);
Route::get('/api2/sso2/{token}', ['uses' => 'Auth\AuthController@confirmsso', 'as' => 'sso2','middleware'=>'xss']);

//errors
Route::get('/adminerror/{token}/{type}', ['uses' => 'AuthAdminController@error', 'as' => 'adminerror']);
