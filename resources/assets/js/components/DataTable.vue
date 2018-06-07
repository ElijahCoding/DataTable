<template>
  <div class="panel panel-default">
      <div class="panel-heading">Name of Table</div>

      <div class="panel-body">
        <table class="table->responsive">
          <thead>
            <tr>
              <th>Column</th>
            </tr>
          </thead>

          <tbody>
            <tr>
              <td>Value</td>
            </tr>
          </tbody>
        </table>
      </div>
  </div>
</template>

<script>
    export default {
        props: [
          'endpoint'
        ],

        data () {
          return {
            response: {
              displayable: [],
              records: []
            }
          }
        },

        mounted() {
          this.getRecords()
        },

        methods: {
          getRecords () {
            return axios.get(`${this.endpoint}`).then((response) => {
              this.response = response.data.data
            })
          }
        }
    }
</script>
