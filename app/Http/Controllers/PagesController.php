<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 

use App\TheLoai;
use App\Slide;
use App\LoaiTin;
use App\TinTuc;
use App\User;


class PagesController extends Controller
{
    //

	public function __construct() {
		$theloai = TheLoai::all();
		$slide = Slide::all();
		view()->share('theloai',$theloai);
		view()->share('slide',$slide);

		if(Auth::check()) {
			view()->share('nguoiDung',Auth::user());
		}
	}

	public function trangchu() {
		return view('pages.trangchu');
	}

	public function lienhe() {
		return view('pages.lienhe');
	}

	public function loaitin($id) {
		$loaitin = LoaiTin::find($id);
		$tintuc = TinTuc::where('idLoaiTin',$id)->paginate(5);
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

	public function getDangxuat() {
		Auth::logout();
		return redirect('trangchu');
	}

	public function getNguoiDung() {
		return view('pages.nguoidung');
	}

	public function postNguoiDung(Request $request) {
    	$this->validate($request,
    		[
    			'name' => 'required|min:3',
    		],
    		[
    			'name.required' => 'Bạn chưa nhập tên người dùng',
    			'name.min' => 'Tên người dùng phải có ít nhất 3 ký tự',
    		]);
    	$user = Auth::user();
    	$user->name = $request->name;
    	if($request->changePassword == "on") {
    	$this->validate($request,
    		[
    			'password' => 'required|min:3|max:32',
    			'passwordAgain' => 'required|same:password',
    		],
    		[
    			'password.required' => 'Bạn chưa nhập mật khẩu',
    			'password.min' => 'Mật khẩu phải có độ dài từ 3 đến 32 ký tự',
    			'password.max' => 'Mật khẩu phải có độ dài từ 3 đến 32 ký tự',
    			'passwordAgain.required' => 'Bạn chưa nhập lại mật khẩu',
    			'passwordAgain.same' => 'Mật khẩu nhập lại chưa khớp'
    		]);    		
    		$user->password = bcrypt($request->password);	
    	}
    	$user->save();
    	return redirect('nguoidung')->with('thongbao', 'Sửa thành công'); 
	}

	public function getDangky() {
		return view('pages.dangky');
	}

	public function postDangky(Request $request) {
    	$this->validate($request,
    		[
    			'name' => 'required|min:3',
    			'email' => 'required|email|unique:users,email',
    			'password' => 'required|min:3|max:32',
    			'passwordAgain' => 'required|same:password',
    		],
    		[
    			'name.required' => 'Bạn chưa nhập tên người dùng',
    			'name.min' => 'Tên người dùng phải có ít nhất 3 ký tự',
    			'email.required' => 'Bạn chưa nhập email',
    			'email.email' => 'Bạn chưa nhập đúng định dạng email',
    			'email.unique' => 'Email đã tồn tại',
    			'password.required' => 'Bạn chưa nhập mật khẩu',
    			'password.min' => 'Mật khẩu phải có độ dài từ 3 đến 32 ký tự',
    			'password.max' => 'Mật khẩu phải có độ dài từ 3 đến 32 ký tự',
    			'passwordAgain.required' => 'Bạn chưa nhập lại mật khẩu',
    			'passwordAgain.same' => 'Mật khẩu nhập lại chưa khớp'
    		]);
    	$user = new User;
    	$user->name = $request->name;
    	$user->email = $request->email;
    	$user->password = bcrypt($request->password);
    	$user->quyen = 0;
    	$user->save();
    	return redirect('dangky')->with('thongbao', 'Đăng ký thành công');
	}

	public function getTimkiem(Request $request) {
		$tukhoa = $request->get('tukhoa'); // nhận biến từ phương thức GET
		$tintuc = TinTuc::where('TieuDe','like',"%$tukhoa%")->orWhere('Tomtat','like',"%$tukhoa%")->orWhere('NoiDung','like',"%$tukhoa%")->paginate(5);
		return view('pages.timkiem')->with('tintuc',$tintuc)->with('tukhoa',$tukhoa);
	}

}
