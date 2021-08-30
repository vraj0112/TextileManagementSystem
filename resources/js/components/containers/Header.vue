<template>
    <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      
      <li class="nav-item">
        <input type="checkbox" id="changeTheme" onchange="changeTheme" data-size="sm" data-onstyle="light" data-offstyle="secondary" data-on="<i class='bi bi-sun'></i>" data-off="<i class='bi bi-moon'></i>" checked data-toggle="toggle">
      </li>
      <li class="nav-item">
        <button style="margin-left: 20px; height: 31px;" id="logOut" class="btn btn-sm btn-danger"><i class="bi bi-box-arrow-left"></i>  Logout</button>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
</template>

<script>
export default {
    name: "Header",
}

$(function(){ 
  $('#changeTheme').bootstrapToggle();
  var theme = "dark";
  $('#changeTheme').change(function(){
    if(theme=="light"){
      $("body").addClass('dark-mode');
      theme = "dark";
    }else{
      $("body").removeClass('dark-mode');
      theme = "light";
    }
  })
});

</script>