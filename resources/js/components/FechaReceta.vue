<template>
    <span >{{ formatearFecha }} </span>
</template>

<script>
    export default {
        props: ['fecha'],
        computed: {
            formatearFecha() {
                // 25 de abril del 2020
                return moment(this.fecha).locale('es').format('DD [de] MMMM [del] YYYY')
            }
        }
    }
</script>
