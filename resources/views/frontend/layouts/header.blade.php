<header class="relative wrapper bg-soft-primary !bg-[#edf2fc]">
    <nav class="navbar navbar-expand-lg center-nav navbar-light navbar-bg-light">
        <div class="container xl:flex-row lg:flex-row !flex-nowrap items-center">
            <div class="navbar-brand w-full">
                <a href="{{ route('home') }}">
                    <img src="{{ $setting->logo }}" srcset="{{ $setting->logo }} 2x" alt="Logo LMS">
                </a>
            </div>
            <div class="navbar-collapse offcanvas offcanvas-nav offcanvas-start">
                <div class="offcanvas-header xl:hidden lg:hidden flex items-center justify-between flex-row p-6">
                    <h3 class="text-white xl:text-[1.5rem] !text-[calc(1.275rem_+_0.3vw)] !mb-0">
                        {{ $setting->short_name }}</h3>
                    <button type="button"
                        class="btn-close btn-close-white mr-[-0.75rem] m-0 p-0 leading-none title_color transition-all duration-[0.2s] ease-in-out border-0 motion-reduce:transition-none before:text-[1.05rem] before:content-['\ed3b'] before:w-[1.8rem] before:h-[1.8rem] before:leading-[1.8rem] before:shadow-none before:transition-[background] before:duration-[0.2s] before:ease-in-out before:flex before:justify-center before:items-center before:m-0 before:p-0 before:rounded-[100%] hover:no-underline bg-inherit before:bg-[rgba(255,255,255,.08)] before:font-Unicons hover:before:bg-[rgba(0,0,0,.11)] focus:outline-0"
                        data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body xl:!ml-auto lg:!ml-auto flex flex-col !h-full">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="">Khóa học</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">Bài tập</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">Quiz</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{route('front.book.index' )}}">Sách</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">Tài nguyên</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="">Hồ sơ của tôi</a>
                        </li>
                    </ul>
                    <!-- /.navbar-nav -->
                    <div class="offcanvas-footer xl:hidden lg:hidden">
                        <div>
                            <a href="mailto:first.{{ $setting->email }}" class="link-inverse">{{ $setting->email }}</a>
                            <br> {{ $setting->hotline }}<br>
                            <nav class="nav social social-white mt-4">
                                <a class="text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                    href="{{ $setting->facebook }}">
                                    <img src="" class=" " />
                                </a>
                                <a class="text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                    href="{{ $setting->shopee }}">
                                    <img src="" class=" " />
                                </a>
                                <a class="text-[#cacaca] text-[1rem] transition-all duration-[0.2s] ease-in-out translate-y-0 motion-reduce:transition-none hover:translate-y-[-0.15rem] m-[0_.7rem_0_0]"
                                    href="{{ $setting->lazada }}">
                                    <img src="" class=" " />
                                </a>
                            </nav>
                            <!-- /.social -->
                        </div>
                    </div>
                    <!-- /.offcanvas-footer -->
                </div>
                <!-- /.offcanvas-body -->
            </div>
            <!-- /.navbar-collapse -->
            <div class="navbar-other w-full !flex !ml-auto">
                <ul class="navbar-nav !flex-row !items-center !ml-auto">
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas-search"><i
                                class="uil uil-search before:content-['\eca5'] !text-[1.1rem]"></i></a></li>
                    <li class="nav-item"><a class="nav-link" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvas-user"><i
                                class="uil uil-user before:content-['\eca5'] !text-[1.1rem]"></i></a></li>
                    <li class="nav-item xl:hidden lg:hidden">
                        <button class="hamburger offcanvas-nav-btn"><span></span></button>
                    </li>
                </ul>
                <!-- /.navbar-nav -->
            </div>
            <!-- /.navbar-other -->
        </div>
        <!-- /.container -->
    </nav>

    <!-- Offcanvas User -->
    <div style="width:20rem" class="offcanvas offcanvas-end bg-light" id="offcanvas-user" data-bs-scroll="true">
        <div class="offcanvas-header flex items-center justify-between p-[1.5rem]">
            <h3 class="mb-0">{{ !$user ? 'ĐĂNG NHẬP' : 'THÔNG TIN TÀI KHOẢN' }} </h3>
            <button type="button"
                class="btn-close m-0 p-0 mr-[-.5rem] leading-none title_color transition-all duration-[0.2s] ease-in-out  border-0 motion-reduce:transition-none before:text-[1.05rem] before:content-['\ed3b'] before:w-[1.8rem] before:h-[1.8rem] before:leading-[1.8rem] before:shadow-none before:transition-[background] before:duration-[0.2s] before:ease-in-out before:flex before:justify-center before:items-center before:m-0 before:p-0 before:rounded-[100%] hover:no-underline bg-inherit before:bg-[rgba(0,0,0,.08)] before:font-Unicons hover:before:bg-[rgba(0,0,0,.11)] focus:outline-0"
                data-bs-dismiss="offcanvas" aria-label="Đóng"></button>
        </div>

    </div>

    <!-- Offcanvas Search -->
    <div class="offcanvas offcanvas-top bg-light" id="offcanvas-search" data-bs-scroll="true">
        <div class="container flex !flex-row py-6">
            <form method="GET" action=""
                class="search-form relative before:content-['\eca5'] before:block before:absolute before:-translate-y-2/4 before:text-[1rem] before:text-[#343f52] before:z-[1] before:right-auto before:top-2/4 before:font-Unicons w-full before:left-0 focus:!outline-offset-0 focus:outline-0">
                <input name="searchdata" placeholder="Tìm kiếm khóa học..." id="search-form1" type="text"
                    class="form-control text-[0.8rem] !shadow-none pl-[1.75rem] !pr-[.75rem] border-0 bg-inherit m-0 block w-full font-medium leading-[1.7] text-[#60697b] px-4 py-[0.6rem] rounded-[0.4rem] focus:!outline-offset-0 focus:outline-0"
                    placeholder="Tìm kiếm khóa học, bài tập, hoặc tài nguyên">
            </form>
            <button type="button"
                class="btn-close leading-none title_color transition-all duration-[0.2s] ease-in-out p-0 border-0 motion-reduce:transition-none before:text-[1.05rem] before:content-['\ed3b'] before:w-[1.8rem] before:h-[1.8rem] before:leading-[1.8rem] before:shadow-none before:transition-[background] before:duration-[0.2s] before:ease-in-out before:flex before:justify-center before:items-center before:m-0 before:p-0 before:rounded-[100%] hover:no-underline bg-inherit before:bg-[rgba(0,0,0,.08)] before:font-Unicons hover:before:bg-[rgba(0,0,0,.11)] focus:outline-0"
                data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
    </div>
</header>
