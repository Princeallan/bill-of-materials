<template>
    <div class="w-full flex flex-col sm:flex-row flex-grow overflow-hidden mt-5">

        <div class="sm:w-1/3 md:1/4 w-full flex-shrink flex-grow-0 p-4">
            <div class="sticky top-0 p-4 bg-gray-100 rounded-xl w-full">
                <ul class="flex sm:flex-col overflow-hidden content-center justify-between">
                    <router-link to="/home">
                        <li class="py-2 hover:bg-indigo-300 rounded">
                            <img src="//cdn.jsdelivr.net/npm/heroicons@1.0.1/outline/home.svg"
                                 class="w-7 sm:mx-2 mx-4 inline"/>
                            <span class="hidden sm:inline">Home</span>
                        </li>
                    </router-link>
                    <router-link to="/products-list">
                        <li class="py-2 hover:bg-indigo-300 rounded">
                            <img src="//cdn.jsdelivr.net/npm/heroicons@1.0.1/outline/home.svg"
                                 class="w-7 sm:mx-2 mx-4 inline"/>
                            <span class="hidden sm:inline">Product List</span>
                        </li>
                    </router-link>

                    <li class="py-2 hover:bg-indigo-300 rounded" @click="handleLogout">
                            <img src="//cdn.jsdelivr.net/npm/heroicons@1.0.1/outline/ban.svg"
                                 class="w-7 sm:mx-2 mx-4 inline"/> <span
                            class="hidden sm:inline">Logout</span>
                    </li>

                </ul>
            </div>
        </div>
        <main role="main" class="w-full h-full flex-grow p-3 overflow-auto bg-gray-100">

            <slot/>

        </main>
    </div>

</template>
<script setup>
import {useRouter} from "vue-router";

let router = useRouter();
const handleLogout = () => {
    localStorage.removeItem('APP_USER_TOKEN')
    router.push('/')
}
</script>
