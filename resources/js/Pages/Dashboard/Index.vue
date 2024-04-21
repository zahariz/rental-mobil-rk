<template>
    <div>
    <Head title="Dashboard" />
    <h1 class="mb-8 text-3xl font-bold">Dashboard</h1>
    <p class="mb-8 leading-normal">Hi! Selamat datang di Rental Mobil RK dimana kami menawarkan rental mobil terbaik dan termurah se Indonesia.</p>
  </div>
</template>

<script>
    import {Head} from '@inertiajs/vue3'
    import Layout from  '@/Shared/Layout.vue'


    export default {
        component : {
            Head,
        },
        layout: Layout

    }

</script>
