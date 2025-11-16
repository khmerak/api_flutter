@extends('layouts.index')

@section('main')
    <div id="app" class="p-6">

        <!-- Page Title + Add Button -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold">Products</h1>
            <button @click="openAddModal" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                + Add Product
            </button>
        </div>

        <!-- Products Table -->
        <div class="bg-white shadow rounded-lg p-4">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-2 text-left">#</th>
                        <th class="px-4 py-2 text-left">Image</th>
                        <th class="px-4 py-2 text-left">Title</th>
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Stock</th>
                        <th class="px-4 py-2 text-left">Description</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(product, index) in products" :key="product.id"
                        class="border-b hover:bg-gray-50 align-top">
                        <td class="px-4 py-2 align-middle">@{{ index + 1 }}</td>

                        <!-- Image -->
                        <td class="px-4 py-2">
                            <img v-if="product.image_url" :src="product.image_url" class="w-12 h-12 rounded object-cover">
                            <span v-else class="text-gray-400">No Image</span>
                        </td>

                        <td class="px-4 py-2 font-semibold align-middle">@{{ product.title }}</td>
                        <td class="px-4 py-2 align-middle">$@{{ product.price }}</td>
                        <td class="px-4 py-2 align-middle">@{{ product.category?.name || 'N/A' }}</td>
                        <td class="px-4 py-2 align-middle">@{{ product.stock ?? 0 }}</td>
                        <td class="px-4 py-2 align-middle">@{{ (product.description || '').slice(0, 80) }}<span
                                v-if="product.description && product.description.length > 80">...</span></td>

                        <td class="px-4 py-2 text-center align-middle">
                            <button @click="openEditModal(product)" class="text-blue-600 hover:underline mr-3">Edit</button>
                            <button @click="deleteProduct(product.id)" class="text-red-600 hover:underline">Delete</button>
                        </td>
                    </tr>

                    <tr v-if="products.length === 0">
                        <td colspan="8" class="text-center py-6 text-gray-500">No products found.</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- ADD / EDIT MODAL -->
        <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow-lg w-full max-w-xl">
                <h2 class="text-xl font-bold mb-4">@{{ isEdit ? 'Edit Product' : 'Add Product' }}</h2>

                <!-- TITLE -->
                <div class="mb-3">
                    <label class="block font-semibold mb-1">Title</label>
                    <input type="text" v-model="form.title" class="border px-3 py-2 w-full rounded" />
                </div>

                <!-- DESCRIPTION -->
                <div class="mb-3">
                    <label class="block font-semibold mb-1">Description</label>
                    <textarea v-model="form.description" class="border px-3 py-2 w-full rounded"></textarea>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- PRICE -->
                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Price</label>
                        <input type="number" step="0.01" v-model.number="form.price"
                            class="border px-3 py-2 w-full rounded" />
                    </div>

                    <!-- STOCK -->
                    <div class="mb-3">
                        <label class="block font-semibold mb-1">Stock Quantity</label>
                        <input type="number" v-model.number="form.stock" class="border px-3 py-2 w-full rounded" />
                    </div>
                </div>

                <!-- CATEGORY -->
                <div class="mb-3">
                    <label class="block font-semibold mb-1">Category</label>
                    <select v-model="form.category_id" class="border px-3 py-2 w-full rounded">
                        <option value="" disabled>Select Category</option>
                        <option v-for="cat in categories" :value="cat.id">@{{ cat.name }}</option>
                    </select>
                </div>

                <!-- IMAGE -->
                <div class="mb-3">
                    <label class="block font-semibold mb-1">Image</label>
                    <input type="file" @change="handleImage" accept="image/*" />
                    <div class="mt-3 flex items-center space-x-3">
                        <div v-if="previewImage">
                            <img :src="previewImage" class="w-24 h-24 object-cover rounded" />
                        </div>
                        <div v-else-if="isEdit && existingImageUrl">
                            <img :src="existingImageUrl" class="w-24 h-24 object-cover rounded" />
                        </div>
                        <div v-else class="text-gray-400">No image selected</div>
                    </div>
                </div>

                <!-- ACTIONS -->
                <div class="flex justify-end space-x-3 mt-4">
                    <button @click="closeModal" class="px-4 py-2 bg-gray-300 rounded">Cancel</button>
                    <button @click="isEdit ? updateProduct() : addProduct()"
                        class="px-4 py-2 bg-blue-600 text-white rounded">
                        @{{ isEdit ? 'Update' : 'Save' }}
                    </button>
                </div>
            </div>
        </div>

    </div>

    <!-- Vue and Axios -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        const app = Vue.createApp({
            data() {
                return {
                    products: [],
                    categories: [],
                    showModal: false,
                    isEdit: false,
                    editId: null,
                    previewImage: null,
                    existingImageUrl: null,

                    form: {
                        title: "",
                        description: "",
                        price: null,
                        stock: null,
                        category_id: "",
                        image: null,
                    },
                };
            },

            mounted() {
                this.getProducts();
                this.getCategories();
            },

            methods: {
                async getProducts() {
                    const res = await axios.get('/api/products');
                    this.products = res.data;
                },

                async getCategories() {
                    const res = await axios.get('/api/categories');
                    this.categories = res.data;
                },

                openAddModal() {
                    this.resetForm();
                    this.previewImage = null;
                    this.existingImageUrl = null;
                    this.isEdit = false;
                    this.showModal = true;
                },

                openEditModal(product) {
                    this.isEdit = true;
                    this.showModal = true;
                    this.editId = product.id;

                    this.form.title = product.title || "";
                    this.form.description = product.description || "";
                    this.form.price = product.price ?? 0;
                    this.form.stock = product.stock ?? 0;
                    this.form.category_id = product.category_id || "";

                    this.form.image = null;
                    this.previewImage = null;
                    this.existingImageUrl = product.image_url || null;
                },

                handleImage(event) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.form.image = file;
                    this.previewImage = URL.createObjectURL(file);
                },

                async addProduct() {
                    const formData = new FormData();
                    formData.append('title', this.form.title);
                    formData.append('description', this.form.description || "");
                    formData.append('price', this.form.price ?? 0);
                    formData.append('stock', this.form.stock ?? 0);
                    formData.append('category_id', this.form.category_id);

                    if (this.form.image instanceof File) {
                        formData.append('image', this.form.image);
                    }

                    await axios.post('/api/products', formData);
                    await this.getProducts();
                    this.closeModal();
                },

                async updateProduct() {
                    const formData = new FormData();
                    formData.append('_method', 'PUT');
                    formData.append('title', this.form.title);
                    formData.append('description', this.form.description || "");
                    formData.append('price', this.form.price ?? 0);
                    formData.append('stock', this.form.stock ?? 0);
                    formData.append('category_id', this.form.category_id);

                    if (this.form.image instanceof File) {
                        formData.append('image', this.form.image);
                    }

                    await axios.post(`/api/products/${this.editId}`, formData);
                    await this.getProducts();
                    this.closeModal();
                },

                async deleteProduct(id) {
                    if (!confirm('Delete this product?')) return;
                    await axios.delete(`/api/products/${id}`);
                    await this.getProducts();
                },

                resetForm() {
                    this.form = {
                        title: "",
                        description: "",
                        price: null,
                        stock: null,
                        category_id: "",
                        image: null,
                    };
                },

                closeModal() {
                    if (this.previewImage) {
                        URL.revokeObjectURL(this.previewImage);
                    }
                    this.showModal = false;
                },
            }
        });

        app.mount('#app');
    </script>
@endsection
