<template>
  <div>
      <h5>{{nombre.id}}</h5>
      <h5 v-text="nombre.per_paterno"></h5>
      <h5 v-text="nombre.per_ci"></h5>
  </div>
</template>

<script>
export default {
    data() {
        return {
            //RECIBIMOS LA VARIABLE EN ESTE CASO (n)
            nombre: this.$route.params.n
        }
    },
    mounted() {
        this.ver();
    },
    methods: {
        ver(){
            console.log(this.nombre);
        }
    },

}
</script>

<style>

</style>