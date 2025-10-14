import Trix from "trix";
import 'trix/dist/trix.css'
document.addEventListener("trix-before-initialize", () => {
  // Change Trix.config if you need
})
document.addEventListener("trix-file-accept", function (event) {
    event.preventDefault();
});