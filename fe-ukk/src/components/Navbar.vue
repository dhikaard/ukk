<template>
<div class="surface-overlay sticky top-0 px-3 lg:px-6 flex align-items-stretch relative border-bottom-1 surface-border" style="min-height: 80px">
    <div class="flex align-items-center justify-content-center">
        <img src="" alt="Image" height="40" class="hidden lg:inline mr-3 lg:mr-6">
        <img src="" alt="Image" height="40" class="inline lg:hidden mr-3 lg:mr-6">
    </div>
    <div class="flex align-items-center flex-auto">
        <IconField iconPosition="left" class="w-full">
    <InputIcon class="pi pi-search"> </InputIcon>
    <InputText v-model="value1" placeholder="Search" class="w-full"/>
</IconField>
    </div>
    <div class="flex ml-3 lg:ml-6">
        <ul class="list-none p-0 m-0 flex">
            <li class="inline-flex relative">
                <a v-ripple class="text-900 font-medium inline-flex align-items-center cursor-pointer px-1 lg:px-3 mr-2 lg:mr-0 border-bottom-2 border-transparent hover:border-primary select-none p-ripple"
                    v-styleclass="{ selector: '@next', enterClass: 'hidden', enterActiveClass: 'scalein', leaveToClass: 'hidden', leaveActiveClass: 'fadeout', hideOnOutsideClick: true}">
                    <i class="pi pi-user text-xl"></i>
                    <span class="hidden">My Account</span>
                </a>
                <div class="hidden border-round surface-overlay p-3 shadow-2 absolute right-0 top-100 z-1 w-15rem origin-top">
                    <ul class="list-none p-0 m-0">
                        <li>
                            <a class="cursor-pointer text-700 hover:text-900 hover:surface-100 border-round flex align-items-center px-3 py-2">
                                <i class="bi bi-receipt-cutoff text-lg mr-2"></i>
                                <span>History</span>
                            </a>
                        </li>
                        <li>
                            <a class="cursor-pointer text-700 hover:text-900 hover:surface-100 border-round flex align-items-center px-3 py-2">
                                <i class="pi pi-fw pi-heart text-lg mr-2"></i>
                                <span>Favorites</span>
                            </a>
                        </li>
                        <li>
                            <a class="cursor-pointer text-700 hover:text-900 hover:surface-100 border-round flex align-items-center px-3 py-2">
                                <i class="pi pi-fw pi-star text-lg mr-2"></i>
                                <span>Reviews</span>
                            </a>
                        </li>
                        <li>
                            <a class="cursor-pointer text-700 hover:text-900 hover:surface-100 border-round flex align-items-center px-3 py-2">
                                <i class="pi pi-fw pi-sign-out text-lg mr-2"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="inline-flex relative">
                <a v-ripple class="text-900 font-medium inline-flex align-items-center cursor-pointer px-1 lg:px-3 border-bottom-2 border-transparent hover:border-primary select-none p-ripple"
                    v-styleclass="{ selector: '@next', enterClass: 'hidden', enterActiveClass: 'scalein', leaveToClass: 'hidden', leaveActiveClass: 'fadeout', hideOnOutsideClick: true}">
                    <i class="pi pi-shopping-cart text-xl" v-badge></i>
                    <span class="hidden">My Cart</span>
                </a>
                <div class="hidden border-round surface-overlay p-4 shadow-2 absolute right-0 top-100 z-1 w-20rem origin-top">
                    <span class="text-900 font-medium mb-3 block">My Cart (1 Item)</span>
                    <div class="flex align-items-center border-bottom-1 surface-border pb-3">
                        <img src="" class="w-4rem flex-shrink-0 shadow-1" />
                        <div class="flex flex-column pl-3">
                            <span class="text-900 font-medium mb-2">Prime Watch</span>
                            <span class="text-600 mb-2">Standard Size</span>
                            <span class="text-primary font-bold">$50.00</span>
                        </div>
                    </div>
                    <div class="flex pt-3">
                        <Button class="p-button-outlined mr-2">View Cart</Button>
                        <Button class="ml-2">Purchase</Button>
                    </div>
                </div>
            </li>
        </ul>
    </div>
</div>

</template>

<script setup>

</script>
