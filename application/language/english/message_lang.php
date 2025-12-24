<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

// API ERROR MESSAGES English

$lang["already_in"]           = "Already In Record";
$lang["success"]              = "Successfully";
$lang["record_not_found"]     = "No Records Found";
$lang["page_no"]              = "Page Number Is Required";
$lang["page_no_numeric"]      = "Page Number Should Be Numeric";
$lang["lat"]                  = "Latitude Is Required";
$lang["lng"]                  = "Longitude Is Required";
$lang["is_required"]          = "Is Required";
$lang["required"]             = "Required Fields Are Empty";
$lang["valid_id"]             = "Please Send Valid ID";
$lang["reset_password"]       = "Reset Password Successfully";
$lang["server_problem"]       = "Some Problem in Our Server Please Try after Some Time";
$lang["invalid_image"]        = "Image Is Not Supported";
$lang["account_verify"]       = "Please verify your account first.";
$lang["mobile_not_register"]  = "Mobile not register";
$lang["no_user_found"]        = "No User Found With This Number";
$lang["invalid_detail"]       = "Invalid Detail";
$lang["not_send_same_mobile"] = "Please do not send same Mobile number";
$lang["forgot_otp_send"]      = "A One time use password has been sent successfully to your registered mobile number.";
$lang["forgot_otp_send_to_email"]      = "A One time use password has been sent successfully to your registered email address.";
$lang["forgot_otp_not_send"]  = "A One time use password has not been sent to your registered mobile number and email.";
$lang["otp_already_send"]     = "A One time password already sent to your registered mobile number and email.";
$lang["email_not_exist"]      = "This Mobile Is Not In Our Record";
$lang["otp_verify"]           = "OTP Verified Successfully";
$lang["not_sendmoney_youself"] = "You cannot send money to yourself";

// Mobile Number Validation 
$lang["mobile_required"]   = "Mobile Number Is Required";
$lang["mobile_min_length"] = "Mobile Number Should Be 10-12 Digit";
$lang["mobile_max_length"] = "Mobile Number Should Be 10-12 Digit";
$lang["mobile_numeric"]    = "Mobile Number Should be Numeric";
$lang["mobile_unique"]     = "Mobile Number Should be Unique";

//organization name
$lang["organization_name"]       = "Organization Name Is Required";
$lang["organization_type"]       =  "Organization Type Is Required";
$lang["organization_address"]    = "address Is Required";
$lang["organization_phone"]      =  "phone Is Required";
$lang["organization_update"]     = "Organization Updated Successfully";
$lang["organization_add"]     = "Organization added Successfully";
$lang["organization_not_update"] = "Organization not Updated";


// OTP Validation 
$lang["otp_send"]           = "OTP Send Successful";
$lang["otp_varify"]         = "OTP Verify Successfully";
$lang["otp_already_varify"] = "OTP Already Verified";
$lang["otp_not_match"]      = "OTP Not Match";
$lang["otp_max_length"]     = "OTP Max length is 6";
$lang["otp_required"]       = "OTP is Required";
$lang["otp_numeric"]        = "OTP Should be Numeric";

// Email Validation
$lang["email_required"] = "Email Address is Required";
$lang["email_valid"]    = "Email Address is Invalid";
$lang["email_unique"]   = "Email Address is Already in Our Record Please login"; 

// Password Validation
$lang["password_required"]  = "Password is Required";
$lang["password_minlength"] = "Password Should Be 6-12 Characters";
$lang["password_maxlenght"] = "Password Should Be 6-12 Characters";

// Signup and Registration 
$lang["country_code_required"] = "Country Code Is Required";
$lang["country_code_length"]   = "Max Length of Country should be 4";

$lang["verification_type_0_1"] = "Verification Type between 0 to 1";
$lang["verify_id_required"]    = "Verification ID Number is Required";
$lang["verify_id_unique"]      = "Verification ID Should be Unique";
$lang["verify_front"]          = "Verification Image is Required";
$lang["document_file"]          = "Document is Required";

$lang["already_register"]    = "Already Register Please Login";
$lang["mobile_not_register"] = "Entered Mobile Number Not Registered";
$lang["thank_msg"]           = "Thank You! Please check your email to activate your account";

// Registration
$lang["first_name"]        = "First Name is Required";
$lang["last_name"]         = "Last Name is Required";
$lang["profile_update"]    = "Profile Successfully Updated";
$lang["profile_notupdate"] = "Profile Not Updated";
$lang["new_password"]      = "New Password Is Required";
$lang["addrsss"]      = "Address Is Required";

// Finger Tip 
$lang["finger_status"] = "Finger Status Changed";
$lang["finger_status_disable"] = "Fingerprint disabled successfully.";
$lang["finger_status_enable"]  = "Fingerprint enabled successfully.";
$lang["enable_finger"] = "Please Enable Finger Print Lock";
$lang["thank_msg1"]    = "Thank You! Please check your email to activate your account";

