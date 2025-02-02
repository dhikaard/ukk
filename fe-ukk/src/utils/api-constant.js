export const ApiConstant = {
    LOGIN: {
        path: 'login',
        method: 'POST',
        keyList: ''
    },
    REGISTER: {
        path: 'register',
        method: 'POST',
        keyList: ''
    },
    GET_ITEMS: {
        path: 'items',
        method: 'GET',
    },
    GET_ITEM_DETAIL: {
        path: 'items/:code',
        method: 'GET',
    },
    GET_CATEGORIES: {
        path: 'categories',
        method: 'GET'
    },
    ADD_RENT: {
        path: 'rent',
        method: 'POST'
    },
    GET_RENT_HISTORY: {
        path: 'history',
        method: 'GET'
    },
    CANCEL_RENT: {
        path: 'rent/cancel/:id',
        method: 'PUT'
    }
};
