@extends('backend.layouts.master')

@section('scriptop')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')

<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">
        Chỉnh sửa Kết Quả Enroll
    </h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <!-- BEGIN: Form Layout -->

        <form method="post" action="{{ route('admin.enroll_results.update', $enrollResult->id) }}">
            @csrf
            @method('patch')
            <div class="intro-y box p-5">
                <!-- Enrollment -->
                <div class="mt-3">
                    <label for="enroll_id" class="form-label">Enrollment</label>
                    <select name="enroll_id" id="enroll_id" class="form-select" required>
                        @foreach($enrollment as $item)
                            <option value="{{ $item->id }}" 
                                    {{ $item->id == $enrollResult->enroll_id ? 'selected' : '' }}>
                                {{ $item->phancong_id }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- User -->
                <div class="mt-3">
                    <label for="user_id" class="form-label">Người tham gia</label>
                    <select name="user_id" id="user_id" class="form-select" required>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" 
                                    {{ $user->id == $enrollResult->user_id ? 'selected' : '' }}>
                                {{ $user->username }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hình thức thi -->
                <div class="mt-3">
                    <label for="hinhthucthi_id" class="form-label">Hình thức thi</label>
                    <select name="hinhthucthi_id" id="hinhthucthi_id" class="form-select" required>
                        @foreach($hinhthucthi as $ht)
                            <option value="{{ $ht->id }}" 
                                    {{ $ht->id == $enrollResult->hinhthucthi_id ? 'selected' : '' }}>
                                {{ $ht->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bộ đề -->
                <div class="mt-3">
                    <label for="bode_id" class="form-label">Bộ đề</label>
                    <select name="bode_id" id="bode_id" class="form-select" required>
                        <optgroup label="Bộ đề trắc nghiệm">
                            @foreach($boDeTracNghiem as $bode)
                                <option value="{{ $bode->id }}" 
                                        {{ $bode->id == $enrollResult->bode_id ? 'selected' : '' }}>
                                    {{ $bode->title }}
                                </option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Bộ đề tự luận">
                            @foreach($boDeTuLuan as $bode)
                                <option value="{{ $bode->id }}" 
                                        {{ $bode->id == $enrollResult->bode_id ? 'selected' : '' }}>
                                    {{ $bode->title }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                </div>

                <!-- Điểm -->
                <div class="mt-3">
                    <label for="grade" class="form-label">Điểm</label>
                    <input type="number" name="grade" id="grade" class="form-control" 
                           value="{{ old('grade', $enrollResult->grade) }}" step="0.1" min="0" max="100" required>
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

                <!-- Hiển thị lỗi -->
                <div class="mt-3">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>

                <!-- Nút submit -->
                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Cập nhật</button>
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
    cauHoiTracNghiem.forEach(cauHoi => mapCauHoiTracNghiem.set(cauHoi.id, cauHoi.content));

    const mapCauHoiTuLuan = new Map();
    cauHoiTuLuan.forEach(cauHoi => mapCauHoiTuLuan.set(cauHoi.id, cauHoi.content));

    function loadQuestions(hinhThucThi, bodeId) {
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
    }

    // Khi chọn hình thức thi
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

    // Khi chọn bộ đề
    bodeSelect.addEventListener('change', function () {
        const hinhThucThi = hinhthucthiSelect.value;
        const bodeId = this.value;
        loadQuestions(hinhThucThi, bodeId);
    });

    // Load dữ liệu khi tải trang
    const initialHinhThucThi = hinhthucthiSelect.value;
    const initialBoDeId = bodeSelect.value;

    if (initialHinhThucThi && initialBoDeId) {
        loadQuestions(initialHinhThucThi, initialBoDeId);
    }
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
