export const showSuccessAdd = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil menambahkan!', life: 2000 });
};

export const showSuccessEdit = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil mengubah!', life: 2000 });
};

export const showSuccessRemove = (toast) => {
  toast.add({ severity: 'success', summary: 'Sukses', detail: 'Berhasil menghapus!', life: 2000 });
};
