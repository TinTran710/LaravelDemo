<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\TheLoai;

class TheLoaiController extends Controller
{
    //
    public function getDanhSach() {
    	$theloai = TheLoai::all();
    	return view('admin.theloai.danhsach')->with('theloai',$theloai);
    }

    public function getThem() {
    	return view('admin.theloai.them');	
    }

    public function postThem(Request $request) {
    	$this->validate($request,
    		[
    			'Ten' => 'required|unique:TheLoai,Ten|min:3|max:100'
    		],
    		[
    			'Ten.required'=>'Bạn chưa nhập tên thể loại',
                'Ten.unique' => 'Tên thể loại đã tồn tại',
    			'Ten.min'=>'Tên thể loại phải có độ dài từ 3 cho đến 100 ký tự',
    			'Ten.max'=>'Tên thể loại phải có độ dài từ 3 cho đến 100 ký tự'
    		]);
    	$theloai = new TheLoai;
    	$theloai->Ten = $request->Ten;
    	$theloai->TenKhongDau = changeTitle($request->Ten);
    	$theloai->save();
    	return redirect('admin/theloai/them')->with('thongbao','Thêm thành công');
    }

    public function getSua($id) {
    	$theloai = TheLoai::find($id);
        return view('admin.theloai.sua',['theloai'=>$theloai]);
    }

    public function postSua(Request $request, $id) {
        $theloai = TheLoai::find($id);
        $this->validate($request,
        [
            'Ten' => 'required|unique:TheLoai,Ten|min:3|max:100'
        ],
        [
            'Ten.required' => 'Bạn chưa nhập tên thể loại',
            'Ten.unique' => 'Tên thể loại đã tồn tại',
            'Ten.min'=>'Tên thể loại phải có độ dài từ 3 cho đến 100 ký tự',
            'Ten.max'=>'Tên thể loại phải có độ dài từ 3 cho đến 100 ký tự'
        ]);
        $theloai->Ten = $request->Ten;
        $theloai->TenKhongDau = changeTitle($request->Ten);
        $theloai->save();
        return redirect('admin/theloai/sua/'.$id)->with('thongbao','Sửa thành công');
    }

    public function getXoa($id) {
        $theloai = TheLoai::find($id);
        $theloai->delete();
        $thongbao = 'Xóa thành công';
        return redirect('admin/theloai/danhsach')->with('thongbao','Xóa thành công');
        // nếu return view() thì biến $theloai ở hàm đầu tiên sẽ không nhận được bên view
        // nếu return redirect khi bản thân route đã return view ở hàm khác thì ở route này biến phải ở đây là dạng flash session()
        // => return view() và redirect() khác nhau ở chỗ redirect() sẽ gọi lại màn hình đã chứa các biến từ trước đó còn view() gọi lại màn hình với các biến cài lại từ đầu
        // muốn truyền biến flash session thì phải dùng cấu trúc return redirect()->with() như trên
        // truyền biến sang view có thể dùng nhiều cách như [''=>] hoặc with()...
    }
}
