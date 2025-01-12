<template>
    <div class="surface-section px-4 py-8 md:px-6 lg:px-8">
    <div class="grid mb-7">
        <div class="col-12 lg:col-6">
            <div class="flex">
                <div class="flex flex-column w-2 justify-content-between">
                    <img v-for="(image, i) of images2" :key="image" :src="'images/blocks/ecommerce/productoverview/' + image" class="w-full cursor-pointer border-2 border-round border-transparent transition-colors transition-duration-150"
                        :class="{'border-primary': selectedImageIndex2 === i}" @click="selectedImageIndex2 = i"/>
                </div>
                <div class="pl-3 w-10">
                    <img :src="'images/blocks/ecommerce/productoverview/' + images2[selectedImageIndex2]" class="w-full" />
                </div>
            </div>
        </div>
        <div class="col-12 lg:col-6 py-3 lg:pl-6">
            <div class="flex align-items-center text-xl font-medium text-900 mb-4">Product Title Placeholder</div>
            <div class="flex align-items-center justify-content-between mb-5">
                <span class="text-900 font-medium text-3xl block">$120</span>
                <div class="flex align-items-center">
                    <span class="mr-3">
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star text-700 mr-1"></i>
                    </span>
                    <span class="text-sm"><b class="text-900 mr-1">24</b> <span class="text-500"></span>reviews</span>
                </div>
            </div>

            <div class="font-bold text-900 mb-3">Color</div>
            <div class="flex align-items-center mb-5">
                <div class="w-2rem h-2rem flex-shrink-0 border-circle bg-bluegray-500 mr-3 cursor-pointer border-2 surface-border transition-all transition-duration-300"
                    :style="{'box-shadow': color3 === 'bluegray' ? '0 0 0 0.2rem var(--bluegray-500)' : null}" @click="color3 = 'bluegray'"></div>
                <div class="w-2rem h-2rem flex-shrink-0 border-circle bg-green-500 mr-3 cursor-pointer border-2 surface-border transition-all transition-duration-300"
                    :style="{'box-shadow': color3 === 'green' ? '0 0 0 0.2rem var(--green-500)' : null}" @click="color3 = 'green'"></div>
                <div class="w-2rem h-2rem flex-shrink-0 border-circle bg-blue-500 cursor-pointer border-2 surface-border transition-all transition-duration-300"
                    :style="{'box-shadow': color3 === 'blue' ? '0 0 0 0.2rem var(--blue-500)' : null}" @click="color3 = 'blue'"></div>
            </div>

            <div class="mb-3 flex align-items-center justify-content-between">
                <span class="font-bold text-900">Size</span>
                <a tabindex="0" class="cursor-pointer text-600 text-sm flex align-items-center">Size Guide <i class="ml-1 pi pi-angle-right"></i></a>
            </div>
            <div class="grid grid-nogutter align-items-center mb-5">
                <div class="col h-3rem border-1 border-300 text-900 inline-flex justify-content-center align-items-center flex-shrink-0 border-round mr-3 cursor-pointer hover:surface-100 transition-duration-150 transition-colors"
                    :class="{'border-blue-500 border-2 text-blue-500': size3 === 'XS'}" @click="size3 = 'XS'">XS</div>
                <div class="col h-3rem border-1 border-300 text-900 inline-flex justify-content-center align-items-center flex-shrink-0 border-round mr-3 cursor-pointer hover:surface-100 transition-duration-150 transition-colors"
                    :class="{'border-blue-500 border-2 text-blue-500': size3 === 'S'}" @click="size3 = 'S'">S</div>
                <div class="col h-3rem border-1 border-300 text-900 inline-flex justify-content-center align-items-center flex-shrink-0 border-round mr-3 cursor-pointer hover:surface-100 transition-duration-150 transition-colors"
                    :class="{'border-blue-500 border-2 text-blue-500': size3 === 'M'}" @click="size3 = 'M'">M</div>
                <div class="col h-3rem border-1 border-300 text-900 inline-flex justify-content-center align-items-center flex-shrink-0 border-round mr-3 cursor-pointer hover:surface-100 transition-duration-150 transition-colors"
                    :class="{'border-blue-500 border-2 text-blue-500': size3 === 'L'}"  @click="size3 = 'L'">L</div>
                <div class="col h-3rem border-1 border-300 text-900 inline-flex justify-content-center align-items-center flex-shrink-0 border-round cursor-pointer hover:surface-100 transition-duration-150 transition-colors"
                    :class="{'border-blue-500 border-2 text-blue-500': size3 === 'XL'}" @click="size3 = 'XL'">XL</div>
            </div>

            <div class="font-bold text-900 mb-3">Quantity</div>
            <div class="flex flex-column sm:flex-row sm:align-items-center sm:justify-content-between">
                <InputNumber :showButtons="true" buttonLayout="horizontal" spinnerMode="horizontal" :min="0" inputClass="w-3rem text-center" v-model="quantity1"
                    decrementButtonClass="p-button-text" incrementButtonClass="p-button-text" incrementButtonIcon="pi pi-plus" decrementButtonIcon="pi pi-minus"></InputNumber>
                <div class="flex align-items-center flex-1 mt-3 sm:mt-0 ml-0 sm:ml-5">
                    <Button icon="pi pi-shopping-cart" label="Sewa Sekarang" class="flex-1 mr-5"></Button>
                    <i class="pi pi-question-circle text-2xl cursor-pointer"></i>
                </div>
            </div>
        </div>
    </div>

    <TabView>
        <TabPanel header="Details">
            <div class="text-900 font-medium text-3xl mb-4 mt-2">Product Details</div>
            <p class="line-height-3 text-700 p-0 mx-0 mt-0 mb-4">Volutpat maecenas volutpat blandit aliquam etiam erat velit scelerisque in. Duis ultricies lacus sed turpis tincidunt id. Sed tempus urna et pharetra.
                Metus vulputate eu scelerisque felis imperdiet proin fermentum. Venenatis urna cursus eget nunc scelerisque viverra mauris in.
                Viverra justo nec ultrices dui sapien eget mi proin. Laoreet suspendisse interdum consectetur libero id faucibus.</p>

            <div class="grid">
                <div class="col-12 lg:col-4">
                    <span class="text-900 block font-medium mb-3">Highlights</span>
                    <ul class="py-0 pl-3 m-0 text-700 mb-3">
                        <li class="mb-2">Vulputate sapien nec.</li>
                        <li class="mb-2">Purus gravida quis blandit.</li>
                        <li class="mb-2">Nisi quis eleifend quam adipiscing.</li>
                        <li>Imperdiet proin fermentum.</li>
                    </ul>
                </div>
                <div class="col-12 lg:col-4">
                    <span class="text-900 block font-medium mb-3">Size and Fit</span>
                    <ul class="list-none p-0 m-0 text-700 mb-4">
                        <li class="mb-3"><span class="font-medium">Leo vel:</span> Egestas congue.</li>
                        <li class="mb-3"><span class="font-medium">Sociis natoque:</span> Parturient montes nascetur.</li>
                        <li><span class="font-medium">Suspendisse in:</span> Purus sit amet volutpat.</li>
                    </ul>
                </div>
                <div class="col-12 lg:col-4">
                    <span class="text-900 block font-medium mb-3">Material & Care</span>
                    <ul class="p-0 m-0 text-700 flex flex-wrap flex-column xl:flex-row">
                        <li class="flex align-items-center white-space-nowrap w-10rem block mr-2 mb-3">
                            <i class="pi pi-sun mr-2"></i>
                            <span>Not dryer safe</span>
                        </li>
                        <li class="flex align-items-center white-space-nowrap w-10rem block mb-3">
                            <i class="pi pi-times-circle mr-2"></i>
                            <span>No chemical wash</span>
                        </li>
                        <li class="flex align-items-center white-space-nowrap w-10rem block mb-3 mr-2">
                            <i class="pi pi-sliders-h mr-2"></i>
                            <span>Iron medium heat</span>
                        </li>
                        <li class="flex align-items-center white-space-nowrap w-10rem block mb-3">
                            <i class="pi pi-minus-circle mr-2"></i>
                            <span>Dry flat</span>
                        </li>
                    </ul>
                </div>
            </div>
        </TabPanel>
        <TabPanel header="Reviews">
            <div class="text-900 font-medium text-3xl mb-4 mt-2">Customer Reviews</div>
            <ul class="list-none p-0 m-0">
                <li class="pb-5 border-bottom-1 surface-border">
                    <span>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-gray-500"></i>
                    </span>
                    <div class="text-900 font-medium text-xl my-3">Absolute Perfection!</div>
                    <p class="mx-0 mt-0 mb-3 text-700 line-height-3">Blandit libero volutpat sed cras ornare arcu dui vivamus. Arcu dictum varius duis at consectetur lorem donec massa. Imperdiet proin fermentum leo vel orci porta non. Porttitor rhoncus dolor purus non.</p>
                    <span class="text-600 font-medium">Darlene Robertson, 2 days ago</span>
                </li>
                <li class="py-5 border-bottom-1 surface-border">
                    <span>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500 mr-1"></i>
                        <i class="pi pi-star-fill text-yellow-500"></i>
                    </span>
                    <div class="text-900 font-medium text-xl my-3">Classy</div>
                    <p class="mx-0 mt-0 mb-3 text-700 line-height-3">Venenatis cras sed felis eget. Proin nibh nisl condimentum id venenatis a condimentum.</p>
                    <span class="text-600 font-medium">Kristin Watson, 2 days ago</span>
                </li>
            </ul>
        </TabPanel>
        <TabPanel header="Shipping">
            <div class="text-900 font-medium text-3xl mb-4 mt-2">Shipping Placeholder</div>
            <p class="line-height-3 text-700 p-0 mx-0 mt-0 mb-4">Mattis aliquam faucibus purus in massa tempor nec feugiat nisl. Justo donec enim diam vulputate ut pharetra. Tempus egestas sed sed risus.
                Feugiat sed lectus vestibulum mattis. Tristique nulla aliquet enim tortor at auctor urna nunc. Habitant morbi tristique senectus et. Facilisi nullam vehicula ipsum.</p>

            <div class="grid">
                <div class="col-12 md:col-6">
                    <span class="text-900 block font-medium mb-3">Shipping Costs</span>
                    <ul class="py-0 pl-3 m-0 text-700 mb-3">
                        <li class="mb-2">Japan - JPY 2,500.</li>
                        <li class="mb-2">Europe – EUR 10</li>
                        <li class="mb-2">Switzerland – CHF 10</li>
                        <li class="mb-2">Canada – CAD 25</li>
                        <li class="mb-2">USA – USD 20</li>
                        <li class="mb-2">Australia – AUD 30</li>
                        <li class="mb-2">United Kingdom – GBP 10</li>
                    </ul>
                </div>
                <div class="col-12 md:col-6">
                    <span class="text-900 block font-medium mb-3">Return Policy</span>
                    <p class="line-height-3 text-700 p-0 m-0">Pharetra et ultrices neque ornare aenean euismod elementum nisi. Diam phasellus vestibulum lorem sed. Mattis molestie a iaculis at. </p>
                </div>
            </div>
        </TabPanel>
    </TabView>
</div>
</template>
<script setup>
import { ref } from 'vue';

const images2 = ref(['image1.jpg', 'image2.jpg', 'image3.jpg']);
const selectedImageIndex2 = ref(0);
const quantity1 = ref(1);
const color3 = ref('');
const size3 = ref('');
const liked1 = ref(false);
</script>
