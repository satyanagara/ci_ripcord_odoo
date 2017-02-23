<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "api/3.0/b2b/bauth/test";
$route['404_override'] = '';

############################### SNS ROUTE   ######################
$route['api/3.0/user/auth/login/sns'] = "api/3.0/user/auth/login_sns";

$route['api/3.0/user/auth/password/reset'] = "api/3.0/user/auth/reset";
$route['api/3.0/user/auth/notification/register'] = "api/3.0/user/auth/registerNotification";


################################ REQUEST ROUTE ####################
$route['api/3.0/user/request/finished/types'] = "api/3.0/user/request/finish_type";

$route['api/3.0/user/profile/password/update'] = "api/3.0/user/profile/update_password";
$route['api/3.0/user/profile/email/verify'] = "api/3.0/user/profile/emailVerify";
$route['api/3.0/user/profile/mobile/code'] = "api/3.0/user/profile/mobileVerify"; #### verify code 

############################### ADRRESS ROUTE   ######################
$route['api/3.0/user/profile/address/add'] = "api/3.0/user/address/add";
$route['api/3.0/user/profile/address/update'] = "api/3.0/user/address/update";
$route['api/3.0/user/profile/address/delete'] = "api/3.0/user/address/delete";


//$route['api/3.0/user/profile/mobile/code'] = "api/3.0/user/profile/mobileVerify"; ##### request code

$route['api/3.0/user/profile/account/verify'] = "api/3.0/user/profile/accountVerify";
$route['api/3.0/user/notifications'] = "api/3.0/user/profile/notifications";
$route['api/3.0/user/profile/picture/update'] = "api/3.0/user/profile/upload";


############################## USER VENDOR ROUTE ########################
$route['api/3.0/user/vendors'] = "api/3.0/user/vendor";
$route['api/3.0/user/vendor/remove'] = "api/3.0/user/vendor/delete";
$route['api/3.0/user/vendor/service/types'] = "api/3.0/user/vendor/serviceType";
$route['api/3.0/user/vendor/services'] = "api/3.0/user/vendor/services";


//$route['api/3.0/user/vendor/reviews'] = "api/3.0/user/vendor/reviews";
$route['api/3.0/user/request/bid/accept'] = "api/3.0/user/request/accept";


//=================================== vendor routing 
$route['api/3.0/vendor/auth/password/reset'] = "api/3.0/vendor/auth/reset";
$route['api/3.0/vendor/profile/password/update'] = "api/3.0/vendor/profile/updatePassword";
$route['api/3.0/vendor/profile/picture/update'] = "api/3.0/vendor/profile/upload";
$route['api/3.0/vendor/auth/notification/register'] = "api/3.0/vendor/auth/registerNotification";

$route['api/3.0/vendor/profile/account/verify'] = "api/3.0/vendor/profile/accountVerify";
$route['api/3.0/vendor/services'] = "api/3.0/vendor/profile/services";
$route['api/3.0/vendor/service/types'] = "api/3.0/vendor/profile/serviceType";
$route['api/3.0/vendor/request/finished/types'] = "api/3.0/vendor/request/finish_type";

//$route['api/3.0/vendor/request/actives'] = "api/3.0/vendor/request/open";
//$route['api/3.0/vendor/request/bids'] = "api/3.0/vendor/request/actives";
//$route['api/3.0/vendor/order/finisheds'] = "api/3.0/vendor/request/finished";
$route['api/3.0/vendor/request/finisheds'] = "api/3.0/vendor/request/finished";

$route['api/3.0/vendor/staffs'] = "api/3.0/vendor/profile/staffs";

$route['api/3.0/vendor/reviews'] = "api/3.0/vendor/profile/reviews";

$route['api/3.0/vendor/profile/picture/update'] = "api/3.0/vendor/upload/action_upload";

$route['api/3.0/vendor/profile/mobile/code'] = "api/3.0/vendor/profile/mobileVerify";



$route['api/3.0/vendor/request/actives'] = "api/3.0/vendor/request/get_actives"; ### ADJUST ACTIVES ORDER VENDOR ROUTE ###
$route['api/3.0/vendor/request/bids'] = "api/3.0/vendor/request/get_my_picked_bids"; ### ADJUST ROUTE VENDOR REQEST #####
$route['api/3.0/vendor/request/detail'] = "api/3.0/vendor/request/req_details"; ## Get specified user request detail ##

$route['api/3.0/vendor/notifications'] = "api/3.0/vendor/profile/notifications";

############################### VOUCHER ROUTE   ######################
$route['api/3.0/user/vouchers'] = "api/3.0/user/voucher/get_voucher";

############################## VERITRANS ROUTE ########################
$route['api/3.0/settlement/notification'] = "api/3.0/user/request/notification";
$route['api/3.0/settlement/finish'] = "api/3.0/user/request/verification_success";
$route['api/3.0/settlement/unfinish'] = "api/3.0/user/request/verification_unfinish";
$route['api/3.0/settlement/error'] = "api/3.0/user/request/verification_fail";
$route['api/3.0/settlement/pending'] = "api/3.0/user/request/verification_pending";
$route['api/3.0/settlement/deny'] = "api/3.0/user/request/verification_deny";
$route['api/3.0/settlement/complete'] = "api/3.0/user/request/verification_complete";

$route['api/3.0/settlement/checkout'] = "api/3.0/user/request/vtweb_checkout";

$route['api/3.0/user/request/settlement/status'] = "api/3.0/user/request/settlement_status";

############################## AUTH TOKEN ROUTE ########################
$route['api/3.0/vendor/auth/error/(:any)'] = "api/3.0/check_auth/check_access/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */