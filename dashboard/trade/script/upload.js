//connect to upload.php
document.querySelector('#uploadBtn').addEventListener('click',()=>{
    document.querySelector('input').click();
})

let orderRef = sessionStorage.getItem('buyOrderRef');
console.log(orderRef);
document.querySelector('#ref').innerHTML = orderRef;
// ////////////////////////////////////
async function getOrderDetails(url){
  const ref = new FormData();
  ref.append('order_refrence',orderRef)
  try {
      await fetch(url,{ method: "POST", body: ref })
    .then(response=>response.json())
    .then(data=>OrderData(data))
  } catch (error) {
    console.log(error);
  }
}
const url =  '../php/orderActions/buy_order_proof.php'
getOrderDetails(url);


let sellerId;
function OrderData(data) {
  console.log(data.order);
  let {seller_id,exchange_rate,order_unit,payment_method,wallet,wallet_to,date}= data.order;
  wallet_to_receive = wallet;
  wallet_to_remove_from = wallet;
  wallet_to_receive = wallet_to;
  sellerId = seller_id;
  unit = order_unit;
  rate = exchange_rate;
  let html = `
  <div class="payment-method-bank">
  <div class="name-details">
    <h4>Seller Id</h4>
    <p>${seller_id}</p>
  </div>
  <div class="name-details">
    <h4>Order unit:</h4>
    <p>${order_unit}</p>
  </div>
  <div class="name-details">
    <h4>Payment method:</h4>
    <p>${payment_method}</p>
  </div>
  <div class="name-details">
    <h4>Wallet from:</h4>
    <p>${wallet}</p>
  </div>
  <div class="name-details">
    <h4>Wallet to:</h4>
    <p>${wallet_to}</p>
  </div>
  <div class="name-details">
    <h4>Exchange Rate:</h4>
    <p>${exchange_rate} ${wallet} = 1 ${wallet_to}</p>
  </div>
  <div class="name-details">
    <h4>Expected Amount:</h4>
    <p>${order_unit/exchange_rate} ${wallet_to}</p>
  </div>
  <div class="name-details">
    <h4>DateTime</h4>
    <p>${date}</p>
  </div>
</div>`
document.querySelector('#orderData').insertAdjacentHTML('beforeend',html);
}

///////////////////////////////////////

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
        var maxWidth = 5000; // Set your desired maximum width
        var maxHeight = 5000; // Set your desired maximum height
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
        var compressedDataUrl = canvas.toDataURL("image/jpeg", 1); // Adjust the image quality as desired

        // Convert the data URL to a Blob object
        var compressedFile = dataURLtoFile(compressedDataUrl, `${sellerId}+${orderRef}.jpg`);

        // Create a new FormData object and append the compressed file
        var formData = new FormData();
        formData.append("image", compressedFile);
        formData.append('receiver',sellerId);
        formData.append('refrence',orderRef);

        // console.log(compressedFile);
        // Send the compressed file to the server
        fetch("../php/upload/upload.php", {
          method: "POST",
          body: formData,
        })
          .then((response) => response.json())
          .then((data) => {
            console.log(data);
            
            //Create an img element
            let img = document.createElement('img')
            //Set attribute of the image as the userId and transaction ref
            img.setAttribute('src',`../php/upload/proofImg/${data.img}`);
            document.querySelector('.image-container').appendChild(img);
            //Show succesfull message
              document.querySelector('#success').style.display = 'block';
              document.querySelector('#success').innerHTML = `${data.Success} 😊 receipt sent to ${data.receiver}`;
              document.querySelector('#modalbtn').click();
          })
          .catch((error) => {
            setTimeout(() => {
              document.querySelector('#failed').style.display = 'block';
            }, 2000);
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
  var n = bstr.length;
  var u8arr = new Uint8Array(n);
  while (n--) {
    u8arr[n] = bstr.charCodeAt(n);
  }
  return new File([u8arr], filename, { type: mime });
}


console.log('from upload');