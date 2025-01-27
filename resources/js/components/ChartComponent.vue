<template>
    <div>
        <canvas ref="chart"></canvas>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import Chart, { ChartType, ChartData, ChartOptions } from "chart.js/auto";

interface IChartData {
    labels: string[];
    title: string;
    data: number[];
}

interface IChartPreferences {
    chartType: ChartType; //bar, pie, line, radar,
    backgroundColor: string;
    borderColor: string;
    borderWidth: number;
}

const props = defineProps<{
    chartData: IChartData | null;
    chartPreferences: IChartPreferences;
}>();

const chart = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart | null = null;

const renderChart = () => {
    if (chart.value && props.chartData) {
        const { labels, title, data: chartData } = props.chartData;
        const { chartType, backgroundColor, borderColor, borderWidth } =
            props.chartPreferences;

        // Destroy existing chart instance if it exists
        if (chartInstance) {
            chartInstance.destroy();
        }

        // Create new chart instance
        chartInstance = new Chart(chart.value, {
            type: chartType,
            data: {
                labels: labels,
                datasets: [
                    {
                        label: title,
                        data: chartData,
                        backgroundColor: backgroundColor,
                        borderColor: borderColor,
                        borderWidth: borderWidth,
                    },
                ],
            } as ChartData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
            } as ChartOptions,
        });
    }
};

// Watch for changes in chartData and re-render the chart
watch(() => props.chartData, renderChart, { immediate: true });

// Cleanup chart instance on unmount
onMounted(() => {
    renderChart();
});
</script>

<style scoped>
/* Add any specific styles for your chart component here */
</style>
