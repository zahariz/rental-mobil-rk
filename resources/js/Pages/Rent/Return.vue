<template>
  <div>
    <Head title="Pengembalian Mobil" />
    <h1 class="mb-8 text-3xl font-bold">
      <Link class="text-indigo-400 hover:text-indigo-600" href="/transaction"
        >Transaction</Link
      >
      <span class="text-indigo-400 font-medium">/</span> Return
    </h1>
    <div class="flex items-center justify-between mb-6">
      <search-filter v-model="form.search" class="mr-4 w-full max-w-md" @reset="reset">
      </search-filter>
    </div>
    <div class="w-full bg-white rounded-md shadow overflow-hidden">
      <form @submit.prevent="store">
        <div class="container my-12 mx-auto px-4 md:px-12">
          <div class="flex flex-wrap -mx-1 lg:-mx-4">
            <!-- Column -->
            <div class="my-1 px-1 w-full md:w-1/2 lg:my-4 lg:px-4 lg:w-1/3" v-for="rent in rents.data" :key="rent.id">
              <!-- Article -->
              <article class="overflow-hidden rounded-lg shadow-lg" >
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
                      {{ rent.cars.merek }}
                    </a>
                  </h1>
                  <p class="text-grey-darker text-sm"> <span class="font-bold">{{ rent.cars.no_plat }}</span> - {{rent.status}}</p>
                </header>

                <footer
                  class="flex items-center justify-between leading-none p-2 md:p-4"
                >
                  <Link class="btn-indigo" :href="`/return/${rent.id}/cars`">
                    <span>Return</span>
                  </Link>
                  <div class="px-6 pt-4 pb-2">
                    <span
                      class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                      >{{ calculateDuration(rent) }}</span
                    >
                    <span
                      class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2 mb-2"
                      >{{ formatPrice(rent.harga) }}</span
                    >
                  </div>
                </footer>
                
              </article>
              <!-- END Article -->
            </div>
            <div v-if="rents.data.length === 0">
                    <div class="flex items-center px-6 py-4">
                        
                        No rent found
                    </div>
            </div>

            <!-- END Column -->
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
  
  <script>
import { Head, Link } from "@inertiajs/vue3";
import Layout from "@/Shared/Layout.vue";
import TextInput from "@/Shared/TextInput.vue";
import SelectInput from "@/Shared/SelectInput.vue";
import LoadingButton from "@/Shared/LoadingButton.vue";
import SearchFilter from "@/Shared/SearchFilter.vue";
import throttle from 'lodash/throttle'
    import pickBy from 'lodash/pickBy'
    import mapValues from 'lodash/mapValues'

export default {
  components: {
    Head,
    Link,
    LoadingButton,
    SelectInput,
    TextInput,
    SearchFilter,
  },
  layout: Layout,
  remember: "form",
  props: {
    rents: Object,
    filters: Object
  },
  data() {
    return {
      form: this.$inertia.form({
        mobil: null,
        tipe: null,
        qty: null,
        penalty: null,
        search: this.filters.search
      }),
    };
  },
        watch: {
            form: {
            deep: true,
            handler: throttle(function () {
                this.$inertia.get(`/return`, pickBy(this.form), { preserveState: true })
            }, 150),
            },
        },
  methods: {
    store() {
      this.form.post("/organizations");
    },
    reset() {
            this.form = mapValues(this.form, () => null)
            },
            calculateDuration(rent) {
      const start = new Date(rent.tanggal_mulai);
      const end = new Date(rent.tanggal_selesai);
      const durationInMilliseconds = end - start;
      const days = Math.floor(durationInMilliseconds / (1000 * 60 * 60 * 24));
      const hours = Math.floor((durationInMilliseconds % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      return `${days} days ${hours} hours`;
    },
    formatPrice(price) {
      const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        currencyDisplay: 'symbol',
      });

      return formatter.format(price);
    },

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
    }

  },
};
</script>