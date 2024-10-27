<nav class="side-nav">
   
<ul>
        <li>
            <a href="{{route('admin.home')}}" class="side-menu side-menu{{$active_menu=='dashboard'?'--active':''}}">
                <div class="side-menu__icon"> <i data-lucide="home"></i> </div>
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
    
</ul>
</nav>