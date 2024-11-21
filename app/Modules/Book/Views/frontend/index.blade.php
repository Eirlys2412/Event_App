@extends('frontend.layouts.master')

<style>
    .book-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
        padding: 1rem;
    }

    .book-container {
        display: flex;
        gap: 1rem;
        background-color: #ffffff;
        border-radius: 0.5rem;
        padding: 1rem;
        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        align-items: flex-start;
    }

    .book-container .book-image img {
        width: 120px;
        height: 160px;
        object-fit: cover;
        border-radius: 0.5rem;
    }

    .book-container .book-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .book-container .book-info h3 a {
        font-size: 1rem;
        font-weight: 600;
        color: #1a202c;
        text-decoration: none;
    }

    .book-container .book-info h3 a:hover {
        color: #3182ce;
    }

    .book-container .book-info p {
        font-size: 0.875rem;
        color: #6b7280;
        line-height: 1.25rem;
    }

    .badge {
        display: inline-block;
        padding: 0.2rem 0.5rem;
        background-color: #ffffff;
        color: #ff7f11 !important;
        border: 1px solid #ff7f11;
        border-radius: 0.25rem;
        font-size: 0.75rem;
        font-weight: 500;
    }

    .author-link {
        color: #3182ce;
        text-decoration: none;
        font-size: 0.875rem;
    }

    .author-link:hover {
        text-decoration: underline;
    }
</style>

@section('content')
    <div class="container mx-auto py-10">
        <h2 class="text-2xl font-bold mb-5 text-center">Tất cả Sách</h2>

        <!-- Danh sách sách -->
        <div class="book-list">
            @forelse ($books as $book)
                <div class="book-container">
                    <!-- Ảnh sách -->
                    <div class="book-image">
                        <a href="{{ route('front.book.show', $book->slug) }}" title="{{ $book->title }}">
                            <img src="{{ $book->photo }}" alt="{{ $book->title }}">
                        </a>
                    </div>

                    <!-- Thông tin sách -->
                    <div class="book-info">
                        <!-- Tiêu đề -->
                        <h3>
                            <a href="{{ route('front.book.show', $book->slug) }}">
                                {{ $book->title }}
                            </a>
                        </h3>
                        <!-- Tóm tắt -->
                        <p>
                            {{ \Illuminate\Support\Str::limit($book->summary, 100, '...') }}
                        </p>
                        <!-- Tác giả và Loại sách -->
                        <div class="flex justify-between items-center pt-2">
                            <!-- Tác giả -->
                            <div class="flex items-center space-x-1">
                                <svg class="w-4 h-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                    viewBox="0 0 24 24">
                                    <path
                                        d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.4 0-8.4 1.7-8.4 4.8v2.4h16.8v-2.4c0-3.1-5-4.8-8.4-4.8z" />
                                </svg>
                                <a href="#" class="author-link">
                                    {{ $book->user ? $book->user->full_name : 'N/A' }}
                                </a>
                            </div>
                            <!-- Loại sách -->
                            <span class="badge">
                                {{ $book->bookType ? $book->bookType->title : 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                <p class="col-span-full text-center text-gray-500">Không có sách nào được tìm thấy.</p>
            @endforelse
        </div>

        <!-- Phân trang -->
        <div class="mt-8">
            {{ $books->links('vendor.pagination.tailwind') }}
        </div>
    </div>
@endsection
