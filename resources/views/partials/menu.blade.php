<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            
            @can('user_access')
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
            @can('user_access')
                <li class="nav-item">
                    <a href="{{ route('admin.users.index') }}"
                        class="nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'active' : '' }}">
                        <i class="fi fi-br-users nav-icon"></i>
                        {{ trans('cruds.user.title') }}
                    </a>
                </li>
            @endcan
              @if(Gate::check('dairy_access') || Gate::check('region_access'))
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

            @if(Gate::check('product_access') || Gate::check('vendor_access'))
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
                                        class="nav-link {{ request()->is('admin/vendors*') ? 'active' : '' }}">
                                        Vendors
                                    </a>
                                </li>
                            @endcan
                        </div>
                    </ul>
                </li>
            @endif


          
            @if(Gate::check('expensecategory_access') || Gate::check('expense_access') || Gate::check('expenseitem_access'))
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fi fi-br-wallet nav-icon"></i>
                        Expense Management
                    </a>
                    <ul class="nav-dropdown-items">
                        <div style="margin-left:28px;">
                            @can('expensecategory_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.expense_categories.index') }}"
                                        class="nav-link {{ request()->is('admin/expense_categories*') ? 'active' : '' }}">
                                        Expense Category
                                    </a>
                                </li>
                            @endcan
                             @can('expenseitem_access')
                                <li class="nav-item">
                                    <a href="{{ route('admin.expenseitems.index') }}"
                                        class="nav-link {{ request()->is('admin/expense_items*') ? 'active' : '' }}">
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

                           
                        </div>
                    </ul>
                </li>
            @endif


            @can('invoice_access')
                <li class="nav-item nav-dropdown">
                    <a class="nav-link nav-dropdown-toggle" href="#">
                        <i class="fi fi-br-file-invoice nav-icon"></i>
                        Invoice Management
                    </a>
                    <ul class="nav-dropdown-items">
                        <div style="margin-left:28px;">
                            <li class="nav-item">
                                <a href="{{ route('admin.invoices.index') }}"
                                    class="nav-link {{ request()->is('admin/invoices/index') ? 'active' : '' }}">
                                   Invoices
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('admin.invoice-list.index') }}"
                                    class="nav-link {{ request()->is('admin/invoice') || request()->is('admin/invoice/*') || request()->is('admin/invoice-list*') ? 'active' : '' }}">
                                    Invoices List
                                </a>
                            </li>
                        </div>
                    </ul>
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
    </nav>
</div>
