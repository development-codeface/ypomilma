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
            @can('user_manage_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fi fi-br-user-gear nav-icon"></i>
                        {{ trans('cruds.userManagement.title') }}
                    </a>
                    <ul class="nav-dropdown-items">
                        <div style="margin-left:28px;">
                            @can('permission_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.permissions.index') }}"
                                        class="nav-link {{ request()->is('admin/permissions*') ? 'active' : '' }}">
                                        {{ trans('cruds.permission.title') }}
                                    </a>
                                </li>
                            @endcan


                            @can('role_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.roles.index') }}"
                                        class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}">
                                        {{ trans('cruds.role.title') }}
                                    </a>
                                </li>
                            @endcan
                        </div>
                    </ul>
                </li>
            @endcan
            @can('invoice_access')
                <li class="nav-item">
                    <a href="{{ route('admin.invoice-list.index') }}"
                        class="nav-link {{ request()->is('admin/invoice') || request()->is('admin/invoice/*') ? 'active' : '' }}">
                        <i class="fi fi-br-home-location-alt nav-icon"></i>
                        {{ trans('cruds.invoice.title') }}
                    </a>
                </li>
            @endcan
            @can('asset_access')
                <li class="nav-item">
                    <a href="{{ route('admin.asset-management.index') }}"
                        class="nav-link {{ request()->is('admin/asset') || request()->is('admin/asset/*') ? 'active' : '' }}">
                        <i class="fi fi-br-home-location-alt nav-icon"></i>
                        {{ trans('cruds.asset_management.title') }}
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
            @if (Gate::check('dairy_access') || Gate::check('region_access'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fi fi-br-home-location-alt nav-icon"></i>
                        Dairy Management
                    </a>
                    <ul class="nav-dropdown-items">
                        <div style="margin-left:28px;">
                            @can('dairy_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.dairies.index') }}"
                                        class="nav-link {{ request()->is('admin/dairies*') ? 'active' : '' }}">
                                        Dairy
                                    </a>
                                </li>
                            @endcan

                            @can('region_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.regions.index') }}"
                                        class="nav-link {{ request()->is('admin/regions*') ? 'active' : '' }}">
                                        Regions
                                    </a>
                                </li>
                            @endcan
                        </div>
                    </ul>
                </li>
            @endif

            @if (Gate::check('product_access') || Gate::check('vendor_access'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fi fi-br-box nav-icon"></i>
                        Product Management
                    </a>
                    <ul class="nav-dropdown-items">
                        <div style="margin-left:28px;">
                            @can('product_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.products.index') }}"
                                        class="nav-link {{ request()->is('admin/products*') ? 'active' : '' }}">
                                        Products
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
                                        class="nav-link {{ request()->is('admin/fund_allocations*') ? 'active' : '' }}">
                                        <i class="fi fi-br-piggy-bank nav-icon"></i>
                                        Fund Allocation
                                    </a>
                                </li>
                            @endcan


                            @if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
                                @can('profile_password_edit')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('profile/password*') ? 'active' : '' }}"
                                            href="{{ route('profile.password.edit') }}">
                                            <i class="fi fi-br-lock nav-icon"></i>
                                            {{ trans('global.change_password') }}
                                        </a>
                                    </li>
                                @endcan
                            @endif

                    </ul>
            @endif
    </nav>
</div>
