<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('back/dashboard/') }}/{{ $organization->id }}" class="brand-link">
        <img src="{{ asset('storage/organizations') }}/{{ $organization->logo }}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">
            {{ $organization->organization }}
        </span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ asset('storage/users') }}/{{ Auth::user()->profile }}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">
                    {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item has-treeview menu-open">
                    <a href="#" class="nav-link active">
                        <i class="nav-icon fa fa-dashboard"></i>
                        <p>
                            Dashboard
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('back/dashboard/') }}/{{ $organization->id }}" class="nav-link active">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-th"></i>
                        <p>
                            Customers
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('back.customers.create', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>New Customer</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('back.customers.index', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Customers List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-list-alt"></i>
                        <p>
                            Products
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('back.products.create', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Create Product</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('back/products') }}/{{ $organization->id }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Products List</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('back.brands.index', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Brands</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ url('back/categories') }}/{{ $organization->id }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Categories</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{ route('back.units.index', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Unit</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-file-text-o"></i>
                        <p>
                            Sales
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('back.sales.create', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>New Sale</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('back.sales.index', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Sales List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-file-text-o"></i>
                        <p>
                            Purchases
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('back/purchases') }}/{{ $organization->id }}/create" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>New Purchases</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('back/purchases') }}/{{ $organization->id }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Purchases List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-list"></i>
                        <p>
                            Receipts
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('back.receipts.index', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Receipts List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-th-list"></i>
                        <p>
                            Invoices
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('back.invoices.create', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>New Invoice</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('back.invoices.index', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Invoices List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            Suppliers
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('back/suppliers') }}/{{ $organization->id }}/create" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>New Supplier</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('back/suppliers') }}/{{ $organization->id }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Suppliers List</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-bar-chart"></i>
                        <p>
                            Stocks
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('back.stocks.index', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Stocks Summary</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">SETTINGS</li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-cogs"></i>
                        <p>
                            Settings
                            <i class="fa fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('back.settings.index', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Settings</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('back.payments.methods.index', $organization->id) }}" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Payment Methods</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-header">ACCOUNTS</li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-user-plus"></i>
                        <p>
                            New Account
                        </p>
                    </a>
                </li>

                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fa fa-users"></i>
                        <p>
                            User Accounts
                            <i class="right fa fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="#" class="nav-link">
                                <i class="fa fa-circle nav-icon"></i>
                                <p>Accounts</p>
                            </a>
                        </li>
                    </ul>
                </li>

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
