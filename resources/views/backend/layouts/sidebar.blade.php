<nav class="side-nav">
   
    <ul>
            <li>
                <a href="{{route('admin.home')}}" class="side-menu side-menu{{$active_menu=='dashboard'?'--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="home"></i></div>
                    <div class="side-menu__title"> Dashboard </div>
                </a>
            </li> 
           
        <!-- Blog -->
        <!-- <li>
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
        </li> -->
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
        <!-- <li>
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
        </li> -->
        
        {{-- nganh --}}
        <!-- <li>
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
        </li> -->
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
        

        <!-- Motion -->
    <!-- <li>
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
        </li> -->
        <!-- Donvi -->
        <!-- <li>
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
    </li> -->

    <!-- Class -->
    <!-- <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='class_list' || $active_menu=='class_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
            <div class="side-menu__title">
               Lớp học
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu=='class_list' || $active_menu=='class_add')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.class.index')}}" class="side-menu {{$active_menu=='class_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                    <div class="side-menu__title">Danh sách lớp học</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.class.create')}}" class="side-menu {{$active_menu=='class_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title">Thêm Lớp học</div>
                </a>
            </li>
        </ul>
    </li> -->


    <!-- Event -->
<li>
    <a href="javascript:;" class="side-menu {{($active_menu=='event_list' || $active_menu=='event_add')?'side-menu--active':''}}">
        <div class="side-menu__icon"> <i data-lucide="calendar"></i> </div>
        <div class="side-menu__title">
            Sự kiện
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{($active_menu=='event_list' || $active_menu=='event_add')?'side-menu__sub-open':''}}">
        <li>
            <a href="{{route('admin.event.index')}}" class="side-menu {{$active_menu=='event_list'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                <div class="side-menu__title">Danh sách sự kiện</div>
            </a>
        </li>
        <li>
            <a href="{{route('admin.event.create')}}" class="side-menu {{$active_menu=='event_add'?'side-menu--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm sự kiện</div>
            </a>
        </li>

        <li>
            <a href="{{ route('admin.event_type.index') }}" class="side-menu {{$active_menu=='event_type_list' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                <div class="side-menu__title"> Event Type</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.event_type.create') }}" class="side-menu {{$active_menu=='event_type_add' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Add Event Type</div>
            </a>
        </li>

        <!-- Thêm mục Event Group -->
        <li>
            <a href="{{ route('admin.event_group.index') }}" class="side-menu {{$active_menu=='event_group_list' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                <div class="side-menu__title">Danh sách nhóm sự kiện</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.event_group.create') }}" class="side-menu {{$active_menu=='event_group_add' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm nhóm sự kiện</div>
            </a>
        </li>
<!--event attendance-->
        <li>
            <a href="{{ route('admin.event_attendance.index') }}" class="side-menu {{$active_menu=='event_attendance_list' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                <div class="side-menu__title"> Event attendance</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.event_attendance.create') }}" class="side-menu {{$active_menu=='event_attendance_add' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Add Event Attendance</div>
            </a>
        </li>

        <!--event user-->
        <li>
            <a href="{{ route('admin.event_user.index') }}" class="side-menu {{$active_menu=='event_user_list' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                <div class="side-menu__title"> Event user</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.event_user.create') }}" class="side-menu {{$active_menu=='event_user_add' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Add Event User</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.event_registration.index') }}" class="side-menu {{$active_menu=='event_registration_list' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                <div class="side-menu__title"> Event registration</div>
            </a>
        </li>   
        
        <li>
            <a href="javascript:;" class="side-menu side-menu{{in_array($active_menu,['community_groups','community_posts'])?'--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="users"></i> </div>
                <div class="side-menu__title">
                    Cộng đồng
                    <div class="side-menu__sub-icon {{in_array($active_menu,['community_groups','community_posts'])?'transform rotate-180':''}}"> <i data-lucide="chevron-down"></i> </div>
                </div>
            </a>
            <ul class="{{in_array($active_menu,['community_groups','community_posts'])?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{route('admin.community.groups.index')}}" class="side-menu side-menu{{$active_menu=='community_groups'?'--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="layers"></i> </div>
                        <div class="side-menu__title"> Nhóm cộng đồng </div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.community.posts.index')}}" class="side-menu side-menu{{$active_menu=='community_posts'?'--active':''}}">
                        <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
                        <div class="side-menu__title"> Bài đăng cộng đồng </div>
                    </a>
                </li>
            </ul>
        </li>

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

    <li>
    <a href="javascript:;.html" class="side-menu side-menu {{($active_menu =='comment_add'|| $active_menu=='comment_list') ? 'side-menu--active' : ''}}">
        <div class="side-menu__icon"> <i data-lucide="message-square"></i> </div>
        <div class="side-menu__title">
            Bình luận   
            <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{($active_menu =='comment_add'|| $active_menu=='comment_list') ? 'side-menu__sub-open' : ''}}">
        <li>
            <a href="{{route('admin.comments.index')}}" class="side-menu {{$active_menu=='comment_list' ? 'side-menu--active' : ''}}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách bình luận</div>
            </a>
        </li>
        
    </ul>
