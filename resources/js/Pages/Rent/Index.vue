<template>
    <div>
    <Head title="History Transaksi"/>
    <h1 class="mb-8 text-3xl font-bold">History Peminjaman</h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
        
      </search-filter>
      <Link class="btn-indigo" href="/list-cars">
        <span>Sewa</span>
        <span class="hidden md:inline">&nbsp;Mobil</span>
      </Link>
    </div>
    <div class="bg-white rounded-md shadow overflow-x-auto">
      <table class="w-full whitespace-nowrap">
        <thead>
          <tr class="text-left font-bold">
            <th class="pb-4 pt-6 px-6">Mobil</th>
            <th class="pb-4 pt-6 px-6">No Plat</th>
            <th class="pb-4 pt-6 px-6">Tanggal Rental</th>
            <th class="pb-4 pt-6 px-6">Tanggal Selesai</th>
            <th class="pb-4 pt-6 px-6">Harga</th>
            <th class="pb-4 pt-6 px-6">Status</th>
            <th class="pb-4 pt-6 px-6" colspan="2">Action</th>
          </tr>
        </thead>
        <tbody>
            <tr class="hover:bg-gray-100 focus-within:bg-gray-100" v-for="rent in rents.data" :key="rent.id">
                <td class="border-t">
                    <div class="flex items-center px-6 py-4">
                        {{ rent.cars.merek }}
                    </div>
                </td>
                <td class="border-t">
                    <div class="flex items-center px-6 py-4">
                        {{ rent.cars.no_plat }}
                    </div>
                </td>
                <td class="border-t">
                    <div class="flex items-center px-6 py-4">
                        {{ rent.tanggal_mulai }}
                    </div>
                </td>
                <td class="border-t">
                    <div class="flex items-center px-6 py-4">
                        {{ rent.tanggal_selesai }}
                    </div>
                </td>
                <td class="border-t">
                    <div class="flex items-center px-6 py-4">
                        Rp. {{ rent.harga }}
                    </div>
                </td>
                <td class="border-t">
                    <div class="flex items-center px-6 py-4">
                        {{ rent.status }}
                    </div>
                </td>
                <td class="border-t">
                    <div class="flex items-center px-6 py-4" v-if="rent.status == 'Rented'">
                        <Link class="btn-indigo" :href="`/return/${rent.id}/cars`">
                            <span>Return</span>
                        </Link>
                    </div>
                    
                </td>
            </tr>
            <tr v-if="rents.data.length === 0">
                <td class="border-t">
                    <div class="flex items-center px-6 py-4">
                        
                        No rent found
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
    
    </div>
    </div>

</template>
<script>

    import { Head, Link } from '@inertiajs/vue3'
    import Icon from '@/Shared/Icon.vue'
    import Layout from '@/Shared/Layout.vue'
    import Pagination from '@/Shared/Pagination.vue'
    import SearchFilter from '@/Shared/SearchFilter.vue'
    import throttle from 'lodash/throttle'
    import pickBy from 'lodash/pickBy'
    import mapValues from 'lodash/mapValues'

    export default {

        components: {
            Head,
            Icon,
            Link,
            Pagination,
            SearchFilter,
        },
        layout: Layout,
        props: {
            rents: Object,
            filters: Object,
        },
        data() {
            return {
            form: {
                search: this.filters.search
            },
            }
        },
        watch: {
            form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get(`/transaction`, pickBy(this.form), { preserveState: true })
            }, 150),
            },
        },
        methods: {
            reset() {
            this.form = mapValues(this.form, () => null)
            },
        },

    }

</script>