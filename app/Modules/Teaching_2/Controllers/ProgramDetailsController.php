<?php

namespace App\Modules\Teaching_2\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log; // Thêm dòng này
use App\Modules\Teaching_2\Models\ProgramDetails;
use App\Modules\Teaching_2\Models\Module;
use App\Modules\Teaching_2\Models\ChuongTrinhDaoTao;


class ProgramDetailsController extends Controller
{
    //
    // Display the list of user pages
    public function index()
    {
        $active_menu = 'program_details_list';
        $program_details = ProgramDetails::paginate(10); // Thay đổi all() thành paginate()
        // dd($program_details); // Kiểm tra loại đối tượng
        return view('Teaching_2::program_details.index', compact('active_menu','program_details'));
    }

    // Show the form for creating a new user page
    public function create()
    {   $active_menu = 'program_details_add';
        $hocPhan = Module::all(); // Lấy tất cả đơn vị để chọn
        $chuongTrinhdaotao = ChuongTrinhDaoTao::all(); // Lấy tất cả người dùng để chọn
        return view('Teaching_2::program_details.create', compact('active_menu','hocPhan','chuongTrinhdaotao'));
    }

    public function store(Request $request)
    {
    $validatedData = $request->validate([
        'hocphan_id' => 'required|exists:modules,id', // Phải tồn tại trong bảng modules (id)
        'chuongtrinh_id' => 'required|exists:chuong_trinh_dao_tao,id', // Phải tồn tại trong bảng chuong_trinh_dao_tao (id)
        'hocky' => 'required|integer|min:1', // Bắt buộc, số nguyên, không nhỏ hơn 1
        'loai' => 'required|string|max:50|in:Bắt buộc,Tự chọn', // Bắt buộc, chuỗi, giá trị là "bắt buộc" hoặc "tự chọn"
        'hocphantienquyet' => 'nullable|json', // Nếu có giá trị, phải là JSON hợp lệ
        'hocphansongsong' => 'nullable|json', // Nếu có giá trị, phải là JSON hợp lệ
    ]);


    $latestHocPhan = Module::latest()->first();

    if ($latestHocPhan) {
        // Set the data for the ProgramDetails
        $programDetailsData = [
        'hocphan_id' => $validatedData['hocphan_id'], // Liên kết với bảng modules
        'chuongtrinh_id' => $validatedData['chuongtrinh_id'], // Liên kết với bảng chuong_trinh_dao_tao
        'hocky' => $validatedData['hocky'], // Số học kỳ
        'loai' => $validatedData['loai'], // Loại (bắt buộc hoặc tự chọn)
        'hocphantienquyet' => json_encode([
            'id' => $latestHocPhan->id,
        ],JSON_UNESCAPED_UNICODE),// JSON mặc định là mảng rỗng
        'hocphansongsong' => json_encode([
            'id' => $latestHocPhan->id,
        ],JSON_UNESCAPED_UNICODE),// JSON mặc định là mảng rỗng
    ];

        ProgramDetails::create($programDetailsData);

        return redirect()->route('admin.program_details.index')->with('success', 'Program Details created successfully.');
    }

    return redirect()->route('admin.program_details.index')->with('error', 'No blog data found.');
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
        $hocPhan = Module::all(); // Lấy tất cả đơn vị để chọn
        $chuongTrinhdaotao = ChuongTrinhDaoTao::all(); // Lấy tất cả người dùng để chọn// Cập nhật biến này
        return view('Teaching_2::program_details.edit', compact('program_details','hocPhan', 'chuongTrinhdaotao',  'active_menu'));
    }

    // Update a program_details
    public function update(Request $request,$id)
    {
        try {
            // dd($request->all());
            // dd($program_details);
            $program_details = ProgramDetails::findOrFail($id);
            $validatedData = $request->validate([
                'hocphan_id' => 'required|exists:modules,id',
                'chuongtrinh_id' => 'required|exists:chuong_trinh_dao_tao,id',
                'hocky' => 'required|string',
                'loai' => 'required|string',
            ]);
    
            Log::info('Dữ liệu validated:', $validatedData);
            
            $program_details->update($validatedData);
            $program_details->save();
            
    
            return redirect()->route('admin.program_details.index')->with('success', 'Program Details updated successfully.');
        } catch (\Exception $e) {
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
    // Tìm kiếm 
    public function search(Request $request)
    {
        $active_menu = 'program_details_list';
        $hocPhan = Module::all(); // Lấy tất cả đơn vị để chọn
        $chuongTrinhdaotao = ChuongTrinhDaoTao::all(); // Lấy tất cả người dùng để chọn
        $search = $request->input('datasearch');

        $program_details = ProgramDetails::with(['hocPhan', 'chuongTrinhdaotao'])
            ->where('id', 'LIKE', "%{$search}%")
            ->paginate(10);

        return view('Teaching_2::program_details.index', compact('program_details', 'hocPhan', 'chuongTrinhdaotao','active_menu', 'search'));
    }
}
