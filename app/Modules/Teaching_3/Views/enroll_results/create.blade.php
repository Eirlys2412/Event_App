@extends('backend.layouts.master')
@section('scriptop')

<meta name="csrf-token" content="{{ csrf_token() }}">

<script src="{{asset('js/js/tom-select.complete.min.js')}}"></script>
<link rel="stylesheet" href="{{ asset('/js/css/tom-select.min.css') }}">
@endsection

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Thêm kết quả khoá học
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->
        <form method="post" action="{{ route('admin.enroll_results.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="intro-y box p-5">
                <!-- Enroll -->
                <div class="mt-3">
                    <label for="enroll_id" class="form-label">Chọn Enroll</label>
                    <select name="enroll_id" class="form-select mt-2" required>
                        @foreach($enrollment as $enroll)
                            <option value="{{ $enroll->id }}">{{ $enroll->phancong_id}}</option>
                        @endforeach
                    </select>
                </div>
        
                <!-- User -->
                <div class="mt-3">
                    <label for="user_id" class="form-label">Người dùng</label>
                    <select name="user_id" class="form-select mt-2" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->username }}</option>
                        @endforeach
                    </select>
                </div>
        
                <!-- Hình thức thi -->
                <div class="mt-3">
                    <label for="hinhthucthi_id" class="form-label">Hình thức thi</label>
                    <select id="hinhthucthi_id" name="hinhthucthi_id" class="form-select mt-2" required>
                        <option value="">-- Chọn hình thức thi --</option>
                        <option value="1">Trắc nghiệm</option>
                        <option value="2">Tự luận</option>
                    </select>
                </div>

                <!-- Bộ đề -->
                <div class="mt-3">
                    <label for="bode_id" class="form-label">Chọn bộ đề</label>
                    <select id="bode_id" name="bode_id" class="form-select mt-2" required>
                        <option value="">-- Chọn bộ đề --</option>
                        <!-- Load bộ đề tự động bằng JavaScript -->
                    </select>
                </div>


        
                <!-- Grade -->
                <div class="mt-3">
                    <label for="grade" class="form-label">Điểm số</label>
                    <input type="number" id="grade" name="grade" class="form-control" step="0.01" min="0" max="100" value="{{ old('grade') }}" placeholder="Nhập điểm số (tùy chọn)">
                </div>
        
                <!-- Danh sách câu hỏi -->
                <div class="mt-3">
                    <label for="questions" class="form-label">Danh sách câu hỏi</label>
                    <table class="table table-bordered mt-2">
                        <thead>
                            <tr>
                                <th>ID Câu hỏi</th>
                                <th>Điểm</th>
                            </tr>
                        </thead>
                        <tbody id="question-list">
                            <!-- Dữ liệu sẽ được load bằng JavaScript -->
                        </tbody>
                    </table>
                </div>
        
                
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </form>
        
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const hinhthucthiSelect = document.getElementById('hinhthucthi_id');
    const bodeSelect = document.getElementById('bode_id');
    const questionList = document.getElementById('question-list');

    // Dữ liệu từ backend
    const boDeTracNghiem = @json($boDeTracNghiem);
    const boDeTuLuan = @json($boDeTuLuan);
    const cauHoiTracNghiem = @json($cauHoiTracNghiem);
    const cauHoiTuLuan = @json($cauHoiTuLuan);

    // Tạo map câu hỏi
    const mapCauHoiTracNghiem = new Map();
    cauHoiTracNghiem.forEach(cauHoi => {
    console.log('ID câu hỏi Trắc nghiệm:', cauHoi.id);
    mapCauHoiTracNghiem.set(cauHoi.id, cauHoi.content);
});
    const mapCauHoiTuLuan = new Map();
    cauHoiTuLuan.forEach(cauHoi => {
    console.log('ID câu hỏi Tự luận:', cauHoi.id);
    mapCauHoiTuLuan.set(cauHoi.id, cauHoi.content);
});

    hinhthucthiSelect.addEventListener('change', function () {
        const hinhThucThi = this.value;

        // Reset danh sách bộ đề và câu hỏi
        bodeSelect.innerHTML = '<option value="">-- Chọn bộ đề --</option>';
        questionList.innerHTML = '';

        if (hinhThucThi == 1) {
            boDeTracNghiem.forEach(boDe => {
                bodeSelect.innerHTML += `<option value="${boDe.id}">${boDe.title}</option>`;
            });
        } else if (hinhThucThi == 2) {
            boDeTuLuan.forEach(boDe => {
                bodeSelect.innerHTML += `<option value="${boDe.id}">${boDe.title}</option>`;
            });
        }
    });

    bodeSelect.addEventListener('change', function () {
        const bodeId = this.value;
        const hinhThucThi = hinhthucthiSelect.value;

        // Reset danh sách câu hỏi
        questionList.innerHTML = '';

        if (bodeId) {
            let selectedBoDe;

            if (hinhThucThi == 1) {
                selectedBoDe = boDeTracNghiem.find(boDe => boDe.id == bodeId);
            } else if (hinhThucThi == 2) {
                selectedBoDe = boDeTuLuan.find(boDe => boDe.id == bodeId);
            }

            if (selectedBoDe) {
    try {
        const questions = JSON.parse(selectedBoDe.questions);
        questions.forEach(question => {
            let questionContent;
            if (hinhThucThi == 1) {
                questionContent = mapCauHoiTracNghiem.get(parseInt(question.id_question));
            } else if (hinhThucThi == 2) {
                questionContent = mapCauHoiTuLuan.get(parseInt(question.id_question));
            }
            
            questionList.innerHTML += `
                <tr>
                    <td>${questionContent || 'Không tìm thấy nội dung'}</td>
                    <td>${question.points}</td>
                </tr>
            `;
        });
    } catch (e) {
        console.error('Error parsing questions:', e);
    }
}
        }
    });
});


</script>
<script>
    var select = new TomSelect('#select-tags', {
        maxItems: null,
        allowEmptyOption: true,
        plugins: ['remove_button'],
        sortField: {
            field: "text",
            direction: "asc"
        },
        onItemAdd: function() {
            this.setTextboxValue('');
            this.refreshOptions();
        },
        create: true
    });
    select.clear();
</script>

<script src="{{asset('js/js/ckeditor.js')}}"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#questions'), {
            mediaEmbed: { previewsInData: true }
        })
        .then(editor => {
            console.log(editor);
        })
        .catch(error => {
            console.error(error);
        });
</script>
<script>
    document.getElementById('hinhthucthi_id').addEventListener('change', function () {
        const hinhThucThi = this.value;
        const tracNghiemGroup = document.getElementById('tracnghiem_group');
        const tuLuanGroup = document.getElementById('tuluan_group');

        // Hiển thị nhóm bộ đề phù hợp
        if (hinhThucThi == '1') { // Giả sử 1 là ID của hình thức thi trắc nghiệm
            tracNghiemGroup.style.display = 'block';
            tuLuanGroup.style.display = 'none';
        } else if (hinhThucThi == '2') { // Giả sử 2 là ID của hình thức thi tự luận
            tracNghiemGroup.style.display = 'none';
            tuLuanGroup.style.display = 'block';
        } else {
            tracNghiemGroup.style.display = 'none';
            tuLuanGroup.style.display = 'none';
        }
    });
</script>
@endsection
