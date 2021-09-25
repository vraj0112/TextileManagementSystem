<template>
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img
        src="dist/img/SahajanandLogo.png"
        alt="Sahajanand Textiles Logo"
        class="brand-image img-circle elevation-3"
        style="opacity: 0.8"
      />
      <span class="brand-text font-weight-light">Sahajanand Textile</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul
          class="nav nav-pills nav-sidebar flex-column"
          data-widget="treeview"
          role="menu"
          data-accordion="false"
        >
          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p class="text-md">Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <router-link to="/inwardquality" class="nav-link">
              <i class="nav-icon far bi bi-pip"></i>
              <p class="text-md">Inward Quality</p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/sellquality" class="nav-link">
              <i class="nav-icon far bi bi-pip"></i>
              <p class="text-md">Sell Quality</p>
            </router-link>
          </li>

          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon far bi bi-person-circle"></i>
              <p class="text-md">Vendor</p>
            </a>
          </li>

          <li class="nav-item">
            <router-link to="/customer" class="nav-link">
              <i class="nav-icon far bi bi-person-circle"></i>
              <p class="text-md">Customer</p>
            </router-link>
          </li>

          <li class="nav-item">
            <router-link to="/broker" class="nav-link">
              <i class="nav-icon far bi bi-person-circle"></i>
              <p class="text-md">Broker</p>
            </router-link>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon nav-icon far bi bi-box-arrow-in-right"></i>
              <p class="text-md">
                Inward
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">New Inward</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">Manage Inward</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon nav-icon far bi bi-receipt"></i>
              <p class="text-md">
                Challan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">New Challan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">Manage Challan</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon nav-icon far bi bi-receipt-cutoff"></i>
              <p class="text-md">
                Invoice
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">Generate Direct Invoice</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">Manage Direct Invoice</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">Generate from Challan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">Manage Challan Invoice</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon far bi bi-bank"></i>
              <p class="text-md">Bank Details</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far bi bi-dash-circle"></i>
              <p class="text-md">
                Expense
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">Expense Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p class="text-md">Expense Management</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon far bi bi-currency-dollar"></i>
              <p class="text-md">Credit</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>
</template>

<script>
export default {
  name: "Sidebar",
};
</script>