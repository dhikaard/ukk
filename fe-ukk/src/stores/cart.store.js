import { defineStore } from 'pinia';
import { showAddCart } from '@/utils/toast-service';
import { ApiConstant } from '@/utils/api-constant';
import callApi from '@/utils/api-connect';

export const useCartStore = defineStore('cart.store', {
    state: () => ({
        api: ApiConstant.ADD_RENT,
        items: [],
        showCart: false,
        rentDates: null
    }),

    getters: {
        duration() {
            if (!this.rentDates?.[0] || !this.rentDates?.[1]) return 1;
            
            // Calculate duration in days
            const start = new Date(this.rentDates[0]);
            const end = new Date(this.rentDates[1]);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            
            return diffDays + 1; // Include both start and end days
        },

        total() {
            return this.items.reduce((sum, item) => {
                // Calculate item total including duration
                return sum + (item.price * item.quantity * this.duration);
            }, 0);
        },

        itemCount() {
            return this.items.reduce((count, item) => count + item.quantity, 0);
        }
    },

    actions: {
        async addToCart(product, quantity, dates, toast) {
            try {
                if (!Array.isArray(dates)) {
                    throw new Error('Tanggal sewa harus dipilih');
                }
        
                if (this.items.length > 0) {
                    // Compare date strings for validation
                    const existingStart = this.rentDates[0].toISOString();
                    const existingEnd = this.rentDates[1].toISOString();
                    const newStart = dates[0].toISOString();
                    const newEnd = dates[1].toISOString();

                    if (existingStart !== newStart || existingEnd !== newEnd) {
                        throw new Error('Tanggal sewa harus sama untuk semua item');
                    }
                } else {
                    this.rentDates = dates;
                }
        
                const existingItem = this.items.find(item => 
                    item.id === product.id && 
                    item.selectedSize === product.selectedSize
                );
        
                if (existingItem) {
                    existingItem.quantity += quantity;
                } else {
                    this.items.push({
                        ...product,
                        quantity,
                        dates
                    });
                }
        
                // Save dates as ISO strings
                localStorage.setItem('cart', JSON.stringify({
                    items: this.items,
                    rentDates: [
                        this.rentDates[0].toISOString(),
                        this.rentDates[1].toISOString()
                    ]
                }));
        
                if (toast) {
                    showAddCart(toast);
                }
            } catch (error) {
                throw error;
            }
        },
        loadCart() {
            const savedCart = localStorage.getItem('cart');
            if (savedCart) {
                const { items, rentDates } = JSON.parse(savedCart);
                this.items = items;
                // Convert date strings back to Date objects
                if (rentDates) {
                    this.rentDates = [
                        new Date(rentDates[0]), 
                        new Date(rentDates[1])
                    ];
                }
            }
        },

        removeItem(productId) {
            const index = this.items.findIndex(item => item.id === productId);
            if (index > -1) {
                this.items.splice(index, 1);
                if (this.items.length === 0) {
                    this.rentDates = null;
                }
            }
        },

        updateQuantity(productId, quantity) {
            const item = this.items.find(item => item.id === productId);
            if (item) {
                item.quantity = quantity;
            }
        },

        async checkout() {
            try {
                const token = localStorage.getItem('token');
                if (!token) {
                    throw new Error('Silakan login terlebih dahulu untuk melakukan checkout');
                }
        
                const formatDate = (date) => {
                    return date.toISOString().slice(0, 19).replace('T', ' ');
                };
        
                const payload = {
                    api: ApiConstant.ADD_RENT,
                    body: {
                        rent_start_date: formatDate(this.rentDates[0]),
                        rent_end_date: formatDate(this.rentDates[1]),
                        items: this.items.map(item => ({
                            items_id: item.id,
                            qty: item.quantity,
                            size: item.selectedSize || null
                        }))
                    }
                };
        
                const result = await callApi(payload);
                if (result.isOk) {
                    this.clearCart();
                    return result.data;
                }
                throw new Error(result.error?.message || 'Failed to create order');
            } catch (error) {
                throw error;
            }
        },
        clearCart() {
            this.items = [];
            this.rentDates = null;
            localStorage.removeItem('cart');
        }
    }
});