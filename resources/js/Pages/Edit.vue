<template>
    <AuthenticatedLayout>
      <div class="container mt-5">
        <h1>Edit Student</h1>
        <form @submit.prevent="handleSubmit" class="mt-4" enctype="multipart/form-data">
          <input type="hidden" name="_token" :value="csrfToken">
          <div class="mb-3">
            <label for="name" class="form-label">Name:</label>
            <input type="text" id="name" v-model="formData.name" class="form-control" />
          </div>
          <div class="mb-3">
            <label for="age" class="form-label">Age:</label>
            <input type="number" id="age" v-model="formData.age" class="form-control" />
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select id="status" v-model="formData.status" class="form-control">
              <option value="active">Active</option>
              <option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="image" class="form-label">Image:</label>
            <input type="file" id="image" @change="handleFileChange" class="form-control" />
          </div>
          <button type="submit" class="btn btn-primary">Update Student</button>
        </form>
      </div>
    </AuthenticatedLayout>
  </template>

  <script>
  import { ref } from 'vue';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

  export default {
    components: {
      AuthenticatedLayout,
    },
    props: {
      student: {
        type: Object,
        default: () => ({}),
      },
      csrfToken: {
        type: String,
        required: true,
      },
    },
    setup(props) {
      const formData = ref({
        name: props.student.name || '',
        age: props.student.age || 0,
        status: props.student.status || '',
        image: null,
      });

      const handleSubmit = async () => {
        try {
          const payload = new FormData();
          payload.append('name', formData.value.name);
          payload.append('age', formData.value.age);
          payload.append('status', formData.value.status);
          if (formData.value.image) {
            payload.append('image', formData.value.image);
          }

          const response = await fetch(`/students-update/${props.student.id}`, {
            method: 'POST',
            body: payload,
            headers: {
              'X-CSRF-TOKEN': props.csrfToken,
            },
          });

          if (response.ok) {
            const data = await response.json();
            console.log('Student updated:', data);
          } else {
            throw new Error('Failed to update student');
          }
        } catch (error) {
          console.error('Error updating student:', error);
        }
      };

      const handleFileChange = (e) => {
        const file = e.target.files[0];
        formData.value.image = file;
      };

      return {
        formData,
        handleSubmit,
        handleFileChange,
      };
    },
  };
  </script>

  <style scoped>
  /* Add custom styles here if needed */
  </style>
