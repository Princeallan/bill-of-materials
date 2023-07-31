<template>
    <app-layout>
        <div class="card w-full overflow-hidden p-8 bg-white shadow-lg rounded">

            <div class="flex flex-wrap mb-3">
                <div class="w-full md:w-1/2 px-3 mb-3 md:mb-0">
                    <label class="block text-gray-700 text-sm font-bold mb-2"
                           for="username">
                        Item Product <span class="text-red-400">*</span>
                    </label>
                    <v-select
                        class="w-full mb-10 relative"
                        v-model="selectedProduct"
                        :options="products"
                        label="name"
                        placeholder="Select a product"
                        @input="onProductSelect"
                    />

                    <label class="md:w-full block text-gray-500 font-bold">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                            UOM
                        </label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="project"
                            type="text"
                            v-model="bom.uom"
                            placeholder="UOM">
                    </label>
                    <label class="md:w-full block text-gray-500 font-bold mt-3">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                            Quantity <span class="text-red-400">*</span>
                        </label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="project"
                            type="number"
                            v-model="bom.quantity"
                            placeholder="10,000">
                    </label>
                </div>
                <div class="w-full md:w-1/2 px-3 mb-6 md:mb-0 p-5">
                    <label class="md:w-full block text-gray-500 font-bold">
                        <input class="mr-2 leading-tight" type="checkbox" v-model="bom.is_active">
                        <span>
                                Is Active
                        </span>
                    </label>

                    <label class="md:w-full block text-gray-500 font-bold">
                        <input class="mr-2 leading-tight" type="checkbox" v-model="bom.is_default">
                        <span>
                            Is Default
                        </span>
                    </label>
                    <label class="md:w-full block text-gray-500 font-bold">
                        <input class="mr-2 leading-tight" type="checkbox" v-model="bom.allow_alternative">
                        <span>
                            Allow alternative
                        </span>
                    </label>
                    <label class="md:w-full block text-gray-500 font-bold">
                        <input class="mr-2 leading-tight" type="checkbox" v-model="bom.rate_set">
                        <span>
                            Set rate of sub-assembly item based on BOM
                        </span>
                    </label>
                    <label class="md:w-full block text-gray-500 font-bold mt-5">
                        <label class="block text-gray-700 text-sm font-bold mb-2" for="project">
                            Project
                        </label>
                        <input
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            id="project"
                            type="text"
                            v-model="bom.project"
                            placeholder="Project">
                    </label>
                </div>
            </div>
            <div class="flex items-center w-full mb-3 p-5">
                <div class="w-1/2 mr-3">
                    <v-select
                        class="w-full mr-2 relative"
                        v-model="selectedMaterial"
                        :options="products"
                        label="name"
                        placeholder="Select a product"
                        @input="onProductSelect"
                    />
                </div>
                <!-- Add row button -->
                <button class="px-4 py-2 bg-blue-500 text-white rounded" @click="addMaterial">
                    Add Material
                </button>
            </div>

            <div class="relative overflow-hidden shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 m-5">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th>No</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>UOM</th>
                        <th>Rate(KES)</th>
                        <th>Amount(KES)</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in tableRows" :key="index">
                        <td>{{ index + 1 }}</td>
                        <td>{{ row.name }}</td>
                        <td>
                            <input
                                class="shadow appearance-none border rounded w-2/3 py-2 px-3 text-gray-500 leading-tight focus:outline-none focus:shadow-outline"
                                id="qty"
                                v-model="row.qty"
                                @change="syncItemPrice()"
                                type="number">
                        </td>
                        <td>{{ row.uom }}</td>
                        <td>{{ row.price }}</td>
                        <td>{{ row.amount }}</td>
                        <td>
                            <button
                                class="px-4 py-2 bg-red-500 text-white rounded"
                                @click="removeRow(index)">
                                Remove
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex justify-between">
                <button
                    class="px-4 py-2 bg-blue-500 text-white rounded mt-5"
                    @click="submitBom()">
                    Submit
                </button>
            </div>
        </div>
    </app-layout>
</template>

<script setup>
import AppLayout from "../layouts/AppLayout.vue";
import {ref, onMounted} from 'vue';

// Selected product and table rows data
const selectedProduct = ref(null);
const selectedMaterial = ref(null);
const bom = ref({});
const tableRows = ref([]);
const products = ref([]);
const token = localStorage.getItem('APP_USER_TOKEN')

const headers = {
    headers: {
        Authorization: 'Bearer ' + token
    }
}

onMounted(() => {
    handleProducts()
});

const handleProducts = async () => {
    const req = await axios.get('/api/products', headers)
    products.value = req.data.data
}

function syncItemPrice() {
    const existingRow = tableRows.value.find((row) => row.id === selectedMaterial.value.id);

    existingRow.amount = existingRow.qty * existingRow.price;
}

const onProductSelect = () => {
    if (selectedMaterial.value) {
        // Check if the product is already in the table
        const existingRow = tableRows.value.find((row) => row.id === selectedMaterial.value.id);

        if (existingRow) {
            // If the product exists in the table, increase quantity and amount
            existingRow.qty++;
            existingRow.amount = existingRow.qty * existingRow.retail;
        } else {
            // If the product is not in the table, add a new row
            tableRows.value.push({
                ...selectedMaterial.value,
                qty: 1,
                price: selectedMaterial.value.price,
                amount: selectedMaterial.value.price,
            });
        }
        // Clear the selectedMaterial after adding it to the table
        selectedMaterial.value = null;
    }
};

// Method for adding a new row to the table
const addMaterial = () => {
    if (selectedMaterial.value) {
        // Check if the product is already in the table
        const existingRow = tableRows.value.find((row) => row.id === selectedMaterial.value.id);

        if (existingRow) {
            // If the product exists in the table, increase quantity and amount
            existingRow.qty++;
            existingRow.amount = existingRow.qty * existingRow.price;
        } else {
            // If the product is not in the table, add a new row
            tableRows.value.push({
                ...selectedMaterial.value,
                qty: 1,
                amount: selectedMaterial.value.price,
            });
        }
    }
};

const removeRow = (index) => {
    alert("Are you sure you want to remove the material")
    console.log(tableRows.value, index)
    tableRows.value.splice(index, 1);
};

const submitBom = async () => {
    const formData = {
        product_id: selectedProduct.value.id,
        bom: bom.value,
        rawMaterials: tableRows.value
    }

     axios.post("/api/bom", formData, headers).then((response) => {

        refreshForm()
        alert("Bom submitted successfully")
        console.log("Form data saved successfully:", response.data);
    })
        .catch((error) => {
            console.error("Error saving form data:", error);
        });

}

function refreshForm() {
    selectedProduct.value = ''
    selectedMaterial.value = ''
    bom.value = {}
    tableRows.value = []
}
</script>

