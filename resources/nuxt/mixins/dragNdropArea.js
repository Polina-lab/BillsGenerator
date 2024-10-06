//  Wrap required section for drag-n-drop with following div
/*
  <div id="dragndrop_area" 
       @dragover="dragover($event)" 
       @dragleave="dragleave($event)" 
       @drop="drop($event)">

  </div>
*/

// Add a wrapping handler for triggering file upload function 
/**
 * Wraps local file upload functionality
 * @param {Object[]} files list of files to interact handle
 */
//this.fileUploadHandler(files)

export default {
    methods: {
        dragover(event) {
            event.preventDefault();
            // Add some visual fluff to show the user can drop its files
            if (!event.currentTarget.classList.contains("drag-over")) {
                event.currentTarget.classList.add("drag-over");
            }
        },
        dragleave(event) {
            // Clean up
            // event.currentTarget.classList.add("bg-lblue");
            event.currentTarget.classList.remove("drag-over");
        },
        drop(event) {
            event.preventDefault();
            this.fileUploadHandler(event.dataTransfer.files);
            // Clean up
            event.currentTarget.classList.remove("drag-over");
        },
    }
}