<template>
    <Header></Header>
  <Main>
      <router-view></router-view>
    <h2 class="letra-xl text-center mt-4">Crear Nota</h2>
    <form class="form" @submit.prevent="crear_nota">
        <div class="campo">
            <label class="label" for="title">Titulo</label>
            <input class="input" id="title" type="text" v-model="title" placeholder="Titulo">
        </div>
        <div class="campo">
            <label class="label" for="description">Description</label>
            <textarea id="description" v-model="description" placeholder="Description"></textarea>
        </div>
        <div class="campo">
            <label class="label" for="tag">Tag/Categoria</label>
            <input id="tag" v-model="tag" placeholder="Tag"></input>
        </div>
        <p v-if="respuesta">{{ respuesta }}</p>
        <input class="submit" value="Crear" type="submit">
    </form>
  </Main>
  
</template>
<script>
import Main from '../Components/mainContainer.vue';
import Header from '../Components/header_dashboard.vue';

export default {
    name: "crearNota",
    components: {
        Header,
        Main,
    },
    data(){
        return{
            title : '',
            description: '',
            tag: '',
            respuesta: '',
        }
    },
    methods: {
        isActive(index){
            return this.activeIndex === index;
        },
        toggleInfo(index){
            this.activeIndex = this.activeIndex === index ? null : index;
        },
        async crear_nota() {
            try {
                const response = await axios.post('/api/notes', {
                    title: this.title,
                    description: this.description,
                    tag: this.tag,
                });

                this.$router.push('/');

            } catch (error) {
                console.error('Error fetching notes:', error);
                this.respuesta = error.response.data.message;
            }
        }
    }
}
</script>