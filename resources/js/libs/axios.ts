import Axios, { AxiosInstance, AxiosError } from "axios";
import { HttpStatusCodes } from "@/constants/httpStatusCodes";

interface ApiResponse {
    message?: string;
    data?: {
        errors?: Record<string, string[]>;
    };
}

const axios: AxiosInstance = Axios.create({
    baseURL: `${import.meta.env.VITE_APP_URL}/api`,
    timeout: 60000,
    withCredentials: true,
    xsrfCookieName: "XSRF-TOKEN",
    xsrfHeaderName: "X-XSRF-TOKEN",
    headers: {
        Accept: "application/json",
    },
});

// Response interceptor to flatten the response data
axios.interceptors.response.use(
    (response) => {
        return response.data; // Only return the data
    },
    (error: AxiosError<ApiResponse>) => {
        //console.log("heerror: " + error);
        const errorInfo = {
            status: error.response?.status,
            original: error,
            validation: {},
            message: null,
        };

        if (error.response?.data) {
            switch (error.response.status) {
                case HttpStatusCodes.UNPROCESSABLE_ENTITY:
                    errorInfo.message =
                        error.response.data.message ?? "Validation error"; // Use the message from the response or a default message
                    if (
                        typeof error.response.data?.errors === "object" &&
                        error.response.data.errors !== null
                    ) {
                        errorInfo.validation = {
                            general: Object.values(
                                error.response.data?.errors
                            ).flat(), // Store the error strings
                        };
                    }
                    break;
                case HttpStatusCodes.BAD_REQUEST:
                    errorInfo.message = "Bad Request";
                    break;
                case HttpStatusCodes.FORBIDDEN:
                    errorInfo.message = error.response.data.message;
                    break;
                case HttpStatusCodes.UNAUTHORIZED:
                    errorInfo.message = error.response.data.message;
                    break;
                case HttpStatusCodes.CONFLICT:
                    errorInfo.message = error.response.data.message;
                    break;
                case HttpStatusCodes.TOO_MANY_REQUESTS:
                    errorInfo.message = error.response.data.message;
                    break;
                default:
                    console.error("axios casedefault:" + errorInfo.message);
                    errorInfo.message =
                        "Something went wrong, Please try again.";
                    break;
            }
        } else {
            errorInfo.message = "Network error or server did not respond.";
        }
        error.errorInfo = errorInfo;

        return Promise.reject(errorInfo);
    }
);

export default axios;
