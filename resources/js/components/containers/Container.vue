<template>
  <div class="c-app">
    <Sidebar/>
    <div class="wrapper">
      <Header/>
      <div class="c-body">
        <main class="c-main">
          <transition name="fade" mode="out-in">
            <router-view :key="$route.path"></router-view>
          </transition>
        </main>
      </div>
      <Footer/>
    </div>
  </div>
</template>

<script>

import Header from "./Header";
import Sidebar from "./Sidebar";
import Footer from "./Footer";
export default {
  name: "Container",
  components: {
    Header,
    Sidebar,
    Footer,
  },
};

</script>