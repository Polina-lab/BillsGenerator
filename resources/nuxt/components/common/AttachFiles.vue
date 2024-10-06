<template>
  <section class="card border-success mt-3">
    <div class="card-body">
      <h2 class="mt-0">Attach Files
        <span class="text-13" v-if="$route.name === 'estate-id'">
          <a href="#" @click.prevent="attachBlockToggle = !attachBlockToggle">
            <span v-if="attachBlockToggle">Hide</span>
            <span v-else>Show</span>
          </a></span>
      </h2>
      <div v-if="attachBlockToggle">
      <button class="btn btn-success"
              @click.prevent="$refs.uploadForm.click">Upload
      </button>
      <input ref="uploadForm"
             type="file"
             multiple
             hidden
             accept=".jpg, .jpeg, .png, .doc, .docx, .xls, .xlsx, .pdf"
             @change="uploadFiles">
      <div class="table-responsive mt-3" v-if="filesList.length">
        <table class="table table-hover">
          <thead>
          <tr v-for="(fileName, idx) in filesList" :key="idx">
            <td>{{fileName}}</td>
            <td><input type="text" class="form-control" placeholder="Comment"></td>
            <td><i class="fas fa-times text-danger float-right" title="Delete"
                   @click="deleteFileFromList(idx)"></i></td>
          </tr>
          </thead>
        </table>
      </div>
    </div>
    </div>
  </section>
</template>

<script>
  export default {
    name: "AttachFiles",
    data() {
      return {
        filesList: [],
        attachBlockToggle: false
      }
    },
    methods: {
      uploadFiles(e) {
        const files = Array.from(e.target.files)
        files.forEach(file => this.filesList.push(file.name))
      },
      deleteFileFromList(idx) {
        this.filesList.splice(idx, 1)
      }
    }
  }
</script>

<style scoped>
  .fa-times {
    cursor: pointer;
  }

</style>
