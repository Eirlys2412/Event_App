<nav class="side-nav">
   
    <ul>
            <li>
                <a href="{{route('admin.home')}}" class="side-menu side-menu{{$active_menu=='dashboard'?'--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="home"></i></div>
                    <div class="side-menu__title"> Dashboard </div>
                </a>
            </li> 
           
        <!-- Blog -->
        <li>
            <a href="javascript:;.html" class="side-menu side-menu{{( $active_menu=='blog_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add' )?'--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="align-center"></i> </div>
                <div class="side-menu__title">
                    Bài viết
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{ ($active_menu=='blog_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{route('admin.blog.index')}}" class="side-menu {{$active_menu=='blog_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="compass"></i> </div>
                        <div class="side-menu__title">Danh sách bài viết </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.blog.create')}}" class="side-menu {{$active_menu=='blog_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm bài viết</div>
                    </a>
                </li>
                
                <li>
                    <a href="{{route('admin.blogcategory.index')}}" class="side-menu {{$active_menu=='blogcat_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="hash"></i> </div>
                        <div class="side-menu__title">Danh mục bài viết </div>
                    </a>
                </li>
          </ul>
        </li>
         <!-- Group Sidebar Menu -->
        <li>
            <a href="javascript:;.html" class="side-menu side-menu{{( $active_menu=='group_list'|| $active_menu=='group_add' )?'--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="align-center"></i> </div>
                <div class="side-menu__title">
                    Nhóm
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{ ($active_menu=='group_list'|| $active_menu=='group_add')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{route('admin.group.index')}}" class="side-menu {{$active_menu=='group_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="compass"></i> </div>
                        <div class="side-menu__title">Danh sách nhóm </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.group.create')}}" class="side-menu {{$active_menu=='group_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm nhóm</div>
                    </a>
                </li>
                
          </ul>
        </li>


        <!-- student -->
        <li>
            <a href="javascript:;" class="side-menu side-menu{{ ($active_menu == 'student_list' || $active_menu == 'student_add') ? '--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                <div class="side-menu__title">
                    Sinh Viên
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{ ($active_menu == 'student_list' || $active_menu == 'student_add') ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('student.index') }}" class="side-menu {{ $active_menu == 'student_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Danh sách Sinh Viên</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('student.create') }}" class="side-menu {{ $active_menu == 'student_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm Sinh Viên</div>
                    </a>
                </li>
            </ul>
        </li>
        
        {{-- nganh --}}
        <li>
            <a href="javascript:;" class="side-menu side-menu{{ ($active_menu == 'nganh_list' || $active_menu == 'nganh_add') ? '--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="align-center"></i> </div>
                <div class="side-menu__title">
                    Ngành
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{ ($active_menu == 'nganh_list' || $active_menu == 'nganh_add') ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.nganh.index') }}" class="side-menu {{ $active_menu == 'nganh_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="compass"></i> </div>
                        <div class="side-menu__title">Danh sách Ngành</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.nganh.create') }}" class="side-menu {{ $active_menu == 'nganh_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm Ngành</div>
                    </a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:;" class="side-menu  class="side-menu {{($active_menu =='ugroup_add'|| $active_menu=='ugroup_list' || $active_menu =='ctm_add'|| $active_menu=='ctm_list'  )?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                <div class="side-menu__title">
                    Người dùng 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{($active_menu =='ugroup_add'|| $active_menu=='ugroup_list' || $active_menu =='ctm_add'|| $active_menu=='ctm_list')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{route('admin.user.index')}}" class="side-menu {{$active_menu=='ctm_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                        <div class="side-menu__title">Danh sách người dùng</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.user.create')}}" class="side-menu {{$active_menu=='ctm_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm người dùng</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.ugroup.index')}}" class="side-menu {{$active_menu=='ugroup_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="circle"></i> </div>
                        <div class="side-menu__title">Ds nhóm người dùng</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.ugroup.create')}}" class="side-menu {{$active_menu=='ugroup_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm nhóm người dùng</div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Resource  -->
        <li>
            <a href="javascript:;" class="side-menu {{($active_menu=='resource_list'|| $active_menu=='resource_add'|| $active_menu=='resourcetype_list'|| $active_menu=='resourcelinktype_list')?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="file"></i> </div>
                <div class="side-menu__title">
                    Tài nguyên
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{($active_menu=='resource_list'|| $active_menu=='resource_add'|| $active_menu=='resourcetype_list'|| $active_menu=='resourcelinktype_list')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{route('admin.resources.index')}}" class="side-menu {{$active_menu=='resource_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                        <div class="side-menu__title">Danh sách tài nguyên</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.resources.create')}}" class="side-menu {{$active_menu=='resource_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm tài nguyên</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.resource-types.index')}}" class="side-menu {{$active_menu=='resourcetype_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="folder"></i> </div>
                        <div class="side-menu__title"> Loại tài nguyên </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.resource-link-types.index')}}" class="side-menu {{$active_menu=='resourcelinktype_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="link"></i> </div>
                        <div class="side-menu__title"> Loại liên kết tài nguyên </div>
                    </a>
                </li>
            </ul>
        </li>
        <!-- Book -->
        <li>
            <a href="javascript:;" class="side-menu {{ ($active_menu == 'book_list' || $active_menu == 'book_add' || $active_menu == 'booktype_list' || $active_menu == 'bookpoint_list') ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="book-open"></i> </div>
                <div class="side-menu__title">
                    Sách
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{ ($active_menu == 'book_list' || $active_menu == 'book_add' || $active_menu == 'booktype_list' || $active_menu == 'bookpoint_list') ? 'side-menu__sub-open' : '' }}">
                <li>
                    <a href="{{ route('admin.books.index') }}" class="side-menu {{ $active_menu == 'book_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Sách</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.books.create') }}" class="side-menu {{ $active_menu == 'book_add' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm sách</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.booktypes.index') }}" class="side-menu {{ $active_menu == 'booktype_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                        <div class="side-menu__title">Danh mục sách</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookpoints.index') }}" class="side-menu {{ $active_menu == 'bookpoint_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="star"></i> </div>
                        <div class="side-menu__title">Điểm cho sách</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookaccess.index') }}" class="side-menu {{ $active_menu == 'bookpoint_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="award"></i> </div>
                        <div class="side-menu__title">Điểm thưởng</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bookusers.index') }}" class="side-menu {{ $active_menu == 'bookpoint_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="user"></i> </div>
                        <div class="side-menu__title">Điểm người dùng</div>
                    </a>
                </li>
            </ul>
        </li>

        <!-- Motion -->
    <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='motion_list'|| $active_menu=='motion_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="smile"></i> </div>
            <div class="side-menu__title">
                Motion
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu=='motion_list'|| $active_menu=='motion_add')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.motion.index')}}" class="side-menu {{$active_menu=='motion_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                    <div class="side-menu__title">Danh sách motion</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.motion.create')}}" class="side-menu {{$active_menu=='motion_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title">Thêm motion</div>
                </a>
            </li>
            </ul>
        </li>
        <!-- Donvi -->
        <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='donvi_list' || $active_menu=='donvi_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
            <div class="side-menu__title">
                Đơn vị
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu=='donvi_list' || $active_menu=='donvi_add')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.donvi.index')}}" class="side-menu {{$active_menu=='donvi_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                    <div class="side-menu__title">Danh sách đơn vị</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.donvi.create')}}" class="side-menu {{$active_menu=='donvi_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title">Thêm đơn vị</div>
                </a>
            </li>
        </ul>
    </li>
       <!-- eventtype -->
<li>
    <a href="javascript:;" class="side-menu {{($active_menu=='event_list'|| $active_menu=='event_add')?'side-menu--active':''}}">
        <div class="side-menu__icon"> <i data-lucide="calendar"></i> </div> <!-- Thay đổi icon ở đây -->
        <div class="side-menu__title">
            Sự kiện
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{($active_menu=='event_list'|| $active_menu=='event_add')?'side-menu__sub-open':''}}">
        <li>
            <a href="{{route('admin.eventtype.index')}}" class="side-menu {{$active_menu=='event_list'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách sự kiện</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.eventtype.create')}}" class="side-menu {{$active_menu=='event_add'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm Sự kiện</div>
            </a>
        </li>
    </ul>
</li>
<li>
    <a href="javascript:;" class="side-menu {{($active_menu=='major_list'|| $active_menu=='major_add')?'side-menu--active':''}}">
        <div class="side-menu__icon"> 
            <i data-lucide="graduation-cap"></i> <!-- Thay đổi biểu tượng thành mũ tốt nghiệp -->
        </div>
        <div class="side-menu__title">
            Chuyên ngành
            <div class="side-menu__sub-icon transform"> 
                <i data-lucide="chevron-down"></i> 
            </div>
        </div>
    </a>
    <ul class="{{($active_menu=='major_list'|| $active_menu=='major_add')?'side-menu__sub-open':''}}">
        <li>
            <a href="{{route('admin.major.index')}}" class="side-menu {{$active_menu=='major_list'?'side-menu--active':''}}">
                <div class="side-menu__icon"> 
                    <i data-lucide="list"></i> 
                </div>
                <div class="side-menu__title">Danh sách chuyên ngành</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.major.create')}}" class="side-menu {{$active_menu=='major_add'?'side-menu--active':''}}">
                <div class="side-menu__icon"> 
                    <i data-lucide="plus"></i> 
                </div>
                <div class="side-menu__title">Thêm Chuyên ngành</div>
            </a>
        </li>
    </ul>
</li>

     <!-- setting menu -->
     <li>
        <a href="javascript:;.html" class="side-menu side-menu{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='log_list'||$active_menu=='banner_add'|| $active_menu=='banner_list')?'--active':''}}">
              <div class="side-menu__icon"> <i data-lucide="settings"></i> </div>
              <div class="side-menu__title">
                  Cài đặt
                  <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
              </div>
        </a>
        <ul class="{{($active_menu=='donvi_list' || $active_menu=='donvi_add')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.donvi.index')}}" class="side-menu {{$active_menu=='donvi_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                    <div class="side-menu__title">Danh sách đơn vị</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.donvi.create')}}" class="side-menu {{$active_menu=='donvi_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title">Thêm đơn vị</div>
                </a>
            </li>
        </ul>
    </li>

    <!-- Sidebar Chương Trình Đào Tạo -->
    <li>
        <a href="javascript:;" class="side-menu side-menu{{($active_menu=='chuongtrinhdaotao_list'||$active_menu=='chuongtrinhdaotao_add'||$active_menu=='chuongtrinhdaotao_edit') ? '--active' : ''}}">
            <div class="side-menu__icon"> <i data-lucide="book"></i> </div>
            <div class="side-menu__title">
                Chương trình đào tạo
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu=='chuongtrinhdaotao_list'||$active_menu=='chuongtrinhdaotao_add'||$active_menu=='chuongtrinhdaotao_edit') ? 'side-menu__sub-open' : ''}}">
        
            <li>
                <a href="{{route('admin.chuong_trinh_dao_tao.index')}}" class="side-menu {{$active_menu=='chuongtrinhdaotao_list' ? 'side-menu--active' : ''}}">
                    <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                    <div class="side-menu__title">Danh sách chương trình đào tạo</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.chuong_trinh_dao_tao.create')}}" class="side-menu {{$active_menu=='chuongtrinhdaotao_add' ? 'side-menu--active' : ''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus-circle"></i> </div>
                    <div class="side-menu__title">Thêm chương trình đào tạo</div>
                </a>
            </li>
        </ul>
    </li>

         <!-- setting menu -->
         <li>
            <a href="javascript:;.html" class="side-menu side-menu{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='log_list'||$active_menu=='banner_add'|| $active_menu=='banner_list')?'--active':''}}">
                  <div class="side-menu__icon"> <i data-lucide="settings"></i> </div>
                  <div class="side-menu__title">
                      Cài đặt
                      <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                  </div>
            </a>
            <ul class="{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='role_list'||$active_menu=='role_add'||$active_menu=='kiot'|| $active_menu=='setting_list'|| $active_menu=='banner_add'|| $active_menu=='banner_list')?'side-menu__sub-open':''}}">
                 
                  <li>
                      <a href="{{route('admin.role.index',1)}}" class="side-menu {{$active_menu=='role_list'||$active_menu=='role_add'?'side-menu--active':''}}">
                          <div class="side-menu__icon"> <i data-lucide="octagon"></i> </div>
                          <div class="side-menu__title"> Roles</div>
                      </a>
                  </li>
                  <li>
                      <a href="{{route('admin.cmdfunction.index',1)}}" class="side-menu {{$active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'?'side-menu--active':''}}">
                          <div class="side-menu__icon"> <i data-lucide="moon"></i> </div>
                          <div class="side-menu__title"> Chức năng</div>
                      </a>
                  </li>
                  <li>
                      <a href="{{route('admin.setting.edit',1)}}" class="side-menu {{$active_menu=='setting_list'?'side-menu--active':''}}">
                          <div class="side-menu__icon"> <i data-lucide="key"></i> </div>
                          <div class="side-menu__title"> Thông tin công ty</div>
                      </a>
                  </li>
                  
                  
              </ul>
        </li>
        <li>
            <a href="javascript:;.html" class="side-menu side-menu{{( $active_menu=='module_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add' )?'--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="book"></i> </div>
                <div class="side-menu__title">
                    Học phần
                    <div class="side-menu__sub-icon transform"> <i data-lucide="book"></i> </div>
                </div>
            </a>
            <ul class="{{ ($active_menu=='module_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{ route('admin.module.index') }}" class="side-menu {{ $active_menu == 'module_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="graduation-cap"></i></div>
                        <div class="side-menu__title">Danh sách học phần</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.module.create')}}" class="side-menu {{$active_menu=='blog_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title"> Thêm học phần</div>
                    </a>
                </li>
          </ul>
        </li>
        <!-- UserPage -->
