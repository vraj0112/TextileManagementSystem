<template>
    <div>
        <aside></aside>
        <div class="content-wrapper">
            <section class="content">
                <div class="container-fluid">
                    <NewExpenseManagement
                        @refreashExpenseTable="refreashExpenseTable"
                    />
                    <SMExpenseManagement ref="form" />
                </div>
            </section>
        </div>
    </div>
</template>

<script>
import NewExpenseManagement from './NewExpenseManagement'
import SMExpenseManagement from './SMExpenseManagement'

export default {
    name: "ExpenseManagementContaiber",
    components: {
        NewExpenseManagement,
        SMExpenseManagement
    },
    methods: {
        refreashExpenseTable: function(){
            this.$refs.form.getAllExpenses();
        }
    }
}
</script>