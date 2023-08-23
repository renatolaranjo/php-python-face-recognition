import axios from "axios";

const api = axios.create({
  baseURL: process.env.MIX_APP_URL + "/api"
});

export default api;

