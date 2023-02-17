<template>
  <div>
    <span>
      <input v-model="item.item_text" v-on:change="updateItem"/>
    </span>
    <input class="checkbox" type="checkbox" v-model="item.complete" v-on:change="updateItem"/>
    <button v-on:click="moveItemUp">⇡</button>
    <button v-on:click="moveItemDown">⇣</button>
    <button v-on:click="deleteItem">-</button>
  </div>
</template>

<script>
import axios from 'axios'

export default {
  props: [
    'item'
  ],
  /* this might not be necessary */
  watch: {
    item: {
      handler: function () {
        /* save or delete here */
        /* on delete, emit onDelete so refresh can happen */
      },
      deep: true
    }
  },
  methods: {
    /* run this on text change or completed change */
    updateItem: function () {
      axios.put('/api/item/' + this.item.id, this.item);
    },
    moveItemUp: function () {
      axios.patch('/api/item/swap/' + this.item.prev + '/' + this.item.id);
      this.$emit('refresh');
    },
    moveItemDown: function () {
      axios.patch('/api/item/swap/' + this.item.id + '/' + this.item.next);
      this.$emit('refresh');
    },
    deleteItem: function() {
      axios.delete('/api/item/' + this.item.id);
      this.$emit('refresh');
    }
  }
}
</script>
