<template>
    <div>
      <Head title="Create Mobil" />
      <h1 class="mb-8 text-3xl font-bold">
        <Link class="text-indigo-400 hover:text-indigo-600" href="/cars">Mobil</Link>
        <span class="text-indigo-400 font-medium">/</span> Create
      </h1>
      <div class="max-w-3xl w-full bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store" enctype="multipart/form-data">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input v-model="form.merek" :error="form.errors.merek" class="pb-8 pr-6 w-full lg:w-1/2" label="Nama Mobil " />
            <text-input v-model="form.model" :error="form.errors.model" class="pb-8 pr-6 w-full lg:w-1/2" label="Model " />
            <text-input v-model="form.no_plat" :error="form.errors.no_plat" class="pb-8 pr-6 w-full lg:w-1/2" label="No Plat " />
            <text-input v-model="form.qty" :error="form.errors.qty" type="number" class="pb-8 pr-6 w-full lg:w-1/2" label="Qty " />
            <text-input v-model="form.tarif_sewa" :error="form.errors.tarif_sewa" type="number" class="pb-8 pr-6 w-full lg:w-full" label="Harga (Per Jam)" />
            <!-- File input for Gambar -->
            <div class="pb-8 pr-6 w-full lg:w-full">
                <label for="gambar" class="block text-sm font-medium leading-5 text-gray-700">Gambar</label>
                <input id="gambar" type="file" class="mt-1 block w-full form-input" v-on:change="handleFileUpload">
                <span class="text-xs text-gray-600">Unggah gambar mobil</span>
            </div>
          </div>
          <div class="flex items-center justify-end px-8 py-4 bg-gray-50 border-t border-gray-100">
            <loading-button :loading="form.processing" class="btn-indigo" type="submit">Save</loading-button>
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
    data() {
      return {
        form: this.$inertia.form({
          merek: null,
          model: null,
          qty: null,
          no_plat: null,
          tarif_sewa: null,
          gambar: null
        }),
      }
    },
    methods: {
       // Method untuk menangani perubahan pada file input
        handleFileUpload(event) {
            // Mengambil file yang dipilih
            const file = event.target.files[0];
            // Menyimpan file dalam data form
            this.form.gambar = file;
        },
        store() {
            // Membuat FormData untuk mengirim data form, termasuk file gambar
            const formData = new FormData();
            formData.append('merek', this.form.merek);
            formData.append('model', this.form.model);
            formData.append('qty', this.form.qty);
            formData.append('no_plat', this.form.no_plat);
            formData.append('tarif_sewa', this.form.tarif_sewa);
            formData.append('gambar', this.form.gambar);

            // Mengirim data form menggunakan method POST dengan FormData
            this.form.post('/cars/create', formData);
        },
    },
  }
  </script>