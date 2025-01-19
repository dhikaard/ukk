import { reactive } from 'vue';

const local = reactive({
    isExists: (e) => isExists(e),
    get: (e) => get(e),
    set: (k, v) => set(k, v),
    getUser: () => {
        return JSON.parse(localStorage.getItem('user'));
    },
    remove: (e) => remove(e),
    removeExcept: (e) => removeExcept(e),
});

function get(e) {
    return localStorage.getItem(e);
}

function set(key, val) {
    localStorage.setItem(key, val);
}

function remove(...e) {
    if (e.length === 0) localStorage.clear();
    else {
        Object.keys(localStorage).forEach((k) => {
            if (e.includes(k)) {
                localStorage.removeItem(k);
            }
        });
    }
}

function removeExcept(...e) {
    if (e.length === 0) localStorage.clear();
    else {
        Object.keys(localStorage).forEach((k) => {
            if (!e.includes(k)) {
                localStorage.removeItem(k);
            }
        });
    }
}

export default local;
