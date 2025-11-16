@extends('layouts.index')

@section('main')
    <div id="app" class="p-6">

        <!-- Page Title + Add Button -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Categories</h1>

            <button @click="openAddModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                + Add Category
            </button>
        </div>

        <!-- CATEGORY TABLE -->
        <div class="bg-white shadow rounded p-4">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Image</th>
                        <th class="px-4 py-2 text-left">Name</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(category, index) in categories" :key="category.id" class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">@{{ index + 1 }}</td>
                        <td class="px-4 py-2">
                            <img :src="category.image_url" v-if="category.image_url" class="w-12 h-12 rounded object-cover">
                            <span v-else class="text-gray-400">No Image</span>
                        </td>
                        <td class="px-4 py-2 font-semibold">@{{ category.name }}</td>
                        <td class="px-4 py-2">@{{ category.description }}</td>

                        <td class="px-4 py-2 text-center">
                            <button @click="openEditModal(category)" class="text-blue-600 hover:underline mr-3">
                                Edit
                            </button>

                            <button @click="deleteCategory(category.id)" class="text-red-600 hover:underline">
                                Delete
                            </button>
                        </td>
                    </tr>

                    <tr v-if="categories.length === 0">
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            No categories found.
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- ADD / EDIT MODAL -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center">

            <div class="bg-white p-6 rounded shadow-lg w-96">
                <h2 class="text-xl font-bold mb-4">
                    @{{ isEdit ? "Edit Category" : "Add Category" }}
                </h2>

                <!-- NAME -->
                <div class="mb-3">
                    <label class="block font-semibold mb-1">Name</label>
                    <input type="text" v-model="form.name" class="border px-3 py-2 w-full rounded">
                </div>

                <!-- DESCRIPTION -->
                <div class="mb-3">
                    <label class="block font-semibold mb-1">Description</label>
                    <textarea v-model="form.description" class="border px-3 py-2 w-full rounded"></textarea>
                </div>

                <!-- IMAGE UPLOAD -->
                <div class="mb-3">
                    <label class="block font-semibold mb-1">Image</label>
                    <input type="file" @change="handleImage">

                    <!-- Preview -->
                    <div class="mt-2" v-if="previewImage">
                        <img :src="previewImage" class="w-20 h-20 rounded object-cover">
                    </div>
                </div>

                <!-- BUTTONS -->
                <div class="flex justify-end space-x-3 mt-4">
                    <button @click="closeModal" class="px-4 py-2 bg-gray-300 rounded">
                        Cancel
                    </button>

                    <button @click="isEdit ? updateCategory() : addCategory()"
                        class="px-4 py-2 bg-blue-600 text-white rounded">
                        @{{ isEdit ? "Update" : "Save" }}
                    </button>
                </div>

            </div>

        </div>

    </div>

    <!-- Vue 3 CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>

    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const app = Vue.createApp({
            data() {
                return {
                    categories: [],
                    showModal: false,
                    isEdit: false,
                    editId: null,
                    previewImage: null,

                    form: {
                        name: "",
                        description: "",
                        image: null,
                    }
                };
            },

            mounted() {
                this.getCategories();
            },

            methods: {
                // Get all categories
                async getCategories() {
                    const res = await axios.get("/api/categories");
                    this.categories = res.data;
                },

                // Open Add Modal
                openAddModal() {
                    this.resetForm();
                    this.previewImage = null;
                    this.showModal = true;
                    this.isEdit = false;
                },

                // Open Edit Modal
                openEditModal(category) {
                    this.isEdit = true;
                    this.showModal = true;
                    this.editId = category.id;

                    // Load data
                    this.form.name = category.name;
                    this.form.description = category.description;

                    // IMPORTANT: prevent sending old image as new
                    this.form.image = null;

                    // preview existing
                    this.previewImage = category.image_url;
                },

                // Handle new uploaded image
                handleImage(event) {
                    const file = event.target.files[0];
                    this.form.image = file;
                    this.previewImage = URL.createObjectURL(file);
                },

                // Add category
                async addCategory() {
                    let formData = new FormData();
                    formData.append("name", this.form.name);
                    formData.append("description", this.form.description);
                    if (this.form.image instanceof File) {
                        formData.append("image", this.form.image);
                    }

                    await axios.post("/api/categories", formData);
                    this.getCategories();
                    this.closeModal();
                },

                // Update category
                async updateCategory() {
                    let formData = new FormData();
                    formData.append("name", this.form.name);
                    formData.append("description", this.form.description);
                    formData.append("_method", "PUT");

                    // Only send image if user selects a new one
                    if (this.form.image instanceof File) {
                        formData.append("image", this.form.image);
                    }

                    await axios.post(`/api/categories/${this.editId}`, formData);
                    this.getCategories();
                    this.closeModal();
                },

                // Delete
                async deleteCategory(id) {
                    if (confirm("Delete this category?")) {
                        await axios.delete(`/api/categories/${id}`);
                        this.getCategories();
                    }
                },

                resetForm() {
                    this.form = {
                        name: "",
                        description: "",
                        image: null
                    };
                },

                closeModal() {
                    this.showModal = false;
                }
            }
        });

        app.mount("#app");
    </script>
@endsection
