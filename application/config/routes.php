<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'Login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;



// admin
$route['forgot-password'] = 'forgot_password';
$route['password-change'] = 'password_change';




$route['accounts-list'] = 'accounts_list';
$route['accounts-add'] = 'accounts_add';
$route['comment_view'] = 'comment-view';

$route['all_group_post/(:num)'] = 'all-group-post/$1';


