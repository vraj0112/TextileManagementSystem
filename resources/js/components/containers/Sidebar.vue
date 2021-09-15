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
              <p>Dashboard</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon far bi bi-pip"></i>
              <p>Yarn Qualtiy</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon far bi bi-pip"></i>
              <p>Grey Qualtiy</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon far bi bi-person-circle"></i>
              <p>Vendor</p>
            </a>
          </li>

          <li class="nav-item">
            <router-link to="/customer" class="nav-link">
              <i class="nav-icon far bi bi-person-circle"></i>
              <p>Customer</p>
            </router-link>
          </li>

          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon far bi bi-person-circle"></i>
              <p>Broker</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon nav-icon far bi bi-box-arrow-in-right"></i>
              <p>
                Inward
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Inward</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Inward</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon nav-icon far bi bi-receipt"></i>
              <p>
                Challan
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>New Challan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Challan</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon nav-icon far bi bi-receipt-cutoff"></i>
              <p>
                Invoice
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Generate Direct Invoice</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Direct Invoice</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Generate from Challan</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Manage Challan Invoice</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon far bi bi-bank"></i>
              <p>Bank Details</p>
            </a>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon far bi bi-dash-circle"></i>
              <p>
                Expense
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Expense Category</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Expense Management</p>
                </a>
              </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="/" class="nav-link">
              <i class="nav-icon far bi bi-currency-dollar"></i>
              <p>Credit</p>
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