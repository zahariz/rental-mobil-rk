<template>
    <div>
      <Head title="Sewa Mobil" />
      <h1 class="mb-8 text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/transaction">Transaction</Link>
        <span class="text-indigo-400 font-medium">/</span> Rental
      </h1>
      <div class="max-w-3xl w-full bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <div class="flex flex-wrap -mx-1 lg:-mx-4">
          <!-- Column -->
         <div class="my-1 px-1 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/2">
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
                      {{ cars.merek }}
                    </a>
                  </h1>
                  <p class="text-grey-darker text-sm" v-if="cars.qty > 0">Ready</p>
                  <p class="text-grey-darker text-sm" v-if="cars.qty < 0">Stock habis</p>
                </header>
  
                <footer
                  class="flex items-center justify-between leading-none p-2 md:p-4"
                >
                  <span
                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                    >{{ cars.qty }} Unit</span
                  >
                  <span
                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                    >Rp. {{ cars.tarif_sewa }} / Jam</span
                  >
                  
                </footer>
              </article>
              <!-- END Article -->
              </div>
          <!-- END Column -->

           
  
          
      </div>
            <text-input v-model="form.car_id" :error="form.errors.car_id" type="hidden" />
            <text-input v-model="form.tanggal_mulai" :error="form.errors.tanggal_mulai" type="datetime-local" class="pb-8 pr-6 w-full lg:w-full" label="Tanggal Mulai" />
            <text-input v-model="form.tanggal_selesai" :error="form.errors.tanggal_selesai" type="datetime-local"  class="pb-8 pr-6 w-full lg:w-full" label="Tanggal Selesai" />
            <text-input v-model="form.harga" class="pb-8 pr-6 w-full lg:w-full" label="Grand Total" />
          </div>
          <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <loading-button :loading="form.processing" class="btn-indigo" type="submit">Create</loading-button>
          </div>
        </form>
      </div>
    </div>
  </template>
  
  <script>
  import { Head, Link } from '@inertiajs/vue3'
  import Layout from '@/Shared/Layout.vue'
  import TextInput from '@/Shared/TextInput.vue'
  import SelectInput from '@/Shared/SelectInput.vue'
  import LoadingButton from '@/Shared/LoadingButton.vue'
  
  export default {
    components: {
      Head,
      Link,
      LoadingButton,
      SelectInput,
      TextInput,
    },
    
    layout: Layout,
    remember: 'form',
    props: {
        cars : Object
    },
    data() {
      return {
        form: this.$inertia.form({
          tanggal_mulai: null,
          tanggal_selesai: null,
          car_id: this.cars.id,
          status: 'Rented',
          qty: 1,
          harga: null
        }) // Initialize grandTotal
      }
    },
    methods: {
     // Method to calculate grand total
     calculateGrandTotal() {
      // Calculate the duration in hours
      const startDate = new Date(this.form.tanggal_mulai)
      const endDate = new Date(this.form.tanggal_selesai)
      const durationInHours = (endDate - startDate) / (1000 * 60 * 60)

      // Calculate grand total
      const total = this.cars.tarif_sewa * durationInHours
      if(total < 0)
      {
        this.form.harga = 0
      } else {
        this.form.harga = total
      }
    },
    store() {
      // Now you can post the form data or do anything else you need to do
      this.form.post(`/rent/${this.cars.id}/cars`)
    },
  },
  watch: {
    // Watch for changes in start and end dates and recalculate grand total
    'form.tanggal_mulai': function(newStartDate, oldStartDate) {
      this.calculateGrandTotal()
    },
    'form.tanggal_selesai': function(newEndDate, oldEndDate) {
      this.calculateGrandTotal()
    },
  },
  }
  </script>