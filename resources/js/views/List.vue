<template>
  <div>
    <h3>List</h3>
    <flash-message class="myCustomClass"></flash-message>
    <div class="table-responsive">
      <b-table
        id="my-table"
        striped
        hover
        :items="items"
        :fields="fields"
        :per-page="perPage"
        :current-page="currentPage"
      >
        <template v-slot:cell(picture)="row">
          <img
            v-b-modal="'modal-'+ row.item.id"
            class="img-picture"
            :src="'data:image/png;base64, ' +row.item.base64"
          />
          <b-modal :id="'modal-'+ row.item.id" :title="row.item.name" okOnly>
            <p class="my-4">
              <img class="img-modal" :src="'data:image/png;base64, ' +row.item.base64" />
            </p>
          </b-modal>
        </template>
      </b-table>
    </div>
    <div v-if="items.length > 0 ">
      <b-pagination
        v-model="currentPage"
        :total-rows="rows"
        :per-page="perPage"
        aria-controls="my-table"
        align="right"
      ></b-pagination>
    </div>
    <b-alert variant="warning" :show="items.length == 0 && search">No users registered!</b-alert>
  </div>
</template>
<script>
import api from "../services/api";
export default {
  name: "List",
  data() {
    return {
      fields: [
        {
          key: "id",
          label: "#",
        },
        {
          key: "name",
        },
        {
          key: "email",
        },
        {
          key: "country",
        },
        { key: "picture", label: "Picture" },
      ],
      items: [],
      perPage: 10,
      currentPage: 1,
      search: false,
    };
  },
  computed: {
    rows() {
      return this.items.length;
    },
  },
  mounted() {
    this.list();
  },
  methods: {
    list: function () {
      api.get("/list").then((response) => {
        let data = response.data;
        this.items = data;
        this.search = true;
      });
    },
  },
};
</script>
<style  scoped>
.img-picture {
  height: 27px;
  cursor: pointer;
}
.img-modal {
  width: 100%;
}
</style>