<li>
    <a href="javascript:;" class="side-menu {{($active_menu=='userpage_list'|| $active_menu=='userpage_add')?'side-menu--active':''}}">
        <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
        <div class="side-menu__title">
            User Pages
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{($active_menu=='userpage_list'|| $active_menu=='userpage_add')?'side-menu__sub-open':''}}">
        <li>
            <a href="{{route('admin.userpage.index')}}" class="side-menu {{$active_menu=='userpage_list'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách User Pages</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.userpage.create')}}" class="side-menu {{$active_menu=='userpage_add'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm User Page</div>
            </a>
        </li>
    </ul>
</li>
         <!-- teacher -->
         <li>
            <a href="javascript:;" class="side-menu {{($active_menu=='teacher_list'|| $active_menu=='teacher_add')?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                <div class="side-menu__title">
                    Giảng Viên
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{($active_menu=='teacher_list'|| $active_menu=='teacher_add')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{route('admin.teacher.index')}}" class="side-menu {{$active_menu=='teacher_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Danh sách Giảng viên</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.teacher.create')}}" class="side-menu {{$active_menu=='teacher_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm giảng viên</div>
                    </a>
                </li>
            </ul>
        </li>
         <!-- Chi tiết chương trình -->
         <li>
            <a href="javascript:;" class="side-menu {{($active_menu=='program_details_list'|| $active_menu=='program_details_add')?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                <div class="side-menu__title">
                    Chi tiết chương trình
                    <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{($active_menu=='teacher_list'|| $active_menu=='program_details_add')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{route('admin.program_details.index')}}" class="side-menu {{$active_menu=='program_details_list'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                        <div class="side-menu__title">Danh sách chi tiết chương trình</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.program_details.create')}}" class="side-menu {{$active_menu=='program_details_add'?'side-menu--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                        <div class="side-menu__title">Thêm chi tiết chương trình</div>
                    </a>
                </li>
            </ul>
        </li>
    </ul>
    </nav>