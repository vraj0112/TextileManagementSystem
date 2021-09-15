import Router from "vue-router";
import Vue from "vue";

const Container = () => import('../js/components/containers/Container');
const Dashboard = () => import('../js/components/Dashboard');
const Customer = () => import('../js/components/Customer/Customer');

Vue.use(Router);

export default new Router({
    mode: 'hash',
    linkActiveClass: 'active',
    scrollBehavior: () => ({ y: 0 }),
    routes: configRoutes()
})

function configRoutes() {
    return [
        {
            path: '/',
            redirect: "/dashboard",
            name: 'Home',
            component: Container,
            children: [
                {
                    path: "dashboard",
                    name: "Dashboard",
                    component: Dashboard
                },
                {
                    path: "customer",
                    name: "Customer",
                    component: Customer
                }
            ]
        }
    ]
}