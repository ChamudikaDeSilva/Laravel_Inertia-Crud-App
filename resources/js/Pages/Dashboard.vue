<template>
    <AuthenticatedLayout>
      <div class="mt-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-800 mb-4">Add New Student</h2>

            <form @submit.prevent="submitForm" enctype="multipart/form-data">

              <input type="hidden" name="_token" :value="csrfToken">

              <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" v-model="form.name" class="form-control">
              </div>

              <div class="mb-3">
                <label for="image" class="form-label">Image</label>
                <input type="file" id="image" @change="onFileChange" class="form-control">
              </div>

              <div class="mb-3">
                <label for="age" class="form-label">Age</label>
                <input type="number" id="age" v-model="form.age" class="form-control">
              </div>

              <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select id="status" v-model="form.status" class="form-select">
                  <option value="active">Active</option>
                  <option value="inactive">Inactive</option>
                </select>
              </div>

              <div class="mt-4">
                <button type="submit" class="btn btn-primary">Submit</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>

  <script>
  import 'bootstrap/dist/css/bootstrap.min.css';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import Swal from 'sweetalert2';

  export default {
    components: {
      AuthenticatedLayout,
    },
    data() {
      return {
        csrfToken: '{{ csrf_token() }}',
        form: {
          name: '',
          age: '',
          status: 'active',
          image: null
        }
      };
    },
    methods: {
      async submitForm() {
        try {
          const formData = new FormData();
          formData.append('name', this.form.name);
          formData.append('age', this.form.age);
          formData.append('status', this.form.status);
          formData.append('image', this.form.image);


          await this.$inertia.post(route('students.store'), formData);


          Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: 'Student created successfully.',
          });

          
          this.resetForm();
        } catch (error) {
          console.error('Error submitting form:', error);
        }
      },
      onFileChange(event) {
        this.form.image = event.target.files[0];
      },
      resetForm() {
        this.form.name = '';
        this.form.age = '';
        this.form.status = 'active';
        this.form.image = null;
      }
    }
  };
</script>
