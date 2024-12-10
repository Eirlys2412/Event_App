<?php

namespace App\Modules\Teaching_2\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Thêm dòng này
use App\Modules\Teaching_2\Models\ProgramDetails;
use App\Modules\Teaching_2\Models\HocPhan;
use App\Modules\Teaching_2\Models\ChuongTrinhDaoTao;


class ProgramDetailsController extends Controller
{
    //
    protected $pagesize;
    public function __construct( )
    {
        $this->pagesize = env('NUMBER_PER_PAGE','20');
        $this->middleware('auth');
        
    }
    public function index()
{
    $func = "program_details_list";
    if (!$this->check_function($func)) {
        return redirect()->route('unauthorized');
    }

    $active_menu = "program_details_list";
    $breadcrumb = '
        <li class="breadcrumb-item"><a href="#">/</a></li>
        <li class="breadcrumb-item active" aria-current="page"> Danh sách chi tiết chương trình </li>';

    // Lấy danh sách chi tiết chương trình đào tạo
    $program_details = ProgramDetails::orderBy('id', 'DESC')->paginate($this->pagesize);

    // Lấy danh sách học phần [id => title]
    $hocPhanList = HocPhan::pluck('title', 'id')->toArray();

    // Truyền dữ liệu vào view
    return view('Teaching_2::program_details.index', compact('program_details', 'breadcrumb', 'active_menu', 'hocPhanList'));
}


    // Show the form for creating a new user page
    public function create()
    {   $active_menu = 'program_details_add';
        $hocPhan = HocPhan::all(); // Lấy tất cả đơn vị để chọn
        $chuongTrinhdaotao = ChuongTrinhDaoTao::all(); // Lấy tất cả người dùng để chọn
        return view('Teaching_2::program_details.create', compact('active_menu','hocPhan','chuongTrinhdaotao'));
    }
    
    public function store(Request $request)
{
    // Validate dữ liệu từ request
    $validatedData = $request->validate([
        'hocphan_id' => 'required|exists:hoc_phans,id', // Phải tồn tại trong bảng modules (id)
        'chuongtrinh_id' => 'required|exists:chuong_trinh_dao_tao,id', // Phải tồn tại trong bảng chuong_trinh_dao_tao (id)
        'hocky' => 'required|integer|min:1', // Bắt buộc, số nguyên, không nhỏ hơn 1
        'loai' => 'required|string|max:50|in:Bắt buộc,Tự chọn', // Bắt buộc, chuỗi, giá trị là "Bắt buộc" hoặc "Tự chọn"
        'hocphantienquyet' => 'nullable|array', // Nếu có giá trị, phải là mảng
        'hocphantienquyet.*' => 'integer|exists:hoc_phans,id', // Các phần tử phải là số nguyên và tồn tại trong bảng modules
        'hocphansongsong' => 'nullable|array', // Nếu có giá trị, phải là mảng
        'hocphansongsong.*' => 'integer|exists:hoc_phans,id', // Các phần tử phải là số nguyên và tồn tại trong bảng modules
    ]);

    // Dữ liệu học phần tiên quyết
    $hocphanId = $validatedData['hocphan_id'];

    // Xử lý dữ liệu học phần tiên quyết
    $hocphantienquyet = null;
    if (!empty($validatedData['hocphantienquyet'])) {
        $hocphantienquyet = json_encode([
            'id' => $hocphanId, // Học phần chính
            'next' => $validatedData['hocphantienquyet'], // Các học phần tiên quyết
        ], JSON_UNESCAPED_UNICODE);
    }

    // Xử lý dữ liệu học phần song song
    $hocphansongsong = null;
    if (!empty($validatedData['hocphansongsong'])) {
        $hocphansongsong = json_encode([
            'id' => array_merge([$hocphanId], $validatedData['hocphansongsong']), // Học phần chính và song song
        ], JSON_UNESCAPED_UNICODE);
    }

    // Chuẩn bị dữ liệu để lưu
    $programDetailsData = [
        'hocphan_id' => $hocphanId,
        'chuongtrinh_id' => $validatedData['chuongtrinh_id'],
        'hocky' => $validatedData['hocky'],
        'loai' => $validatedData['loai'],
        'hocphantienquyet' => $hocphantienquyet,
        'hocphansongsong' => $hocphansongsong,
    ];

    // Lưu dữ liệu vào database
    ProgramDetails::create($programDetailsData);

    return redirect()->route('admin.program_details.index')->with('success', 'Program Details created successfully.');
}



    // Show a specific program_details
    public function show(ProgramDetails $program_details)
    {
        $active_menu = 'userpage_show'; // Cập nhật biến này
        
        return view('Teaching_2::program_details.index', compact('program_details', 'active_menu'));
    }

