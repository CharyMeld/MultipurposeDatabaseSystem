export const Documents = {
  template: `
    <div>
      <h4 class="text-center p-3">Candidate Documents</h4>
      <div class="mb-3">
        <input type="file" @change="handleFileChange" class="form-control" />
        <button @click="uploadFile" class="btn btn-success mt-2">Upload</button>
      </div>

      <div v-if="files.length" class="table-responsive mt-3">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>File Name</th>
              <th>Preview</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="file in files" :key="file.id">
              <td>{{ file.name }}</td>
              <td>
                <a :href="file.url" target="_blank">View</a>
              </td>
              <td>
                <button @click="deleteFile(file.id)" class="btn btn-danger btn-sm">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-else class="alert alert-info mt-3">
        No documents found.
      </div>
    </div>
  `,
  props: ['candidate', 'doc_fields'],
  data() {
    return {
      selectedFile: null,
      files: [],
    };
  },
  computed: {
    candidateId() {
      return this.candidate?.id || this.candidate?.biodata?.id || null;
    }
  },
  methods: {
    handleFileChange(event) {
      this.selectedFile = event.target.files[0];
    },
    uploadFile() {
      if (!this.selectedFile) {
        alert("Please select a file to upload.");
        return;
      }

      const formData = new FormData();
      formData.append("document", this.selectedFile);

      fetch(`/wacs_system/public/?url=candidates/upload-document&id=${this.candidateId}`, {
        method: "POST",
        body: formData,
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            this.files.push(data.file); // Assuming backend returns file { id, name, url }
            this.selectedFile = null;
            alert("Upload successful.");
          } else {
            alert("Upload failed.");
          }
        })
        .catch(() => alert("An error occurred during upload."));
    },
    deleteFile(fileId) {
      if (!confirm("Are you sure you want to delete this file?")) return;

      fetch(`/wacs_system/public/?url=candidates/delete-document&id=${fileId}`, {
        method: "DELETE",
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            this.files = this.files.filter((file) => file.id !== fileId);
            alert("Deleted successfully.");
          } else {
            alert("Delete failed.");
          }
        })
        .catch(() => alert("An error occurred while deleting the file."));
    },
    fetchDocuments() {
      fetch(`/wacs_system/public/?url=candidates/get-documents&id=${this.candidateId}`)
        .then((res) => res.json())
        .then((data) => {
          if (Array.isArray(data)) {
            this.files = data;
          } else {
            this.files = [];
          }
        })
        .catch(() => {
          this.files = [];
        });
    },
  },
  mounted() {
    this.fetchDocuments();
  },
};

