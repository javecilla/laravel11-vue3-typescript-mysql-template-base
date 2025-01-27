import axios from "@/libs/axios";

export const apiService = {
    async get(url: string) {
        return await handleRequest(() => axios.get(url));
    },
    async post(url: string, payload: any) {
        return await handleRequest(() => axios.post(url, payload));
    },
    async put(url: string, payload: any) {
        return await handleRequest(() => axios.put(url, payload));
    },
    async patch(url: string, payload: any) {
        return await handleRequest(() => axios.patch(url, payload));
    },
    async delete(url: string) {
        return await handleRequest(() => axios.delete(url));
    },
};

const handleRequest = async (request: () => Promise<any>) => {
    let errorMessage: string | null = null;
    let data: any = null;

    try {
        const response = await request();
        data = response.data;
    } catch (error) {
        if (
            error &&
            error.response &&
            error.response.data &&
            error.response.data.message
        ) {
            errorMessage = error.response.data.message;
        } else {
            errorMessage = "An unexpected error occurred.";
        }
    }

    return { data, errorMessage };
};
