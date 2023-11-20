<template>
  <div class="folder-upload">
    <div class="dropzone" @dragover.prevent @dragenter.prevent @drop="handleDrop">
      <p>Drag and drop a folder here to upload its contents</p>
    </div>

    <div v-if="uploadedFiles.length > 0">
      <h2>Uploaded Files:</h2>
      <ul>
        <li v-for="file in uploadedFiles" :key="file.name">{{ file.name }}</li>
      </ul>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      uploadedFiles: [],
    };
  },
  methods: {
    handleDrop(event) {
      event.preventDefault();
      const items = event.dataTransfer.items;

      for (let i = 0; i < items.length; i++) {
        const item = items[i].webkitGetAsEntry();

        if (item.isDirectory) {
          this.uploadFolder(item);
        } else {
          console.warn(`${item.name} is not a folder and will be skipped.`);
        }
      }
    },
    async uploadFolder(folder) {
      const reader = folder.createReader();
      const entries = await new Promise((resolve) =>
        reader.readEntries((entries) => resolve(entries))
      );

      for (let i = 0; i < entries.length; i++) {
        const entry = entries[i];
        if (entry.isFile) {
          await this.uploadFile(entry);
        } else if (entry.isDirectory) {
          await this.uploadFolder(entry);
        }
      }
    },
    async uploadFile(file) {
      // Simulate uploading the file (you can replace this with actual upload logic)
      await new Promise((resolve) => setTimeout(resolve, 1000));

      this.uploadedFiles.push(file);
      console.log(`Uploaded ${file.name}`);
    },
  },
};
</script>

<style scoped>
.folder-upload {
  text-align: center;
  padding: 20px;
}

.dropzone {
  border: 2px dashed #ccc;
  padding: 20px;
  cursor: pointer;
}

ul {
  list-style: none;
  padding: 0;
}

li {
  margin: 5px 0;
}
</style>