</li>
<li>
        <a href="javascript:;" class="side-menu side-menu{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='interact_list'||$active_menu=='interact_add'||$active_menu=='kiot'|| $active_menu=='interactype_list'|| $active_menu=='interactype_add'||$active_menu=='banner_add'|| $active_menu=='banner_list')?'--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="book"></i> </div>
                <div class="side-menu__title">
                    Tương tác 
                    <div class="side-menu__sub-icon "> <i data-lucide="chevron-down"></i> </div>
                </div>
        </a>
        <ul class="{{($active_menu=='cmdfunction_list'||$active_menu=='cmdfunction_add'||$active_menu=='interact_list'||$active_menu=='interact_add'||$active_menu=='kiot')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.userpage.index')}}" class="side-menu {{$active_menu=='interact_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="user-check"></i> </div>
                    <div class="side-menu__title">Cá nhân </div>
                </a>
            </li>
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
                <!-- <li>
                    <a href="" class="side-menu">
                        <div class="side-menu__icon"> <i data-lucide="activity"></i> </div>
                        <div class="side-menu__title"> Top Menu </div>
                    </a>
                </li> -->
            </ul>
        </li>
        <li>
        <a href="javascript:;" class="side-menu {{ in_array($active_menu, ['vnpay_config', 'vnpay_transactions']) ? 'side-menu--active' : '' }}">
            <div class="side-menu__icon"> <i data-lucide="credit-card"></i> </div>
            <div class="side-menu__title">
                Quản lý thanh toán
                <div class="side-menu__sub-icon"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{ in_array($active_menu, ['vnpay_config', 'vnpay_transactions']) ? 'side-menu__sub-open' : '' }}">
            <!-- Cấu hình VNPay -->
            <li>
                <a href="{{ route('admin.vnpay.config') }}" class="side-menu {{ $active_menu == 'vnpay_config' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="settings"></i> </div>
                    <div class="side-menu__title">Cấu hình VNPay</div>
                </a>
            </li>
            <!-- Lịch sử giao dịch -->
            <li>
                <a href="{{ route('admin.vnpay.payment-form') }}" 
                   class="side-menu {{ $active_menu == 'vnpay_transactions' ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"> <i data-lucide="dollar-sign"></i> </div>
                    <div class="side-menu__title">Tạo thanh toán</div>
                </a>
            </li>
        </ul>
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
    <!-- <li>
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
    </li> -->

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
            <a href="javascript:;.html" class="side-menu side-menu{{( $active_menu=='hocphan_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add' )?'--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="book"></i> </div>
                <div class="side-menu__title">
                    Học phần
                    <div class="side-menu__sub-icon transform"> <i data-lucide="book"></i> </div>
                </div>
            </a>
            <ul class="{{ ($active_menu=='hocphan_list'|| $active_menu=='blog_add'||$active_menu=='blogcat_list'|| $active_menu=='blogcat_add')?'side-menu__sub-open':''}}">
                <li>
                    <a href="{{ route('admin.hocphan.index') }}" class="side-menu {{ $active_menu == 'hocphan_list' ? 'side-menu--active' : '' }}">
                        <div class="side-menu__icon"> <i data-lucide="graduation-cap"></i></div>
                        <div class="side-menu__title">Danh sách học phần</div>
                    </a>
                </li>
                <li>
                    <a href="{{route('admin.hocphan.create')}}" class="side-menu {{ $active_menu == 'hocphan_list' ? 'side-menu--active' : '' }}">
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
         <!-- <li>
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
        </li> -->
         <!-- Chi tiết chương trình -->
         <!-- <li>
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
        </li> -->
        <!-- Bộ đề trắc nghiệm -->
    <!-- <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='bode_tracnghiem_list'|| $active_menu=='bode_tracnghiem_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
            <div class="side-menu__title">
                Bộ đề trắc nghiệm
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu=='teacher_list'|| $active_menu=='bode_tracnghiem_add')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.bode_tracnghiem.index')}}" class="side-menu {{$active_menu=='bode_tracnghiem_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                    <div class="side-menu__title">Danh sách bộ đề trắc nghiệm</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.bode_tracnghiem.create')}}" class="side-menu {{$active_menu=='bode_tracnghiem_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title">Thêm bộ đề trắc nghiệm</div>
                </a>
            </li>
        </ul>
    </li> -->
     <!-- Bộ đề tự luận -->
     <!-- <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='bode_tuluans_list'|| $active_menu=='bode_tuluans_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
            <div class="side-menu__title">
                Bộ đề tự luận
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu=='teacher_list'|| $active_menu=='bode_tuluans_add')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.bode_tuluans.index')}}" class="side-menu {{$active_menu=='bode_tuluans_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                    <div class="side-menu__title">Danh sách bộ đề tự luận</div>
                </a>
            </li>
            <li>
                <a href="{{route('admin.bode_tuluans.create')}}" class="side-menu {{$active_menu=='bode_tuluans_add'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                    <div class="side-menu__title">Thêm bộ đề tự luận</div>
                </a>
            </li>
        </ul>
    </li> -->
         <!-- phân công -->