// Transaction Pin
$lang["old_pin_notmatch"] = "Old Transaction Pin Not Match";
$lang["pin_update"]       = "Transaction pin updated successfully";
$lang["pin_require"]      = "Transaction Pin is Required";
$lang["pin_length"]       = "Transaction Pin Lenght Should Be 4";
$lang["pin_numeric"]      = "Transaction Pin Should Be Numeric";

$lang["transaction_id"]         = "Transaction ID Is Required";
$lang["transaction_id_numeric"] = "Transaction ID Should Be Numeric";


$lang["transaction_type"]         = "Transaction Type Is Required";
$lang["transaction_type_numeric"] = "Transaction Type Should Be Numeric";
$lang["transaction_pin_succ"]     = "Transaction pin is correct";

// Login
$lang["login_success"]         = "Login Successfully";
$lang["fingerstatus_required"] = "Finger Status Is Required";
$lang["fingerstatus_length"]   = "Please Send Finger Status between 0 to 1";
$lang["fingercode_required"]   = "Finger Print Code Is Required";
$lang["user_block"]            = "User Is Blocked By Admin Or Not Verify by Email.Please Contact With Administrator.";
$lang["password_notmatch"]     = "Password Not Match";
$lang["register_first"]        = "This User Is Not Register. Please Sign Up First";

// Change Password
$lang["password_change_success"] = "Password Successfully Changed";
$lang["password_not_change"]     = "Password not Successfully Changed";
$lang["old_password_not"]        = "Old Password Not Match";
$lang["old_password"]            = "Old Password Is Required";


$lang["logout_success"]      = "Logout Successfully";
$lang["session_expire"]      = "Your Session Expire Please Login Again";
$lang["header_required"]     = "Required Headers Are Empty";
$lang["varify_token_userid"] = "Please Send Valid User Id And Token No";


// Help & Support Validation 
$lang["category_required"] = "Category Id Is Required";
$lang["category_numeric"]  = "Category Id Should be Numeric";

$lang["faq_required"] = "Faq Id Is Required";
$lang["faq_numeric"]  = "Faq Id Should be Numeric";

$lang["firstname"]        = "First Name is Required";
$lang["lastname"]         = "Last Name is Required";
$lang["subject_required"] = "Subject is Required";
$lang["msg_required"]     = "Message is Required";
$lang["server_error"]     = "Some Problem In Server Please Try Again Later";
$lang["feedback_success"] = "Thanks for submiting your valuable feedback";

//Saved Card Details Validation
$lang["security_code_required"]   = "Security code is Required";
$lang["security_code_min_length"] = "Security code should be 3 digit";
$lang["security_code_max_length"] = "Security code should be 3 digit";
$lang["security_code_numeric"]    = "Security code should be numeric";

$lang["card_required"]   = "Card is Required";
$lang["card_min_length"] = "Card should Be 16 digit";
$lang["card_max_length"] = "Card should Be 16 digit";
$lang["card_numeric"]    = "Card should be numeric";

$lang["expiry_year_required"]   = "Expiry year is Required";
$lang["expiry_year_min_length"] = "Expiry year should Be 4 digit";
$lang["expiry_year_max_length"] = "Expiry year should Be 4 digit";
$lang["expiry_year_numeric"]    = "Expiry year should be numeric";

$lang["expiry_month_required"]           = "Expiry month is Required";
$lang["expiry_month_min_length"]         = "Expiry month should Be 2 Digit";
$lang["expiry_month_less_than_equal_to"] = "Expiry month should Be less than or equal to 12";
$lang["expiry_month_greater_than"]       = "Expiry month should Be greater than 0";
$lang["expiry_month_numeric"]            = "Expiry month should be Numeric";

$lang["card_save_success"]         = "Card details saved successfully";
$lang["card_exist_already"]        = "Card details Already Exist";
$lang["card_update_success"]       = "Card Details Updated successfully";
$lang["invalid_expiry_year"]       = "Invalid expiry year";
$lang["invalid_expiry_year_month"] = "Invalid expiry month or year";
$lang["card_id_required"]          = "Card id is required";
$lang["card_id_numeric"]           = "Card id should be numeric";
$lang["card_delete_success"]       = "Card details deleted successfully";
$lang["invalid_card"]              = "Invalid Card";

//Saved Bank Details Validation
$lang["bank_name_required"]    = "Bank name is required";
$lang["branch_name_required"]  = "Branch name is required";

$lang["acc_number_required"]   = "Account number is required";
$lang["acc_number_min_length"] = "Account number should be 5-14 digit";
$lang["acc_number_max_length"] = "Account number should be 5-14 digit";
$lang["acc_number_numeric"]    = "Account number should be numeric";

$lang["bank_save_success"]    = "Bank details saved successfully";
$lang["bank_already_exist"]   = "Bank details already exist";
$lang["bank_update_success"]  = "Bank details updated successfully";
$lang["bank_delete_success"]  = "Bank details deleted successfully";

