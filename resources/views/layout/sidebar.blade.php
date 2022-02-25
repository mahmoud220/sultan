<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{asset('Admin/dist/img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>Alexander Pierce</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>
      <!-- search form -->

      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">

        <li>
          <a href="{{route('dashboard')}}">
            <i class="fa fa-dashboard"></i> <span>Dashboard</span>
          </a>
        </li>
        <li class="header">Master</li>
        <li>
          <a href="{{route('categories.index')}}">
            <i class="fa fa-cube"></i> <span>Categories</span>
          </a>
        </li>
        <li>
          <a href="{{route('products.index')}}">
            <i class="fa fa-cubes"></i> <span>Products</span>
          </a>
        </li>
        <li>
          <a href="{{route('members.index')}}">
            <i class="fa fa-id-card"></i> <span>Members</span>
          </a>
        </li>
        <li>
          <a href="{{route('suppliers.index')}}">
            <i class="fa fa-truck"></i> <span>Suppliers</span>
          </a>
        </li>
        <li class="header">Transactions</li>
        <li>
          <a href="{{route('expenses.index')}}">
            <i class="fa fa-money"></i> <span>Expenses</span>
          </a>
        </li>
        <li>
          <a href="{{route('purchase.index')}}">
            <i class="fa fa-download"></i> <span>Purchases</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-upload"></i> <span>Sales</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-cart-arrow-down"></i> <span>Old Transaction</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-cart-arrow-down"></i> <span>New Transaction</span>
          </a>
        </li>
        <li class="header">Reports</li>
        <li>
          <a href="#">
            <i class="fa fa-file-pdf-o"></i> <span>Transfer</span>
          </a>
        </li>
        <li class="header">System</li>
        <li>
          <a href="#">
            <i class="fa fa-users"></i> <span>User</span>
          </a>
        </li>
        <li>
          <a href="#">
            <i class="fa fa-cogs"></i> <span>Settings</span>
          </a>
        </li>
      </ul>
    </section>
    <!-- /.sidebar -->
  </aside>
