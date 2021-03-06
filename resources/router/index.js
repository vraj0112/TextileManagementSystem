import Router from "vue-router";
import Vue from "vue";

const Container = () => import('../js/components/containers/Container');
const Dashboard = () => import('../js/components/Dashboard');
const InwardQuality = () => import('../js/components/InwardQuality/InwardQualityContainer');
const SellQuality = () => import('../js/components/SellQuality/SellQualityContainer');
const Broker = () => import('../js/components/Broker/BrokerContainer');
const Customer = () => import('../js/components/Customer/CustomerContainer');
const Vendor = () => import('../js/components/Vendor/VendorContainer');
const BankDetails = () => import('../js/components/BankDetails/BankDetailsContainer');
const Credit = () => import('../js/components/Credit/CreditContainer');
const ExpenseCategory = () => import('../js/components/Expense/ExpenseCategory/ExpenseCategoryContainer');
const ExpenseManagement = () => import("../js/components/Expense/ExpenseManagement/ExpenseManagementContainer");
const NewInward = () => import("../js/components/Inward/NewInward");
const NewChallan = () => import("../js/components/Challan/NewChallan");
const SMChallan = () => import("../js/components/Challan/SMChallan");
const ManageInward = () => import('../js/components/Inward/SMInward');
const GenerateDirectInvoice = () => import('../js/components/Invoice/GenerateDirectInvoice');
const ManageDirectInvoices = () => import('../js/components/Invoice/ManageDirectInvoice');
const GenerateFromChallan = () => import('../js/components/Invoice/GenerateFromChallan');
const ManageChallanInvoice = () => import('../js/components/Invoice/ManageChallanInvoice');

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
                },
                {
                    path: "customer",
                    name: "CustomerContainer",
                    component: Customer
                },
                {
                    path: "vendor",
                    name: "VendorContainer",
                    component: Vendor
                },
                {
                    path: "bankdetails",
                    name: "BankDetails",
                    component: BankDetails
                },
                {
                    path: "credit",
                    name: "Credit",
                    component: Credit
                },
                {
                    path: "expensecategory",
                    name: "Expense Category",
                    component: ExpenseCategory
                },
                {
                    path: "expensemanagement",
                    name: "Expense Management",
                    component: ExpenseManagement
                },
                {
                    path: "newinward",
                    name: "New Inward",
                    component: NewInward
                },
                {
                    path: "manageinward",
                    name: "ManageInward",
                    component: ManageInward
                },
                {
                    path: "newchallan",
                    name: "New Challan",
                    component: NewChallan
                },
                {
                    path: "smchallan",
                    name: "S & M Challan",
                    component: SMChallan
                },
                {
                    path: "newdirectinvoice",
                    name: "New Direct Invoice",
                    component: GenerateDirectInvoice
                },
                {
                    path: "managedirectinvoice",
                    name: "Manage Direct Invoices",
                    component: ManageDirectInvoices
                },
                {
                    path: "invoicefromchallan",
                    name: "GenerateFromChallan",
                    component: GenerateFromChallan
                },
                {
                    path: "managechallaninvoice",
                    name: "ManageChallanInvoice",
                    component: ManageChallanInvoice
                }
            ]
        }
    ]
}