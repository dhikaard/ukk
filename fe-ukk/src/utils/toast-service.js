export const showSuccessAdd = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil menambahkan!', life: 2000 });
};

export const showSuccessEdit = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil mengubah!', life: 2000 });
};

export const showSuccessRemove = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil menghapus!', life: 2000 });
};

export const showSuccessRegister = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil mendaftarkan akun, silakan masuk!', life: 2000 });
};

export const showSuccessLogin = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil masuk!', life: 2000 });
};

export const showSuccessResetPassword = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil menyetel ulang kata sandi!', life: 2000 });
};

export const showSuccessSendOtp = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil mengirim ulang kode OTP!', life: 2000 });
};

export const showErrorStock = (toast) => {
  toast.add({ severity: 'error', summary: 'Gagal', detail: 'Status tidak boleh tersedia jika stok adalah 0', life: 3000 });
};

export const showErrorOtp = (toast) => {
  toast.add({ severity: 'error', summary: 'Gagal', detail: 'Gagal memverifikasi email Anda. Mohon coba kembali!', life: 3000 });
};

export const showSessionExp = (toast) => {
  toast.add({ severity: 'error', summary: 'Gagal', detail: 'Status tidak boleh tersedia jika stok adalah 0', life: 3000 });
};

export const showSuccessCancelOrder = (toast) => {
  toast.add({ severity: 'success', summary: 'Berhasil', detail: 'Pesanan berhasil dibatalkan', life: 3000 });
};

export const showError = (toast, message) => {
  toast.add({ 
    severity: 'error', 
    summary: 'Gagal', 
    detail: message || 'Terjadi kesalahan. Silakan coba lagi!', 
    life: 3000 
  });
};

export const showAddCart = (toast) => {
  toast.add({ 
      severity: 'success', 
      summary: 'Berhasil', 
      detail: 'Barang berhasil ditambahkan ke keranjang!', 
      life: 3000 
  });
};
