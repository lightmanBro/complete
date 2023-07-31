//connect to upload.php
document.querySelector('#uploadBtn').addEventListener('click',()=>{
    document.querySelector('input').click();
})

document.querySelector('input').addEventListener('change',(e)=>{
    var fileInput = document.getElementById("file-upload");
      var file = fileInput.files[0];
    console.log(fileInput.files[0])
    compressAndUpload();
})

function compressAndUpload() {
  var fileInput = document.getElementById("file-upload");
  var file = fileInput.files[0];

  if (file) {
    var reader = new FileReader();

    reader.onload = function (event) {
      var img = new Image();
      img.src = event.target.result;

      img.onload = function () {
        var canvas = document.createElement("canvas");
        var ctx = canvas.getContext("2d");

        // Set the canvas dimensions to the resized image dimensions
        var maxWidth = 1000; // Set your desired maximum width
        var maxHeight = 1000; // Set your desired maximum height
        var width = img.width;
        var height = img.height;

        if (width > height && width > maxWidth) {
          height *= maxWidth / width;
          width = maxWidth;
        } else if (height > maxHeight) {
          width *= maxHeight / height;
          height = maxHeight;
        }

        canvas.width = width;
        canvas.height = height;

        // Draw the image on the canvas
        ctx.drawImage(img, 0, 0, width, height);

        // Get the compressed data URL
        var compressedDataUrl = canvas.toDataURL("image/jpeg", 0.85); // Adjust the image quality as desired

        // Convert the data URL to a Blob object
        var compressedFile = dataURLtoFile(compressedDataUrl, file.name);

        // Create a new FormData object and append the compressed file
        var formData = new FormData();
        formData.append("image", compressedFile);
        console.log(compressedFile);
        // Send the compressed file to the server
        fetch("../php/upload/upload.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            let img = document.createElement('img')
            console.log(data.image);
            img.setAttribute('src',`../php/upload/proofImg/${data.image}`);
            document.querySelector('.image-container').appendChild(img);
          })
          .catch((error) => {
            console.error("Error:", error);
          });
      };
    };

    reader.readAsDataURL(file);
  } else {
    console.log("No file selected.");
  }
}

// Function to convert data URL to Blob
function dataURLtoFile(dataURL, filename) {
  var arr = dataURL.split(",");
  var mime = arr[0].match(/:(.*?);/)[1];
  var bstr = atob(arr[1]);
  console.log(bstr);
  var n = bstr.length;
  var u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new File([u8arr], filename, { type: mime });
}


console.log('from upload');