<!-- <li>
    <a href="javascript:;" class="side-menu {{($active_menu=='phancong_list'|| $active_menu=='phancong_add')?'side-menu--active':''}}">
        <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
        <div class="side-menu__title">
            Phân công
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{($active_menu=='phancong_list'|| $active_menu=='phancong_add')?'side-menu__sub-open':''}}">
        <li>
            <a href="{{ route('phancong.index') }}" class="side-menu {{ $active_menu == 'phancong_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách phân công</div>
            </a>
        </li>
        <li>
            <a href="{{ route('phancong.create') }}" class="side-menu {{ $active_menu == 'phancong_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm phân công</div>
            </a>
        </li>
    </ul>
</li> -->
<!-- Nhóm phân công -->
<!-- <li>
    <a href="javascript:;" class="side-menu {{ ($active_menu == 'phanconggroup_list' || $active_menu == 'phanconggroup_add') ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="file-text"></i> </div>
        <div class="side-menu__title">
            Nhóm phân công
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{ ($active_menu == 'phanconggroup_list' || $active_menu == 'phanconggroup_add') ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('phanconggroup.index') }}" class="side-menu {{ $active_menu == 'phanconggroup_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách Nhóm phân công</div>
            </a>
        </li>
        <li>
            <a href="{{ route('phanconggroup.create') }}" class="side-menu {{ $active_menu == 'phanconggroup_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm nhóm phân công</div>
            </a>
        </li>
    </ul>
</li> -->
<!-- Loại chứng chỉ -->
<!-- <li>
    <a href="javascript:;" class="side-menu {{ ($active_menu == 'loai_chungchi_list' || $active_menu == 'loai_chungchi_add') ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="award"></i> </div>
        <div class="side-menu__title">
            Loại chứng chỉ
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{ ($active_menu == 'loai_chungchi_list' || $active_menu == 'loai_chungchi_add') ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('loai_chungchi.index') }}" class="side-menu {{ $active_menu == 'loai_chungchi_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách Loại chứng chỉ</div>
            </a>
        </li>
        <li>
            <a href="{{ route('loai_chungchi.create') }}" class="side-menu {{ $active_menu == 'loai_chungchi_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm loại chứng chỉ</div>
            </a>
        </li>
    </ul>
</li> -->

        <!-- Điểm danh -->
        <!-- <li>
    <a href="javascript:;" class="side-menu {{ ($active_menu == 'diemdanh_list' || $active_menu == 'diemdanh_add') ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="check-circle"></i> </div>
        <div class="side-menu__title">
            Điểm danh
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{ ($active_menu == 'diemdanh_list' || $active_menu == 'diemdanh_add') ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('diemdanh.index') }}" class="side-menu {{ $active_menu == 'diemdanh_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách điểm danh</div>
            </a>
        </li>
        <li>
            <a href="{{ route('diemdanh.create') }}" class="side-menu {{ $active_menu == 'diemdanh_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm điểm danh</div>
            </a>
        </li>
    </ul>
</li> -->
<!-- Enrollment -->
<!-- <li>
    <a href="javascript:;" class="side-menu {{ ($active_menu == 'enrollment_list' || $active_menu == 'enrollment_add') ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="book-open"></i> </div>
        <div class="side-menu__title">
            Enrollment
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{ ($active_menu == 'enrollment_list' || $active_menu == 'enrollment_add') ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('enrollment.index') }}" class="side-menu {{ $active_menu == 'enrollment_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách Enrollment</div>
            </a>
        </li>
        <li>
            <a href="{{ route('enrollment.create') }}" class="side-menu {{ $active_menu == 'enrollment_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm Enrollment</div>
            </a>
        </li>
    </ul>
</li> -->
<!-- Enrollresult -->
<!-- <li>
    <a href="javascript:;" class="side-menu {{ ($active_menu == 'enroll_results_list' || $active_menu == 'enroll_results_add') ? 'side-menu--active' : '' }}">
        <div class="side-menu__icon"> <i data-lucide="book-open"></i> </div>
        <div class="side-menu__title">
            EnrollResult
            <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
        </div>
    </a>
    <ul class="{{ ($active_menu == 'enroll_results_list' || $active_menu == 'enroll_results_add') ? 'side-menu__sub-open' : '' }}">
        <li>
            <a href="{{ route('admin.enroll_results.index') }}" class="side-menu {{ $active_menu == 'enroll_results_list' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                <div class="side-menu__title">Danh sách kết quả khoá học</div>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.enroll_results.create') }}" class="side-menu {{ $active_menu == 'enroll_results_add' ? 'side-menu--active' : '' }}">
                <div class="side-menu__icon"> <i data-lucide="plus"></i> </div>
                <div class="side-menu__title">Thêm danh sách kết quả khoá học</div>
            </a>
        </li>
    </ul>
</li> -->
    <!-- Thời khóa biểu -->
    <!-- <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='thoikhoabieu_list'|| $active_menu=='bode_tracnghiem_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"><i data-lucide="calendar"></i>
            </div>
            <div class="side-menu__title">
                Thời khóa biểu
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu==''|| $active_menu=='')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.thoikhoabieu.index')}}" class="side-menu {{$active_menu=='bode_tracnghiem_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                    <div class="side-menu__title">Danh sách thời khóa biểu</div>
                </a>
            </li>
        </ul>
    </li> -->

    <!-- Lịch thi -->

    <!-- <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='lichthi_list'|| $active_menu=='lichthi_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"><i data-lucide="calendar"></i>
            </div>
            <div class="side-menu__title">
                Lịch thi
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu==''|| $active_menu=='')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.lichthi.index')}}" class="side-menu {{$active_menu=='lichthi_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                    <div class="side-menu__title">Danh sách lịch thi</div>
                </a>
            </li>
        </ul>
    </li> -->

    <!-- Attendance: Sự tham dự -->
     
    <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='attendance_list'|| $active_menu=='bode_tracnghiem_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"> <i data-lucide="check-square"></i> <!-- Biểu tượng phù hợp cho điểm danh -->
            </div>
            <div class="side-menu__title">
                Điểm danh theo thời khóa biểu
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu==''|| $active_menu=='')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.diemdanh.index')}}" class="side-menu {{$active_menu=='attendance_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                    <div class="side-menu__title">Danh sách điểm danh</div>
                </a>
            </li>
        </ul>
    </li>

    <!-- enrollcertificates: Chứng nhận -->
    <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='enrollcertificates_list'|| $active_menu=='bode_tracnghiem_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"><i data-lucide="award"></i>                <!-- Biểu tượng phù hợp cho điểm danh -->
            </div>
            <div class="side-menu__title">
                Chứng nhận
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu==''|| $active_menu=='')?'side-menu__sub-open':''}}">
            <li>
                <a href="{{route('admin.enrollcertificates.index')}}" class="side-menu {{$active_menu=='enrollcertificates_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                    <div class="side-menu__title">Danh sách chứng nhận</div>
                </a>
            </li>
        </ul>
    </li>
    <!-- Learning: Đang học -->
    <li>
        <a href="javascript:;" class="side-menu {{($active_menu=='learning_list'|| $active_menu=='bode_tracnghiem_add')?'side-menu--active':''}}">
            <div class="side-menu__icon"><i data-lucide="clock"></i>
                <!-- Biểu tượng phù hợp cho điểm danh -->
            </div>
            <div class="side-menu__title">
                Trạng thái học tập
                <div class="side-menu__sub-icon transform"> <i data-lucide="chevron-down"></i> </div>
            </div>
        </a>
        <ul class="{{($active_menu==''|| $active_menu=='')?'side-menu__sub-open':''}}">
            <li> 
                <a href="{{route('admin.learning.index')}}" class="side-menu {{$active_menu=='learning_list'?'side-menu--active':''}}">
                    <div class="side-menu__icon"> <i data-lucide="list"></i> </div>
                    <div class="side-menu__title">Danh sách đang học</div>
                </a>
            </li>
        </ul>
    </li>
    </ul>
</nav>