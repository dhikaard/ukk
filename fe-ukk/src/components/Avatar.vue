<template>
    <img :src="avatarSvg" :alt="alt" />
  </template>
  
  <script setup>
  import { ref, onMounted } from 'vue';
  import { createAvatar } from '@dicebear/core';
  import { initials } from '@dicebear/collection';
  
  const props = defineProps({
    name: {
      type: String,
      required: true,
    },
    alt: {
      type: String,
      default: 'Avatar',
    },
  });

  const avatarSvg = ref('');

  onMounted(() => {
    const avatar = createAvatar(initials, {
      seed: props.name,
      radius: 50,
      backgroundColor: [
        "039be5",
        "1e88e5",
        "3949ab",
        "5e35b1",
        "8e24aa",
        "00acc1"
      ],
      backgroundType: [
        "gradientLinear"
      ],
      backgroundRotation: [
        360, -360, -350, -340, -330, -320, -310, -300, -290, -280,
        -270, -260, -250, -240, -230, -220, -210, -200, -190, -180,
        -170, -160, -150, -140, -130, -120, -110, -100, -90, -80,
        -70, -60, -50, -40, -30, -20, -10, 10, 20, 30, 40, 50,
        60, 70, 80, 90, 100, 110, 120, 130, 140, 150, 160, 170,
        180, 190, 200, 210, 220, 230, 240, 250, 260, 280, 270, 290,
        300, 310, 330, 320, 340, 350
      ],
      chars: 1
    });
  
    const svgString = avatar.toString();
  
    avatarSvg.value = `data:image/svg+xml;base64,${btoa(unescape(encodeURIComponent(svgString)))}`;
  });
  </script>