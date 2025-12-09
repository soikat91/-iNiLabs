const API_BASE = import.meta.env.VITE_API_BASE || 'http://127.0.0.1:8000/api'


async function request(path, opts = {}) {
  const res = await fetch(API_BASE + path, opts);
  const ct = res.headers.get("content-type") || "";
  let body = ct.includes("application/json") ? await res.json() : await res.text();

  if (!res.ok) {
    const err = new Error(body?.message || "Request failed");
    err.status = res.status;
    err.body = body;
    throw err;
  }

  return body;
}

export default {
  get(path, token) {
    return request(path, {
      headers: {
        "Authorization": token ? "Bearer " + token : "",
        "Accept": "application/json"
      }
    });
  },

  post(path, data, token) {
    return request(path, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "Authorization": token ? "Bearer " + token : ""
      },
      body: JSON.stringify(data)
    });
  },

  put(path, data, token) {
    return request(path, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
        "Authorization": token ? "Bearer " + token : ""
      },
      body: JSON.stringify(data)
    });
  },

  delete(path, token) {
    return request(path, {
      method: "DELETE",
      headers: {
        "Authorization": token ? "Bearer " + token : ""
      }
    });
  }
};
