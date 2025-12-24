<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'account/users';
$route['404_override'] = 'admin/page_error';
$route['translate_uri_dashes'] = FALSE;


$route['webservices/users'] = 'webservices/users'; 

/*****************************************Admin Routes ***************************/

$route['admin/all_senders']        				= 'admin/home/all_senders';
$route['admin/all_senders/(:any)']        	    = 'admin/home/all_senders/$1';
$route['admin/searchSender']                    = 'admin/home/searchSender';
$route['admin/sender_details/(:any)']     		= 'admin/home/sender_details/$1';
$route['admin/sender_documents_verified/(:any)']= 'admin/home/sender_documents_verified/$1';

$route['admin/all_receivers']        			= 'admin/home/all_receivers';
$route['admin/all_receivers/(:any)']        	= 'admin/home/all_receivers/$1';
$route['admin/searchReceiver']                  = 'admin/home/searchReceiver';
$route['admin/receiver_details/(:any)']         = 'admin/home/receiver_details/$1';

$route['admin/receiver_documents_verified/(:any)']= 'admin/home/receiver_documents_verified/$1';
$route['admin/balance_user/(:any)/(:any)'] 	    = 'admin/home/balance_user/$1/$2';
$route['admin/user_logins/(:any)/(:any)'] 	    = 'admin/home/user_logins/$1/$2';

$route['admin/addAdvertisements']        		 = 'admin/advertisement/addAdvertisements';
$route['admin/get_country']        		 		= 'admin/advertisement/get_country';
$route['admin/all_advertisements']        		 = 'admin/advertisement/all_advertisements';
$route['admin/advertisement_details/(:any)']     = 'admin/advertisement/advertisement_details/$1';

$route['admin/all_notification_mails']           = 'admin/admin/all_notification_mails';
$route['admin/notification_mail_details/(:any)'] = 'admin/admin/notification_mail_details/$1';

$route['admin/referrals_listing']        		 = 'admin/referrals/referrals_listing';
$route['admin/referrals_management']        	 = 'admin/referrals/referrals_management';

$route['admin/redeem_referrals_list']        	= 'admin/referrals/redeem_referrals_list';

$route['admin/user_transactions/(:any)/(:any)'] = 'admin/home/user_transactions/$1/$2';
$route['admin/user_deposits/(:any)/(:any)'] 	= 'admin/home/user_deposits/$1/$2';
$route['admin/user_withdraw/(:any)/(:any)'] 	= 'admin/home/user_withdraw/$1/$2';


$route['admin/all_transactions']            	= 'admin/home/all_transactions';
$route['admin/all_transactions/(:any)']         = 'admin/home/all_transactions/$1';
$route['admin/searchTransaction']               = 'admin/home/searchTransaction';
$route['admin/withdraw_history']           	 	= 'admin/home/withdraw_history';
$route['admin/deposit_history']             	= 'admin/home/deposit_history';
$route['admin/send_money'] 						= 'admin/home/send_money';
$route['admin/request_money'] 					= 'admin/home/request_money';
$route['admin/sharebill_request'] 				= 'admin/home/sharebill_request';

$route['admin/paybills'] 						= 'admin/home/paybill_list';

$route['admin/all_qrcodes'] 					= 'admin/home/all_qrcodes';
$route['admin/add_biller'] 						= 'admin/home/add_biller';
$route['admin/billers_list'] 					= 'admin/home/billers_list';

$route['admin/promocode_list'] 					= 'admin/home/promocode_list';
$route['admin/add_promocode'] 					= 'admin/home/add_promocode';

$route['admin/profile_setting'] 				= 'admin/admin/profile_setting';

$route['admin/terms_setting/(:any)'] 		    = 'admin/admin/page_settings/$1';
$route['admin/privacy_policy/(:any)'] 		    = 'admin/admin/page_settings/$1';
$route['admin/about_us/(:any)'] 		        = 'admin/admin/page_settings/$1';

$route['admin/transaction_fee'] 		        = 'admin/admin/transaction_fee';
$route['admin/points_earn'] 		       	    = 'admin/admin/points_earn';

$route['admin/logo_setting'] 		       		= 'admin/admin/logo_setting';

$route['admin/roles'] 		       				= 'admin/admin/roles';
$route['admin/set_role_permission'] 		    = 'admin/admin/set_role_permission';
$route['admin/staff'] 		   					= 'admin/admin/staff';
$route['admin/feedback'] 		   			    = 'admin/home/feedback';
$route['admin/set_transaction_limit'] 		    = 'admin/home/set_transaction_limit';



/**** ********************************User Routes *************************************************/

/*************************************Deposit Money***********************************************/
$route['account/addmoney'] 		   			    = 'account/depositmoney/addMoney';
$route['account/withdrawmoney'] 		   		= 'account/depositmoney/withdrawMoney';
$route['account/withdrawrequest'] 		     	= 'account/depositmoney/withdrawRequestList';
$route['account/withdrawrequest/(:any)'] 		= 'account/depositmoney/withdrawRequestList/$1';
$route['account/transaction_history'] 		    = 'account/depositmoney/getTransactionHistory';
$route['account/transaction_history/(:any)']    = 'account/depositmoney/getTransactionHistory/$1';
$route['account/transaction_history/(:any)/(:any)']= 'account/depositmoney/getTransactionHistory/$1/$2';

/*************************************Payment Request *******************************************/
$route['account/send_payment_request'] 		    = 'account/Paymentrequest/sendPaymentRequest';




