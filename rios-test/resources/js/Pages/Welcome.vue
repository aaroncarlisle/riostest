<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});
</script>

<template>
    <Head title="TODO - the app" />

     <div>
       <h2 class="title" >TODO - the app</h2>
       <div class="adddiv" >
         <input v-model="itemText" v-on:keyup.enter="addItem"/>
         <button v-on:click="addItem">+</button>
       </div>
       <ol>
         <li v-for="item in items">
           <ToDoItem :item="item" v-on:refresh="getItems" />
         </li>
       </ol>
    </div>
</template>

<script>
import axios from 'axios'
import ToDoItem from '@/components/ToDoItem.vue'

export default {
  components: {
    ToDoItem
  },
  data () {
    return {
      items: [],
      /*
      editItem: {
        itemText: ''
      }
      */
      itemText: ''
    }
  },
  mounted () {
    this.getItems()
  },
  methods: {
    getItems: function () {
      axios.get('/api/item').then(res=> {
        console.log(res)
        this.items = res.data
      })
    },
    addItem: function () {
      axios.post('/api/item', {
        item_text: this.itemText,
        complete: false
      }).then(res => {
        this.itemText = ''
        this.getItems()
      })
    }
  }
}
</script>

<style>
button {
  background-color: blue;
  color: white;
  font-size: large;
  width: 3em;
  border-width: 3px;
}
input {
  border-width: 3px;
}
.checkbox {
  padding: 10px;
  margin: 2px;
}
.title {
  margin-left: 50px;
  margin-bottom: 10px;
}
.adddiv {
  margin-bottom: 10px;
}
</style>