    // Show the form for editing an existing program_details
    public function edit($program_details)
    {
        $active_menu = 'program_details_edit'; 
        $program_details = ProgramDetails::findOrFail($program_details); // Tìm bản ghi theo ID
        $hocPhan = HocPhan::all(); // Lấy tất cả đơn vị để chọn
        $chuongTrinhdaotao = ChuongTrinhDaoTao::all(); // Lấy tất cả người dùng để chọn

        // Chuyển đổi dữ liệu học phần tiên quyết và học phần song song thành mảng nếu cần
        $hocphantienquyet = json_decode($program_details->hocphantienquyet, true) ?? [];
        $hocphansongsong = json_decode($program_details->hocphansongsong, true) ?? [];

        return view('Teaching_2::program_details.edit', compact('program_details', 'hocPhan', 'chuongTrinhdaotao', 'active_menu', 'hocphantienquyet', 'hocphansongsong'));
    }

    // Update a program_details
    // Update a program_details
public function update(Request $request, $id)
{
    try {
        // Tìm bản ghi theo ID
        $program_details = ProgramDetails::findOrFail($id);

        // Validate dữ liệu từ request
        $validatedData = $request->validate([
            'hocphan_id' => 'required|exists:modules,id',
            'chuongtrinh_id' => 'required|exists:chuong_trinh_dao_tao,id',
            'hocky' => 'required|integer|min:1',
            'loai' => 'required|string|max:50|in:Bắt buộc,Tự chọn',
            'hocphantienquyet' => 'nullable|array',
            'hocphantienquyet.*' => 'integer|exists:modules,id',
            'hocphansongsong' => 'nullable|array',
            'hocphansongsong.*' => 'integer|exists:modules,id',
        ]);

        // Xử lý dữ liệu học phần tiên quyết
        $hocphantienquyet = null;
        if (!empty($validatedData['hocphantienquyet'])) {
            $hocphantienquyet = json_encode([
                'id' => $validatedData['hocphan_id'], // Học phần chính
                'next' => $validatedData['hocphantienquyet'], // Các học phần tiên quyết
            ], JSON_UNESCAPED_UNICODE);
        }

        // Xử lý dữ liệu học phần song song
        $hocphansongsong = null;
        if (!empty($validatedData['hocphansongsong'])) {
            $hocphansongsong = json_encode([
                'id' => array_merge([$validatedData['hocphan_id']], $validatedData['hocphansongsong']), // Học phần chính và song song
            ], JSON_UNESCAPED_UNICODE);
        }

        // Cập nhật dữ liệu
        $program_details->update([
            'hocphan_id' => $validatedData['hocphan_id'],
            'chuongtrinh_id' => $validatedData['chuongtrinh_id'],
            'hocky' => $validatedData['hocky'],
            'loai' => $validatedData['loai'],
            'hocphantienquyet' => $hocphantienquyet,
            'hocphansongsong' => $hocphansongsong,
        ]);

        return redirect()->route('admin.program_details.index')->with('success', 'Program Details updated successfully.');
    } catch (\Exception $e) {
        // Ghi log lỗi và trả về thông báo lỗi
        Log::error('Lỗi khi cập nhật:', ['message' => $e->getMessage()]);
        return redirect()->back()->with('error', 'Đã xảy ra lỗi khi cập nhật dữ liệu.');
    }
}


    // Delete a program_details
    public function destroy($program_details)
    {
        $program_details = ProgramDetails::findOrFail($program_details);
        $program_details->delete();
        return redirect()->route('admin.program_details.index')->with('success', 'Program Details deleted successfully.');
    }
    public function search(Request $request)
    {
        $func = "program_details_list";
        if(!$this->check_function($func))
        {
            return redirect()->route('unauthorized');
        }
        if($request->datasearch)
        {
            $active_menu="program_details_list";
            $searchdata =$request->datasearch;
            $program_details = ProgramDetails::with(['hocPhan', 'chuongTrinhdaotao'])
            ->where('id', 'LIKE', "%{$searchdata}%")
            ->paginate($this->pagesize)->withQueryString();
            $breadcrumb = '
            <li class="breadcrumb-item"><a href="#">/</a></li>
            <li class="breadcrumb-item  " aria-current="page"><a href="'.route('admin.blog.index').'">Bài viết</a></li>
            <li class="breadcrumb-item active" aria-current="page"> tìm kiếm </li>';
            return view('Teaching_2::program_details.index',compact('program_details','breadcrumb','searchdata','active_menu'));
        }
        else
        {
            return redirect()->route('admin.program_details.index')->with('success','Không có thông tin tìm kiếm!');
        }

    }
    // Tìm kiếm 
    // public function search(Request $request)
    // {
    //     $active_menu = 'program_details_list';
    //     $hocPhan = Module::all(); // Lấy tất cả đơn vị để chọn
    //     $chuongTrinhdaotao = ChuongTrinhDaoTao::all(); // Lấy tất cả người dùng để chọn
    //     $search = $request->input('datasearch');

    //     $program_details = ProgramDetails::with(['hocPhan', 'chuongTrinhdaotao'])
    //         ->where('id', 'LIKE', "%{$search}%")
    //         ->paginate(10);

    //     return view('Teaching_2::program_details.index', compact('program_details', 'hocPhan', 'chuongTrinhdaotao','active_menu', 'search'));
    // }
}
