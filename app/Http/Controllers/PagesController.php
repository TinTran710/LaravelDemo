<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

use App\TheLoai;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;


class PagesController extends Controller
{
    //

	public function __construct() {
		$theloai = TheLoai::all();
		$slide = Slide::all();
		view()->share('theloai',$theloai);
		view()->share('slide',$slide);
	}

	public function trangchu() {
		return view('pages.trangchu');
	}

	public function lienhe() {
		return view('pages.lienhe');
	}

	public function loaitin($id) {
		$loaitin = LoaiTin::find($id);
		$tintuc = TinTuc::where('idLoaiTin',$id)->paginate(5)->get();
		return view('pages.loaitin')->with('loaitin',$loaitin)->with('tintuc',$tintuc);
	}

	public function tintuc($id) {
		$tintuc = TinTuc::find($id);
		$tinNoiBat = TinTuc::where('NoiBat',1)->take(4)->get();
		$tinLienQuan = TinTuc::where('idLoaiTin',$tintuc->idLoaiTin)->take(4)->get();
		return view('pages.tintuc')->with('tintuc',$tintuc)->with('tinNoiBat',$tinNoiBat)->with('tinLienQuan',$tinLienQuan);
	}

	public function getDangnhap() {
		return view('pages.dangnhap');
	}

	public function postDangnhap(Request $request) {
        $this->validate($request, 
            [
                'email' => 'required',
                'password' => 'required|min:3|max:32',
            ], 
            [
                'email.required' => 'Bạn chưa nhập email',
                'password.required' => 'Bạn chưa nhập mật khẩu',
                'password.min' => 'Mật khẩu phải có độ dài từ 3 đến 32 ký tự',
                'password.max' => 'Mật khẩu phải có độ dài từ 3 đến 32 ký tự'
            ]);
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])) {
            return redirect('trangchu');
        } else {
            return redirect('dangnhap')->with('thongbao','Đăng nhập không thành công');
        }
	}

}
