<template>
  <div class="grid formgrid p-fluid flex-column w-full">
    <div class="surface-100 mb-3 col-12" style="height:2px"></div>
    <div v-if="!isEdit" class="field col-12 md:col-6 w-full h-3rem flex align-items-center">
      <label for="resolved" class="font-medium mr-5 mb-0">{{ $tl('resolved') }}</label>
      <JleafSwitch v-model="flgResolved" />
    </div>
    <div v-if="!isEdit" class="field mb-4 col-12 md:col-6 w-full">
      <label for="resolvedRemark" class="font-medium">{{ $tl('resolvedRemark') }}</label>
      <Textarea v-model="resolvedRemark" id="resolvedRemark" autoResize rows="5" cols="30" />
    </div>
    <div class="surface-100 mb-3 col-12" style="height:2px"></div>
    <JleafMessage />
    <div class="col-12 text-right">
      <Button :label="$tl('cancel')" icon="pi pi-times" class=" w-auto p-button-text mr-4"
        @click="closeDialog"></Button>
      <Button :label="$tl('save')" class="w-auto mt-3" icon="pi pi-check" :loading="loading['edit']"
        @click="editResolved"></Button>
    </div>
  </div>
</template>

<script setup>
import { inject, onMounted } from 'vue';

const context = useEditErrorMigrationStore();
const dialogRef = inject('dialogRef');
dialogRef = inject('dialogRef');

onMounted(() => {
  if (dialogRef.value && dialogRef.value.data && dialogRef.value.data.returnData) {
    const { flgResolved, resolvedRemark } = dialogRef.value.data.returnData;
    flgResolved = flgResolved;
    resolvedRemark = resolvedRemark;
  }
});

const editResolved = async () => {
  if (dialogRef.value && dialogRef.value.data && dialogRef.value.data.returnData) {
    const { id, version } = dialogRef.value.data.returnData;
    await editData(id, version, flgResolved, resolvedRemark)
  }
};

const closeDialog = () => {
    dialogRef.value.close();
}
</script>
