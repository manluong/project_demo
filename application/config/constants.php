<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESCTRUCTIVE') OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

//Config DIR Upload
define('DIR_UPLOAD_AVATAR', 'statics/uploads/avatar/');
define('DIR_UPLOAD_CV', 'assets/uploads/cv/');

// Begin: Datetime
define('DATETIME_FORMAT_DB', "Y-m-d H:i:s");
define('DATETIME_FORMAT_DB_NO_TIME', "Y-m-d");
define('TIME_FORMAT_DB', 'H:i:s');
define('DATETIME_FORMAT_FULL', 'd/m/Y H:i:s');
define('DATETIME_FORMAT_HYPHEN', 'd-m-Y-h-i-s');
define('DATETIME_FORMAT_VN_DATE', 'd/m/Y');
define('DATETIME_FORMAT_HOUR', 'H:i');
// End: Datetime

// Begin: Import
define('IMPORT_TYPE_USER', 'import_user');
define('IMPORT_USER_DIR', 'assets/uploads/import/user/');
define('IMPORT_TYPE_WINNER', 'import_winner');
define('IMPORT_WINNER_DIR', 'assets/uploads/import/winner/');
define('IMPORT_EMAIL', 'statics/uploads/import_email/');
// end: Import

// Begin: DEFINE STATUS
define('STATUS_REQUIRED', -1);
define('STATUS_FAIL', 0);
define('STATUS_SUCCESS', 1);
// End: DEFINE STATUS
define('IS_PRO', 1);
define('VAL_CHECKED', 1);
define('VAL_UNCHECK', 0);
define('EMPTY_PRICE', 0);
define('DEFAULT_ALBUM_ID', -1);

define('STYLE_NORMAL_SAVE', 1);
define('STYLE_NORMAL_UPDATE', 2);
define('STYLE_PROMO_SAVE', 3);
define('STYLE_PROMO_UPDATE', 4);

//responseCd
define('CODE_SUCCESS', 0);//success
define('CODE_FAIL', 1);//Login fail
define('CODE_USER_NOT_ACTIVE', 11);//user not acvite
//register
define('CODE_USERNAME_EXISTED', 5);//The username is existed
define('CODE_EMAIL_EXISTED', 6);//The email is existed
//reset password
define('CODE_EMAIL_NOT_EXIST', 14);
define('CODE_EMAIL_RESET_EXIST', 13);

define('CODE_USER_LOGING_SUCCESS', 0);
define('CODE_USER_LOGING_FAIL', 1);
define('CODE_REGISTER_SUCCESS', 0);
define('CODE_LOGIN_EXPIRED', 8);
define('CODE_DATA_EMPTY', 7);

//list status booking
define('BOOKING_STT_NEW', 0);
define('BOOKING_STT_CONFIRMED', 1);
define('BOOKING_STT_CANCELLED', 2);
define('BOOKING_STT_FINISHED', 3);
define('BOOKING_STT_RESCHEDULING', 4);
define('path_url_css','assets/uploads/css');

$PERMISSION_LIST = array
(
	'r' => 'read',
	'a' => 'approve',
	'w' => 'write',
	'd' => 'delete',
);

#Set GA constants
define('GA_DIMEN_HOURLY', 'hourly');
define('GA_DIMEN_DAY', 'day');
define('GA_DIMEN_WEEK', 'week');
define('GA_DIMEN_MONTH', 'month');
define('GA_DIMEN_BROWSER', 'browser');
define('GA_DIMEN_OS', 'os');
define('GA_DIMEN_COUNTRY', 'country');
define('GA_DIMEN_CITY', 'city');

define('GA_METRIC_SESSIONS', 'sessions');
define('GA_METRIC_USERS', 'users');
define('GA_METRIC_PAGEVIEWS', 'pageviews');
define('GA_METRIC_NEWUSERS', 'newUsers');
define('GA_METRIC_PAGEVIEWSPERSESSION', 'pageviewsPerSession');
define('GA_METRIC_AVGSESSIONDURATION', 'avgSessionDuration');
define('GA_METRIC_BOUNCERATE', 'bounceRate');
define('GA_METRIC_PERCENTNEWSESSIONS', 'percentNewSessions');

$INDUSTRY_LIST = array
(
	30 => 'Bất động sản',
	7 => 'Xây dựng',
	33 => 'Bán hàng',
	1 => 'Kế toán',
	39 => 'Khác',
);

$website_name = strtolower(WEBSITE_NAME);
define('FORM_SUBMIT_FAILED_URL', 'khong-thanh-cong-'.$website_name);