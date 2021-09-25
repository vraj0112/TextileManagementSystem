import Router from "vue-router";
import Vue from "vue";

const Container = () => import('../js/components/containers/Container');
const Dashboard = () => import('../js/components/Dashboard');
const InwardQuality = () => import('../js/components/InwardQuality/InwardQualityContainer');
const SellQuality = () => import('../js/components/SellQuality/SellQualityContainer');
const Broker = () => import('../js/components/Broker/BrokerContainer');

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
                    path: "inwardquality",
                    name: "InwardQuality",
                    component: InwardQuality
                },
                {
                    path: "sellquality",
                    name: "SellQuality",
                    component: SellQuality
                },
                {
                    path: "broker",
                    name: "Broker",
                    component: Broker
                }
            ]
        }
    ]
}