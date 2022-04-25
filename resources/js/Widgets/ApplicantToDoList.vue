<template>
    <div class="col-12">
        <table class="table table-responsive-sm table-striped" v-if="list.length > 0">
            <thead>
            <tr>
                <th>To Do Item</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(item, idx) in list">
                <td v-html="item"></td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "ApplicantToDoList",
    props: ['list'],
}
</script>

<style scoped>

</style>
