<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">

            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}"
                    class="nav-link {{ request()->is('admin/dashboard*') ? 'active' : '' }}">
                    <i class="fi fi-br-dashboard nav-icon"></i>
                    Dashboard
                </a>
            </li>

            @can('user_manage_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fi fi-br-user-gear nav-icon"></i>
                        {{ trans('cruds.userManagement.title') }}
                    </a>
                    <ul class="nav-dropdown-items" style="margin-left:28px;">
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
                    </ul>
                </li>
            @endcan

            @can('user_access')
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}">
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
                    <ul class="nav-dropdown-items" style="margin-left:28px;">
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
                    </ul>
                </li>
            @endif


            @can('fundallocation_access')
                <li class="nav-item">
                    <a href="{{ route('admin.fund_allocations.index') }}"
                        class="nav-link {{ request()->is('admin/fund_allocations*') ? 'active' : '' }}">
                        <i class="fi fi-br-sack-dollar nav-icon"></i>
                        Fund Allocations
                    </a>
                </li>
            @endcan


            @can('invoice_access')
                <li class="nav-item">
                    <a href="{{ route('admin.invoices.index') }}"
                        class="nav-link {{ request()->is('admin/invoices*') ? 'active' : '' }}">
                        <i class="fi fi-br-file-invoice nav-icon"></i>
                        {{ trans('cruds.invoice.title') }}
                    </a>
                </li>
            @endcan


            @if (Gate::check('product_access') || Gate::check('vendor_access'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fi fi-br-box nav-icon"></i>
                        Product Management
                    </a>
                    <ul class="nav-dropdown-items" style="margin-left:28px;">
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
                                    class="nav-link {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                                    Vendors
                                </a>
                            </li>
                        @endcan
                    </ul>
                </li>
            @endif


            @can('asset_access')
                <li class="nav-item">
                    <a href="{{ route('admin.asset-management.index') }}"
                        class="nav-link {{ request()->is('admin/asset-management*') ? 'active' : '' }}">
                        <i class="fi fi-br-building nav-icon"></i>
                        {{ trans('cruds.asset_management.title') }}
                    </a>
                </li>
            @endcan

            @can('agency_sale_access')
                <li class="nav-item">
                    <a href="{{ route('admin.aggency-sale.index') }}"
                        class="nav-link {{ request()->is('admin/aggency-sale*') ? 'active' : '' }}">
                        <i class="fi fi-br-building nav-icon"></i>
                        {{ trans('cruds.aggency_sale.title') }}
                    </a>
                </li>
            @endcan

              @can('agency_access')
                <li class="nav-item">
                    <a href="{{ route('admin.aggency.index') }}"
                        class="nav-link {{ request()->is('admin/aggency*') ? 'active' : '' }}">
                        <i class="fi fi-br-building nav-icon"></i>
                        {{ trans('cruds.agency.title') }}
                    </a>
                </li>
            @endcan


            @if (Gate::check('expensecategory_access') ||
                    Gate::check('expenseitem_access') ||
                    Gate::check('expense_access') ||
                    Gate::check('fundallocation_access'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fi fi-br-money-check-edit nav-icon"></i>
                        Expense Management
                    </a>
                    <ul class="nav-dropdown-items" style="margin-left:28px;">
                        @can('expensecategory_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.expense_categories.index') }}"
                                    class="nav-link {{ request()->is('admin/expense_categories*') ? 'active' : '' }}">
                                    Expense Categories
                                </a>
                            </li>
                        @endcan

                        @can('expenseitem_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.expenseitems.index') }}"
                                    class="nav-link {{ request()->is('admin/expenseitems*') ? 'active' : '' }}">
                                    Expense Items
                                </a>
                            </li>
                        @endcan

                        @can('expense_access')
                            <li class="nav-item">
                                <a href="{{ route('admin.expenses.index') }}"
                                    class="nav-link {{ request()->is('admin/expenses*') ? 'active' : '' }}">
                                    Expenses
                                </a>
                            </li>
                        @endcan

                    </ul>
                </li>
            @endif

            @php
                $user = auth()->user();
            @endphp

            @can('dairy_invoice_access')
                @if (strtolower($user->role_name) != 'superadmin')
                    <li class="nav-item">
                        <a href="{{ route('admin.invoice-list.index') }}"
                            class="nav-link {{ request()->is('admin/invoice-list*') ? 'active' : '' }}">
                            <i class="fi fi-br-file-invoice nav-icon"></i>
                            {{ trans('cruds.invoice.title') }}
                        </a>
                    </li>
                @endif
            @endcan


            @can('transaction_access')
                <li class="nav-item">
                    <a href="{{ route('admin.transactions.index') }}"
                        class="nav-link {{ request()->is('admin/transactions*') ? 'active' : '' }}">
                        <i class="fi fi-br-chart-histogram nav-icon"></i>
                        Transaction Report
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
    </nav>
</div>