//Send Payment Request Module Validation
$lang["send_request_yourself_error"] = "You cannot send request to yourself";
$lang["mobile_invalid"]              = "Mobile number is incorrect!";
$lang["request_send_success"]        = "Payment request send successfully";
$lang["request_send_fail"]           = "Please Send atleast one Receiver details";
$lang["request_required"]            = "Request id is Required";
$lang["request_numeric"]             = "Request id should be numeric";
$lang["request_decline_success"]     = "Payment request decline successful";
$lang["request_decline_already"]     = "You have already decline request.";
$lang["request_id_invalid"]          = "Invalid request id";
$lang["invalid_pin"]                 = "Please enter valid pin!";
$lang["request_accept_success"]      = "Payment request accept successfully";
$lang["request_sendmoney_success"]   = "Your money has been send successfully pending or initiated";
$lang["sendmoney_accept_success"]    = "You have receive money successfully";
$lang["insufficient_balance"]        = "You dont have sufficient balance to accept this request.";
$lang["request_accept_already"]      = "You have already accepted request.";
$lang["request_cancel_already"]      = "You have already cancel request.";
$lang["request_cancel_success"]      = "Payment request cancel successful";
$lang["insufficient_balances"]       = "You dont have sufficient balance ";

//Withdraw Money Module Validation
$lang["withdraw_success"]        = "Withdraw money successfully";
$lang["withdraw_limit_exceed"]   = "Withdraw amount limit exceed.";
$lang["withdraw_request_accept"] = "Withdraw payment request accepted successfully";
$lang["withdraw_other_user"]     = "Other user dont have sufficient balance to withdraw money to your wallet";
$lang["already_withdraw_money"]  = "You have already withdraw money for this request.";
$lang["amount_req"]              = "Amount is required";
$lang["amount_numeric"]          = "Amount should be numeric";
$lang["amount_greater_than"]     = "Amount should be greater than 0";

//Send Money Module Validation
$lang["send_money_success"]      = "Send money successfully";
$lang["send_money_limit_exceed"] = "Send amount limit exceed.";
$lang["invalid_card_id"]         = "Invalid saved card id.";

//Cash Out Module Validation
$lang["cash_out_success"]      = "Cashout money successfully";
$lang["cash_out_limit_exceed"] = "Cashout amount limit exceed.";

//Share Bill Module validation
$lang["request_share_success"]         = "Share bill request send successfully";
$lang["share_request_accept_success"]  = "Share bill request accept successfully";
$lang["share_request_decline_success"] = "Share bill request decline successfully";
$lang["request_share_fail"]            = "Please Send atleast one Receiver details";

$lang["type_no"]                     = "Type number is required";
$lang["type_no_numeric"]             = "Type number should be numeric";
$lang["request_type_required"]       = "Request type is required";
$lang["addmoney_success"]            = "Money added successfully";
$lang["payment_success"]             = "Payment send successfully";
$lang["send_payment_yourself_error"] = "You cannot send payment to yourself.";

$lang["id_required"]                 = "Id is required";
$lang["id_numeric"]                  = "Id should be numeric";
$lang["notification_change_success"] = "Notification Change Successfully";
$lang["notification_on_success"]     = "Notification ON Successfully";
$lang["notification_off_success"]    = "Notification OFF Successfully";
$lang["not_updated"]                 = "Not updated";

$lang["is_download_req"]             = "Is download is required";
$lang["is_download_numeric"]         = "Is download should be numeric";
$lang['insufficient_wallet_balance'] = 'You dont have sufficient wallet balance.';
$lang['already_decline_request']     = 'You have already decline withdraw request.';
$lang['decline_request_success']     = 'Withdraw payment request decline successful';
$lang['transaction_history_list']    = 'Transaction History List';
$lang['dob_required']                = 'Date of Birth is Required';
$lang['age_required']                = 'Age is Required';
$lang['gender_required']             = 'Gender is Required';
$lang['ssn_required']                = 'Social Security Number is Required';
$lang['not_send_split_reuqest_yourself'] = 'You cannot send split request to yourself';
$lang['earn_point_history_list']         = 'Earn points History List';
$lang['daily_limit_exceed']              = 'Daily limit or amount exceed';
$lang['monthly_limit_exceed']            = 'Monthly limit or amount exceed';

//Add Friend
$lang["add_friend_success"]      = "Friend added successfully";
$lang["add_friend_fail"]         = "Friend not added";
$lang["receiver_name"]           = "Receiver name field is required";
$lang["already_friend"]          = "Your friend Already exist";
$lang["friend_list"]             = "Friend list";
$lang["user_chat_list"]          = "Users chat list";
$lang["user_blocked"]            = "User blocked successfully";
$lang["user_unblocked"]          = "User Unblocked successfully";
$lang["user_not_friend"]         = "This user is not your friend";
$lang["user_status_not_correct"] = "Please send correct status of this user";

//Banner
$lang["adv_details"] = "Advertisement Details";

//Reffral points
$lang["redeem_point_required"] = "Redeem point is required";
$lang["insufficient_referral_point"] = " You dont have sufficient referral points to add money to your wallet";
$lang['redeem_point_history_list']     = 'Redeem referral points history list';
$lang['earn_point_history_list']     = 'Earn referral points history list';

$lang['search_keyword_required']     = 'Search keyword required';
$lang['invalid_request']     = 'Invalid request';
$lang['delete_success']     = 'Deleted successfully';
$lang['delete_error']     = 'Error in deletion';

?>