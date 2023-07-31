<template>
    <div>
        <div class="mb-3">
            <label for="productSelect">Select Product:</label>
            <select
                class="form-select"
                id="productSelect"
                v-model="selectedProduct"
                @change="addProductToTable"
            >
                <option value="">Select a product</option>
                <option v-for="product in filteredProducts" :value="product">
                    {{ product.name }}
                </option>
            </select>
        </div>

        <table class="table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Quantity</th>
                <th>Amount</th>
                <th>Retail</th>
                <th>Edit</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(product, index) in selectedProducts" :key="index">
                <td v-if="!product.isEditing">{{ product.name }}</td>
                <td v-else>
                    <input v-model="product.name" class="form-control">
                </td>
                <td v-if="!product.isEditing">{{ product.qty }}</td>
                <td v-else>
                    <input v-model="product.qty" type="number" class="form-control">
                </td>
                <td>{{ product.amount }}</td>
                <td>{{ product.retail }}</td>
                <td>
                    <button class="btn btn-sm btn-primary" @click="toggleEdit(index)">
                        {{ product.isEditing ? "Save" : "Edit" }}
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    data() {
        return {
            products: [
                { id: 1, name: "Product A", retail: 10.0 },
                { id: 2, name: "Product B", retail: 15.0 },
                // Add more products here...
            ],
            selectedProduct: null,
            selectedProducts: [],
        };
    },
    computed: {
        filteredProducts() {
            // Perform filtering on products based on search criteria if needed
            // For example: return this.products.filter(product => product.name.toLowerCase().includes(this.searchQuery.toLowerCase()));
            return this.products;
        },
    },
    methods: {
        addProductToTable() {
            if (this.selectedProduct) {
                const existingProductIndex = this.selectedProducts.findIndex(
                    (p) => p.id === this.selectedProduct.id
                );

                if (existingProductIndex !== -1) {
                    this.selectedProducts[existingProductIndex].qty++;
                    this.selectedProducts[existingProductIndex].amount =
                        this.selectedProducts[existingProductIndex].qty *
                        this.selectedProduct.retail;
                } else {
                    this.selectedProducts.push({
                        ...this.selectedProduct,
                        qty: 1,
                        amount: this.selectedProduct.retail,
                    });
                }
                this.selectedProduct = null;
            }
        },
        toggleEdit(index) {
            this.selectedProducts[index].isEditing = !this.selectedProducts[index].isEditing;
            // If the product is being edited, update the amount based on the new quantity
            if (this.selectedProducts[index].isEditing) {
                this.selectedProducts[index].amount =
                    this.selectedProducts[index].qty * this.selectedProducts[index].retail;
            }
        },
    },
};
</script>
