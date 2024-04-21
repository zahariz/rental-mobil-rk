<template>
    <div>
      <Head title="Return Mobil" />
      <h1 class="mb-8 text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/transaction">Transaction</Link>
        <span class="text-indigo-400 font-medium">/</span> Return
      </h1>
      <div class="max-w-3xl w-full bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store" >
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
                      {{ rents.cars.merek }}
                    </a>
                  </h1>
                  <p class="text-grey-darker text-sm" >{{ rents.status }}</p>
                </header>
  
                <footer
                  class="flex items-center justify-between leading-none p-2 md:p-4"
                >
                  <span
                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                    >{{ rents.qty }} Unit</span
                  >
                  <span
                    class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                    >Rp. {{ rents.cars.tarif_sewa }} / Jam</span
                  >
                  
                </footer>
              </article>
              <!-- END Article -->
              </div>
          <!-- END Column -->

           
  
          
      </div>
            <text-input v-model="form.tanggal_mulai" :error="form.errors.tanggal_mulai" disabled class="pb-8 pr-6 w-full lg:w-full" label="Tanggal Mulai" />
            <text-input v-model="form.tanggal_selesaiDisplay" :error="form.errors.tanggal_selesaiDisplay" disabled  class="pb-8 pr-6 w-full lg:w-full" label="Tanggal Selesai" />
            <text-input v-model="form.tanggal_selesai" :error="form.errors.tanggal_selesai" type="hidden"/>
            <text-input v-model="form.hargaDisplay" class="pb-8 pr-6 w-full lg:w-full" label="Total" />
            <text-input v-model="form.harga" type="hidden" />
            <text-input v-model="form.penaltyDisplay" class="pb-8 pr-6 w-full lg:w-full" label="Penalty" />
            <text-input v-model="form.penalty" type="hidden" />
            
          </div>
          <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <loading-button :loading="form.processing" class="btn-indigo" type="submit">Return</loading-button>
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
  import { format } from 'date-fns';
  
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
        rents : Object
    },
    data() {
        const tanggalMulai = format(new Date(this.rents.tanggal_mulai), 'dd MMMM yyyy HH:ii');
        const tanggalSelesai = format(new Date(this.rents.tanggal_selesai), 'dd MMMM yyyy HH:ii');
        const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        currencyDisplay: 'symbol',
      });
      const currentDate = new Date(); // Waktu saat ini
    const dueDate = new Date(this.rents.tanggal_selesai); // Tanggal selesai sewa
    const returnDate = currentDate; // Tanggal pengembalian (diambil dari waktu saat ini)
      const hoursLate = 0
      const penaltyRate = 0
    if (returnDate > dueDate) {
      const durationInMilliseconds = returnDate - dueDate;
      hoursLate = Math.floor(durationInMilliseconds / (1000 * 60 * 60)); // Menghitung keterlambatan dalam jam
      penaltyRate = rent.cars.tarif_sewa; // Misalnya, biaya penalty per jam terlambat adalah Rp. 10.000
    }
    const penalty = hoursLate * penaltyRate; // Menghitung total penalty
      

      return {
        form: this.$inertia.form({
          _method: 'put',
          tanggal_mulai: tanggalMulai,
          tanggal_selesaiDisplay: tanggalSelesai,
          tanggal_selesai: this.rents.tanggal_selesai,
          car_id: null,
          status: 'Rented',
          qty: 1,
          hargaDisplay: formatter.format(this.rents.harga),
          harga: this.rents.harga,
          penaltyDisplay: formatter.format(penalty),
          penalty: penalty
        }) // Initialize grandTotal
      }
      
    },
    methods: {
        calculatePenalty(rent) {
        const currentDate = new Date(); // Waktu saat ini
    const dueDate = new Date(rent.tanggal_selesai); // Tanggal selesai sewa
    const returnDate = currentDate; // Tanggal pengembalian (diambil dari waktu saat ini)

    if (returnDate > dueDate) {
      const durationInMilliseconds = returnDate - dueDate;
      const hoursLate = Math.floor(durationInMilliseconds / (1000 * 60 * 60)); // Menghitung keterlambatan dalam jam
      const penaltyRate = rent.cars.tarif_sewa; // Misalnya, biaya penalty per jam terlambat adalah Rp. 10.000
      const penalty = hoursLate * penaltyRate; // Menghitung total penalty

      // Menyimpan nilai penalty ke dalam form
      return this.formatPrice(penalty)
    }
    },
    store() {
      // Now you can post the form data or do anything else you need to do
      this.form.post(`/return/${this.rents.id}/cars`)
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