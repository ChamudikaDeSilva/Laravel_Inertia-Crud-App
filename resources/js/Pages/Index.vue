<template>
    <AuthenticatedLayout>
      <div class="mt-8 max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <h1>Student List</h1>
            <table class="table table-sm">
              <thead>
                <tr>
                  <th v-for="header in headers" :key="header">
                    {{ header }}
                  </th>
                </tr>
              </thead>
              <tbody v-if="students.length">
                <tr v-for="student in students" :key="student.id">
                  <td>{{ student.name }}</td>
                  <td>{{ student.age }}</td>
                  <td>{{ student.status }}</td>
                  <td>
                    <img :src="getStudentImageUrl(student.image)" alt="Student Image" style="max-width: 100px;">
                  </td>
                  <td>
                    <button @click="editStudent(student)" class="btn btn-primary btn-sm mr-2">
                      Edit Student
                    </button>
                    <button @click="deleteStudent(student.id)" class="btn btn-danger btn-sm">
                      Delete Student
                    </button>
                  </td>
                </tr>
              </tbody>
              <tbody v-else>
                <tr>
                  <td colspan="5" class="text-center">No students found</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Swal from 'sweetalert2';


export default {
  components: {
    AuthenticatedLayout,
  },
  props: {
    students: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      headers: ["Name", "Age", "Status", "Image", "Action"],
    };
  },
  methods: {
    getStudentImageUrl(image) {
      return image ? `/storage/images/${image}` : '';
    },
    async editStudent(student) {
      try {

        const editUrl = `/students/${student.id}/edit`;

        // Navigate to the edit page and include the student details in the response
        await this.$inertia.visit(editUrl, {
          props: {
            student: student
          }
        });
      } catch (error) {
        console.error('Error redirecting to edit page:', error);

      }
    },

    async deleteStudent(id) {
    try {
        const confirmed = await Swal.fire({
        title: 'Are you sure?',
        text: 'You are about to delete this student.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
        });

        if (confirmed.isConfirmed) {
        // Use Inertia.js to delete the student
        await this.$inertia.delete(`/students-delete/${id}`);

        // If deletion is successful, update the student list
        this.$emit('students.destroy', id);

        Swal.fire(
            'Deleted!',
            'The student has been deleted.',
            'success'
        );
        }
    } catch (error) {
        console.error('Error deleting student:', error);
        // Handle delete error
        // For example, show an error message to the user
        Swal.fire(
        'Error!',
        'Failed to delete the student.',
        'error'
        );
    }
    }


  },
};
</script>
