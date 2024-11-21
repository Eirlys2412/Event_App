@extends('backend.layouts.master')
@section('content')
<div class="intro-y flex items-center mt-8">
    <h2 class="text-lg font-medium mr-auto">Thêm Sinh Viên</h2>
</div>
<div class="grid grid-cols-12 gap-12 mt-5">
    <div class="intro-y col-span-12 lg:col-span-12">
        <form method="post" action="{{ route('student.store') }}">
            @csrf
            <div class="intro-y box p-5">
                <div>
                    <label for="mssv" class="form-label">Mã số sinh viên</label>
                    <input id="mssv" name="mssv" type="text" class="form-control" placeholder="Mã số sinh viên" required>
                </div>
                <div class="mt-3">  
                    <label for="khoa" class="form-label">Khóa</label>
                    <input id="khoa" name="khoa" type="text" class="form-control" placeholder="Khóa" required>
                </div>
                <div class="mt-3">
                    <label for="donvi_id" class="form-label">Đơn vị</label>
                    <select name="donvi_id" class="form-select mt-2" required>
                        @foreach($donvis as $donvi)
                            <option value="{{ $donvi->id }}">{{ $donvi->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    <label for="nganh_id" class="form-label">Ngành</label>
                    <select name="nganh_id" class="form-select mt-2" required>
                        @foreach($nganhs as $nganh)
                            <option value="{{ $nganh->id }}">{{ $nganh->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mt-3">
                    <label for="status" class="form-label">Tình trạng</label>
                    <select name="status" class="form-select mt-2" required>
                        <option value="đang học" selected>Đang học</option>
                        <option value="thôi học">Thôi học</option>
                        <option value="tốt nghiệp">Tốt nghiệp</option>
                    </select>
                </div>
                
                <!-- Trường ẩn user_id tự động tạo từ khóa và mssv -->
                <input type="hidden" id="user_id" name="user_id" value="">

                <div class="text-right mt-5">
                    <button type="submit" class="btn btn-primary w-24">Lưu</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('mssv').addEventListener('input', setUserId);
    document.getElementById('khoa').addEventListener('input', setUserId);

    function setUserId() {
        const khoa = document.getElementById('khoa').value;
        const mssv = document.getElementById('mssv').value;
        document.getElementById('user_id').value = `${khoa}${mssv}`;
    }
</script>
@endsection
