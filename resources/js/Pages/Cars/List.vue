<template>
    <div>
      <Head title="List Mobil" />
      <h1 class="mb-8 text-3xl font-bold">List Mobil</h1>
      <div class="flex items-center justify-between mb-6">
        <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
      
        </search-filter>
        
      </div>
      <div class="bg-white rounded-md shadow overflow-x-auto">
        <div class="container my-12 mx-auto px-4 md:px-12">
            <div v-if="cars.data.length > 0">
                <div class="flex flex-wrap -mx-1 lg:-mx-4">
          <!-- Column -->
         <div class="my-1 px-1 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/3" v-for="car in cars.data" :key="car.id">
              <!-- Article -->
              <article class="overflow-hidden rounded-lg shadow-lg">
                <a href="#">
                  <img
                    alt="Placeholder"
                    class="block h-auto w-full"
                    :src="car.gambar ? car.gambar : 'https://picsum.photos/600/400/?random'"
                  />
                </a>
  
                <header
                  class="flex items-center justify-between leading-tight p-2 md:p-4"
                >
                  <h1 class="text-lg">
                    <a class="no-underline hover:underline text-black" href="#">
                      {{ car.merek }}
                    </a>
                  </h1>
                  <p class="text-grey-darker text-sm" v-if="car.qty > 0">Ready</p>
                  <p class="text-grey-darker text-sm" v-if="car.qty < 0">Stock habis</p>
                </header>
  
                <footer
                  class="flex items-center justify-between leading-none p-2 md:p-4"
                >
                  <span
                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                    >{{ car.qty }} Unit</span
                  >
                  <span
                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                    >Rp. {{ car.tarif_sewa }} / Jam</span
                  >
                  <Link :href="`/rent/${car.id}/cars`" class="btn-indigo">Sewa</Link>
                  
                </footer>
              </article>
              <!-- END Article -->
              </div>
          <!-- END Column -->

           
  
          
      </div>
            </div>
          <div v-if="cars.data.length === 0"> No cars found</div>

          
      <pagination class="mt-6" :links="cars.links" />
        </div>
      </div>
    </div>
  </template>
  <script>
  import { Head, Link } from "@inertiajs/vue3";
  import Icon from "@/Shared/Icon.vue";
  import Layout from "@/Shared/Layout.vue";
  import Pagination from "@/Shared/Pagination.vue";
  import SearchFilter from "@/Shared/SearchFilter.vue";
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
      filters: Object,
      cars: Object
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
          this.$inertia.get('/list-cars', pickBy(this.form), { preserveState: true })
        }, 150),
      },
    },
    methods: {
      reset() {
        this.form = mapValues(this.form, () => null)
      },
    },
  };
  </script>