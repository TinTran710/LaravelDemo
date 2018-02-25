<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct() {
    	$this->dangNhap();
    }

    function dangNhap() {
    	if(Auth::check()) { // kiểm tra có đang đăng nhập hay không
    		view()->share('user_login',Auth::user()); // truyền biến user tới tất cả các view
    	}
    }

}
