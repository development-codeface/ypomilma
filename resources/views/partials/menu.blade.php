<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <!-- @can('access_report')
    <li class="nav-item">
                                <a href="{{ route('admin.report.index') }}"
                                    class="nav-link {{ request()->is('admin/report') || request()->is('admin/report/*') ? 'active' : '' }}">
                                    <i class="fi fi-rr-apps nav-icon"></i>
                                    {{ trans('cruds.report.title') }}
                                </a>
                            </li>
@endcan -->

            <li class="nav-item nav-dropdown">
                <a class="nav-link  nav-dropdown-toggle" href="#">
                    <i class="fi fi-br-user-gear nav-icon">
                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="nav-dropdown-items">
                    <div style="margin-left:28px;">
                        @can('permission_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.permissions.index') }}"
                                    class="nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'active' : '' }}">
                                    {{-- <i class="fa-fw fas fa-unlock-alt nav-icon">

                                    </i> --}}
                                    {{ trans('cruds.permission.title') }}
                                </a>
                            </li>
                        @endcan
                        @can('role_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.roles.index') }}"
                                    class="nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'active' : '' }}">
                                    {{-- <i class="fa-fw fas fa-briefcase nav-icon">

                                    </i> --}}
                                    {{ trans('cruds.role.title') }}
                                </a>
                            </li>
                        @endcan
                    </div>
                </ul>
            </li>

            @can('invoice_access')
                <li class="nav-item">
                    <a href="{{ route('admin.regions.index') }}"
                        class="nav-link {{ request()->is('admin/regions') || request()->is('admin/regions/*') ? 'active' : '' }}">
                        <i class="fi fi-br-home-location-alt nav-icon"></i>
                        {{ trans('cruds.invoice.title') }}
                    </a>
                </li>
            @endcan

            @can('region_access')
                <li class="nav-item">
                    <a href="{{ route('admin.regions.index') }}"
                        class="nav-link {{ request()->is('admin/regions') || request()->is('admin/regions/*') ? 'active' : '' }}">
                        <i class="fi fi-br-home-location-alt nav-icon"></i>
                        {{ trans('cruds.region.title') }}
                    </a>
                </li>
            @endcan
            @can('user_access')
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                        <i class="fi fi-br-users nav-icon"></i>
                        {{ trans('cruds.user.title') }}
                    </a>
                </li>
            @endcan

            @can('dairy_access')
                <li class="nav-item">
                    <a href="{{ route('admin.dairies.index') }}"
                        class="nav-link {{ request()->is('admin/dairies') || request()->is('admin/dairies/*') ? 'active' : '' }}">
                        <i class="fi fi-br-box nav-icon"></i>
                        Dairy
                    </a>
                </li>
            @endcan

            @can('vendor_access')
                <li class="nav-item">
                    <a href="{{ route('admin.vendors.index') }}"
                        class="nav-link {{ request()->is('admin/vendors') || request()->is('admin/vendors/*') ? 'active' : '' }}">
                        <i class="fi fi-br-briefcase nav-icon"></i>
                        Vendors
                    </a>
                </li>
            @endcan

            @can('product_access')
                <li class="nav-item">
                    <a href="{{ route('admin.products.index') }}"
                        class="nav-link {{ request()->is('admin/products') || request()->is('admin/products/*') ? 'active' : '' }}">
                        <i class="fi fi-br-box nav-icon"></i>
                        Products
                    </a>
                </li>
            @endcan

            @can('expensecategory_access')
                <li class="nav-item">
                    <a href="{{ route('admin.expense_categories.index') }}"
                        class="nav-link {{ request()->is('admin/expense_categories') || request()->is('admin/expense_categories/*') ? 'active' : '' }}">
                        <i class="fi fi-br-box nav-icon"></i>
                        Expense Category
                    </a>
                </li>
            @endcan
            @can('fundallocation_access')
                <li class="nav-item">
                    <a href="{{ route('admin.fund_allocations.index') }}"
                        class="nav-link {{ request()->is('admin/fund_allocations') || request()->is('admin/fund_allocations/*') ? 'active' : '' }}">
                        <i class="fi fi-br-box nav-icon"></i>
                        Fund Allocation
                    </a>
                </li>
            @endcan


            @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                @can('profile_password_edit')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'active' : '' }}"
                            href="{{ route('profile.password.edit') }}">
                            <i class="fi fi-br-lock nav-icon"></i>
                            {{ trans('global.change_password') }}
                        </a>
                    </li>
                @endcan
            @endif
            {{-- <li class="nav-item">
                <a href="#" class="nav-link"
                    onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                    <i class="fi fi-br-sign-out-alt nav-icon"></i>
                    {{ trans('global.logout') }}
                </a>
            </li> --}}
            {{--
            <li>
                <div class="crediz">
                    <div class="icoz"><img class="m_9" src="{{ asset('css/img/codeface.png') }}" alt="">
                    </div>
                    <div class="ics">Need help ?</div>
                    <div class="icsx">Our support team is at your disposal</div>
                    <div class="icsxb">
                        <a class="btn btn-success btn-sm" data-toggle="modal" data-target="#exampleModalCenter">

                            Raise a Ticket
                        </a>
                    </div>

                </div>
            </li> --}}
        </ul>

</div>
</nav>
<div class="modal fade" id="exampleModalCenter">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> Raise a Ticket</h5>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12 col-sm-12">
                        <label class="required" for="notempstaff">Title of your query</label>
                        <input class="form-control {{ $errors->has('collectionroute') ? 'is-invalid' : '' }}"
                            type="text" name="collectionroute" id="collectionroute"
                            value="{{ old('collectionroute', '') }}" required>
                        @if ($errors->has('collectionroute'))
                            <div class="invalid-feedback">
                                {{ $errors->first('collectionroute') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.team.fields.collectionroute_helper') }}</span>
                    </div>
                    <div class="form-group col-lg-12 col-sm-12">
                        <label class="required" for="notempstaff">Discription</label>
                        <textarea class="form-control {{ $errors->has('collectionroute') ? 'is-invalid' : '' }}" type="text"
                            name="collectionroute" id="collectionroute" value="{{ old('collectionroute', '') }}" required> </textarea>
                        @if ($errors->has('collectionroute'))
                            <div class="invalid-feedback">
                                {{ $errors->first('collectionroute') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.team.fields.collectionroute_helper') }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">

                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
<!-- <button class="sidebar-minimizer brand-minimizer" type="button"></button> -->
