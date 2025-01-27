import { useApiResource } from "@/composables/useApiResource";
import { ITestingData } from "@/types/testing";

export function useTesting() {
    const { data, errorMessage, loading, getResources } =
        useApiResource<ITestingData>();

    const fetchTestings = async () => {
        await getResources("/test2");
    };

    return {
        data,
        errorMessage,
        loading,
        fetchTestings,
    };
}
