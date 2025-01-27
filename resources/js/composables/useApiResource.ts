import { ref } from "vue";
import { apiService } from "@/utils/apiService";

export function useApiResource<T>() {
    const data = ref<T | null>(null);
    const errorMessage = ref<string | null>(null);
    const loading = ref(false);

    const getResources = async (url: string) => {
        loading.value = true;
        errorMessage.value = null;

        try {
            const response = await apiService.get(url);
            data.value = response.data as T; // Cast the response data to the generic type T
        } catch (error) {
            errorMessage.value = "Failed to fetch data";
        } finally {
            loading.value = false;
        }
    };

    return {
        data,
        errorMessage,
        loading,
        getResources,
    };
}
