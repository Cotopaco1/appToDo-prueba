import { createStore } from 'vuex';
import axios from 'axios';

export default createStore({
    state: {
    user: {
            name: '',
            email: ''
        },
        loading : false,
        notes : []
    },
    mutations: {
        setUser(state, user){
            state.user.name = user.name;
            state.user.email = user.email
        },
        setMessage(state, newMessage) {
            state.message = newMessage;
        },
        setLoading(state, bool){
            state.loading = bool;
        },
        setNotes(state, datos){
            state.notes = datos
        },
        updateNote(state, note){
            const index = state.notes.findIndex(nota => nota.id === note.id); 

            if (index !== -1) { // Verifica si la nota fue encontrada
                state.notes.splice(index, 1, note); // Reemplaza la nota existente en el estado con la nueva
            }

        },
        deleteNote(state,index){
            state.notes.splice(index, 1)
        }
    },
    actions : {
        async obtener_notas({ commit}){
            commit('setLoading', true);

            try {
                const response = await axios.get('/api/notes');
                console.log(response.data.notes)
                commit('setNotes', response.data.notes);

            } catch (error) {
                console.log(error);
            } finally{
                commit('setLoading', false)
            }
        }
    },
    getters: {
        // Getter para recuperar el array
        getItems: (state) => state.notes,
        getUser: (state)=> state.user,
        getItem: (state) => (index) =>{
            
            return state.notes[index];
        } 
      }
});