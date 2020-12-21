<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{route('dashboard')}}" class="brand-link">
        <img src="{{URL::asset('backoffice/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo"
             class="brand-image img-circle elevation-3"
             style="opacity: .8">
        <span class="brand-text font-weight-light">Sneaksdeal</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{!! URL("storage/admin/").'/'.Session::get('file_img_admin') !!}"
                     class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{!! Session::get('name_admin') .' '.Session::get('lastname_admin') !!}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false"
                style="margin-bottom: 100px">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                @if(Session::get('main_id_at') == 0)
                    <li class="nav-header">MENU{{Session::get('id_admin')}}</li>
                    @php
                        $queryMenu = DB::table('tb_menu')->get();
                    @endphp
                    @if(Session::get('id_admin') != 1)
                        @foreach($queryMenu AS $rowMenu)
                            @php
                                $arrayActive = explode('|',$rowMenu->name_active);
                             $tbPer = DB::table('tb_permission')->where('admin_id','=',Session::get('id_admin'))->where('menu_id','=',$rowMenu->id_menu)->first();
                            @endphp
                            @if(@$tbPer->menu_id != '')
                                @if(count($arrayActive) > 1)

                                    <li class="nav-item has-treeview {!!  ( in_array($active,$arrayActive) ? 'menu-open':'') !!}">
                                        <a href="#"
                                           class="nav-link {!! ( in_array($active,$arrayActive)? 'active':'') !!}">
                                            <i class="nav-icon {{$rowMenu->icon_menu}}"> </i>
                                            <p>
                                                {{$rowMenu->name_menu}}
                                                <i class="right fa fa-angle-left"> </i>
                                            </p>
                                        </a>
                                        <ul class="nav nav-treeview">
                                            @php
                                                $querySubMenu = DB::table('sub_menu')->where("relation_menu",$rowMenu->id_menu)->get();
                                            @endphp
                                            @foreach($querySubMenu AS $rowSubMenu)
                                                {!!left_menu($rowSubMenu->name_submenu,'',asset($rowSubMenu->url_submenu),$rowSubMenu->icon_submenu,'success',($active== $rowSubMenu->sub_active ?'active':''))!!}
                                            @endforeach
                                        </ul>
                                    </li>
                                @else
                                    {!!left_menu($rowMenu->name_menu,'',asset($rowMenu->url_menu),$rowMenu->icon_menu,'success',($active == $rowMenu->name_active?'active':''))!!}

                                @endif
                            @endif
                        @endforeach
                    @else
                        @foreach($queryMenu AS $rowMenu)
                            @php
                                $arrayActive = explode('|',$rowMenu->name_active);
                            @endphp
                            @if(count($arrayActive) > 1)

                                <li class="nav-item has-treeview {!!  ( in_array($active,$arrayActive) ? 'menu-open':'') !!}">
                                    <a href="#" class="nav-link {!! ( in_array($active,$arrayActive)? 'active':'') !!}">
                                        <i class="nav-icon {{$rowMenu->icon_menu}}"> </i>
                                        <p>
                                            {{$rowMenu->name_menu}}
                                            <i class="right fa fa-angle-left"> </i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @php
                                            $querySubMenu = DB::table('sub_menu')->where("relation_menu",$rowMenu->id_menu)->get();
                                        @endphp
                                        @foreach($querySubMenu AS $rowSubMenu)
                                            {!!left_menu($rowSubMenu->name_submenu,'',asset($rowSubMenu->url_submenu),$rowSubMenu->icon_submenu,'success',($active== $rowSubMenu->sub_active ?'active':''))!!}
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                {!!left_menu($rowMenu->name_menu,'',asset($rowMenu->url_menu),$rowMenu->icon_menu,'success',($active == $rowMenu->name_active?'active':''))!!}

                            @endif
                        @endforeach
                    @endif
                @else
                    <li class="nav-header">ร้านค้า</li>
                    {!!left_menu('My-name-blog','',asset('/backoffice/name-blog'),'fas fa-home','success',($active=='blog_detail'?'active':''))!!}
                    {!!left_menu('ยอดขาย','',asset('backoffice/sale/'),'fas fa-money-check-alt','success',($active=='select_sale'?'active':''))!!}
                    <li class="nav-item has-treeview {{($active=='orders_blog' || $active == 'select_order' || $active == 'select_order2' ?'menu-open':'')}}">
                        <a href="#"
                           class="nav-link {{($active=='orders_blog' || $active == 'select_order' || $active == 'select_order2'  ?'active':'')}}">
                            <i class="nav-icon fas fa-list-ol"> </i>
                            <p>
                                จัดการ Order
                                <i class="right fa fa-angle-left"> </i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {!! left_menu('Order ชำระเงินแล้ว','',asset('backoffice/ordersuccess'),'far fa-clone','success',($active=='select_order'?'active':''))!!}
                            {{-- {!! left_menu('Order ยังไม่ได้ชำระเงิน','',asset('/backoffice/orderunsuccess'),'far fa-clone','success',($active=='select_order2'?'active':'')) !!} --}}
                        </ul>
                    </li>
                    <li class="nav-item has-treeview {{($active=='report' || $active == 'report_blog' || $active == 'report_product'  ?'menu-open':'')}}">
                        <a href="#"
                           class="nav-link {{($active=='report' || $active == 'report_blog' || $active == 'report_product' ?'active':'')}}">
                            <i class="nav-icon fas fa-file-export"> </i>
                            <p>
                                รายงาน
                                <i class="right fa fa-angle-left"> </i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            {!!left_menu('รายงานยอดขาย','',asset('backoffice/report'),'fas fa-file-excel','success',($active=='report_blog'?'active':''))!!}
                            {!!left_menu('รายงานสินค้า','',asset('/backoffice/reportproduct'),'fas fa-file-excel','success',($active=='report_product'?'active':''))!!}
                        </ul>
                    </li>
                @endif
                <li class="nav-item">
                    <a href="{{url('backoffice/location')}}" class="nav-link {{($active=='location'?'active':'')}}">
                        <i class="far fa-clone"></i>
                        <p>
                            Location
                            <span class="right badge badge-success"></span>
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>