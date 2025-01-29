<template>
    <div class="bg-white text-dark p-4 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-4">
            javecilla:// laravel11-vue3-typescript-mysql-template-base
        </h2>
    </div>
    <div class="flex justify-center mt-8 pb-8">
        <div
            class="w-full max-w-4xl p-5 text-center bg-white rounded-lg shadow-md dark:bg-gray-800"
        >
            <div v-if="loading" class="text-dark-500">Loading...</div>
            <div v-else-if="errorMessage" class="text-red-500">
                {{ errorMessage }}
            </div>
            <Chart
                v-else
                :chartData="chartData"
                :chartPreferences="chartPreferences"
            />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import Chart from "@/components/ChartComponent.vue";
import { useTesting } from "@/composables/useTesting";
import { ITestingData } from "@/types/testing";

const { data, errorMessage, loading, fetchTestings } = useTesting();
const chartData = ref<ITestingData | null>(null);

// Define chart preferences
const chartPreferences = ref({
    chartType: "bar" as const, // Use `as const` to infer the literal type
    backgroundColor: "#43bb1c",
    borderColor: "#3aad15",
    borderWidth: 1,
});

onMounted(async () => {
    await fetchTestings();
    if (data.value) {
        chartData.value = {
            labels: data.value.labels,
            title: data.value.title,
            data: data.value.data,
        };
    }
});
</script>

<style scoped>
.bg-white {
    background-color: #ffffff;
}
.text-dark {
    color: #333333;
}
</style>
