const btn0 = document.getElementById("Buy1")
const btn11 = document.getElementById("Buy2")
const btn3 = document.getElementById("Buy3")
const btn4 = document.getElementById("Buy4")
const btn5 = document.getElementById("Buy5")

const button3 = document.getElementById("button3");
const button4 = document.getElementById("button4");
const button0 = document.getElementById("button0");
const button01 = document.getElementById("button01");
const content1 = document.getElementById("content1");
const content2 = document.getElementById("content2");
const content3 = document.getElementById("content3");
const content4 = document.getElementById("content4");





const button1 = document.getElementById("Buy6");
const button2 = document.getElementById("Buy7");
const wallet = document.getElementById("content1");
const wallet1 = document.getElementById("content2");
const bankbuy = document.getElementById("content3");


const bankshow = document.getElementById("bankhead");
const bankshow1 = document.getElementById("bankhead1");




button1.addEventListener("click", function() {
  button1.classList.add("active")
  button2.classList.remove("active");
  bankshow.style.display = "block";
  bankshow1.style.display = "none";
  bankbuy.style.display = "none";

  wallet.style.display = "block";
  wallet1.style.display = "none";
  // content3.style.display = "block";
  content4.style.display = "none";

 
 
  
});

button2.addEventListener("click", function() {
  button2.classList.add("active");
  button1.classList.remove("active");
  bankshow.style.display = "none";
  bankshow1.style.display = "block";
  bankbuy.style.display = "block";
  wallet.style.display = "none";
  wallet1.style.display = "none";
  content4.style.display = "none";

 
});






button3.addEventListener("click", function() {
  button3.classList.add("active");
  button4.classList.remove("active2");
  content1.style.display = "block";
  content2.style.display = "none";
  content3.style.display = "none";
  content4.style.display = "none";
 
});

button4.addEventListener("click", function() {
  button4.classList.add("active2");
  button3.classList.remove("active");
  content2.style.display = "block";
  content1.style.display = "none";
  content4.style.display = "none";
  content3.style.display = "none";
  bankbuy.style.display ="none";
});
button0.addEventListener("click", function() {
  button0.classList.add("active1");
  button01.classList.remove("active2");
  wallet.style.display = "none";
  wallet1.style.display = "none";
  content2.style.display = "none";
  content1.style.display = "none";
  content4.style.display = "none";
  content3.style.display = "block";
});
button01.addEventListener("click", function() {
  button01.classList.add("active2");
  button0.classList.remove("active1");
  // bank1.style.display = "none";
  // bank2.style.display = "block";
  content2.style.display = "none";
  content1.style.display = "none";
  content4.style.display = "block";
  content3.style.display = "none";
  bankbuy.style.display = "none";
});


btn0.addEventListener("click", function() {
  btn0.classList.add("active");
  btn11.classList.remove("active");
  btn3.classList.remove("active");
  btn4.classList.remove("active");
  btn5.classList.remove("active");
  // content2.style.display = "block";
  // content1.style.display = "none";

});
btn11.addEventListener("click", function() {
  btn11.classList.add("active");
  btn0.classList.remove("active");
  btn3.classList.remove("active");
  btn4.classList.remove("active");
  btn5.classList.remove("active");
 
});
btn3.addEventListener("click", function() {
  btn3.classList.add("active");
  btn0.classList.remove("active");
  btn11.classList.remove("active");
  btn4.classList.remove("active");
  btn5.classList.remove("active");

});
btn4.addEventListener("click", function() {
  btn4.classList.add("active");
  btn11.classList.remove("active");
  btn0.classList.remove("active");
  btn3.classList.remove("active");
  btn5.classList.remove("active");
});
btn5.addEventListener("click", function() {
  btn5.classList.add("active");
  btn11.classList.remove("active");
  btn0.classList.remove("active");
  btn3.classList.remove("active");
  btn4.classList.remove("active");
});




button3.addEventListener("click", function() {
  button3.classList.add("active1");
  button4.classList.remove("active1");
  content1.style.display = "block";
  content2.style.display = "none";
});

button4.addEventListener("click", function() {
  button4.classList.add("active1");
  button3.classList.remove("active1");
  content2.style.display = "block";
  content1.style.display = "none";
});









// second pop 

// Display function for first inner
function displayPopupinner() {
  document.querySelector('.popup-container-inner').style.display = 'block';
}



// Assign event listener to each button
const buttonspop = document.querySelectorAll('.popup-confirm');
buttonspop.forEach((buttonpop) => {
  buttonpop.addEventListener('click', displayPopupinner);
});



// Display function for second inner
function displayPopupinner1() {
  document.querySelector('.popup-container-inner1').style.display = 'block';
}



// Assign event listener to each button
const buttonspop1 = document.querySelectorAll('.popup-confirm1');
buttonspop1.forEach((buttonpop1) => {
  buttonpop1.addEventListener('click', displayPopupinner1);
});

// Display function for second inner
function displayPopupinner4() {
  document.querySelector('.popup-container-inner4').style.display = 'block';
}



// Assign event listener to each button
const buttonspop4 = document.querySelectorAll('.popup-confirm4');
buttonspop4.forEach((buttonpop4) => {
  buttonpop4.addEventListener('click', displayPopupinner4);
});



// second pop


// FISRT POPUP

// Display function
function displayPopup4() {
  document.querySelector('.popup-container4').style.display = 'block';
}

// Close function
function closePopup4() {
  document.querySelector('.popup-container4').style.display = 'none';
}

// Assign event listener to each button
const buttons4 = document.querySelectorAll('.btn4');
buttons4.forEach((button4) => {
  button4.addEventListener('click', displayPopup4);
});

// Assign event listener to close button
const closeBtn4 = document.querySelector('.close-btn4');
closeBtn4.addEventListener('click', closePopup4);




function displayPopup5() {
  document.querySelector('.popup-container5').style.display = 'block';
}

// Close function
function closePopup5() {
  document.querySelector('.popup-container5').style.display = 'none';
}

// Assign event listener to each button
const buttons5 = document.querySelectorAll('.btn5');
buttons5.forEach((button5) => {
  button5.addEventListener('click', displayPopup5);
});

// Assign event listener to close button
const closeBtn5 = document.querySelector('.close-btn5');
closeBtn5.addEventListener('click', closePopup5);



// close 


// Display function
function displayPopup2() {
  document.querySelector('.popup-container2').style.display = 'block';
}

// Close function
function closePopup2() {
  document.querySelector('.popup-container2').style.display = 'none';
}

// Assign event listener to each button
const buttons2 = document.querySelectorAll('.btn2');
buttons2.forEach((button2) => {
  button2.addEventListener('click', displayPopup2);
});

// Assign event listener to close button
const closeBtn2 = document.querySelector('.close-btn2');
closeBtn2.addEventListener('click', closePopup2);





    // Display function
    function displayPopup() {
      document.querySelector('.popup-container').style.display = 'block';
    }
  
    // Close function
    function closePopup() {
      document.querySelector('.popup-container').style.display = 'none';
    }
  
    // Assign event listener to each button
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach((button) => {
      button.addEventListener('click', displayPopup);
    });
  
    // Assign event listener to close button
    const closeBtn = document.querySelector('.close-btn');
    closeBtn.addEventListener('click', closePopup);



// FIRST POPUP



// payment method

const paybankbtn = document.getElementById("bank-box-btn");
const paychipperbtn = document.getElementById("chipper-box-btn");
const paypibtn = document.getElementById("pi-box-btn");

const paybank = document.getElementById("bank-box")
const paychipper = document.getElementById("chipper-box");
const paypi = document.getElementById("pi-box");



paybankbtn.addEventListener("click", function(){
  paybankbtn.classList.add("active");
  paychipperbtn.classList.remove("active");
  paypibtn.classList.remove("active");
  paybank.style.display = "block";
  paychipper.style.display = "none";
  paypi.style.display = "none";
});
paychipperbtn.addEventListener("click", function(){
  paychipperbtn.classList.add("active");
  paybankbtn.classList.remove("active");
  paypibtn.classList.remove("active");
  paybank.style.display = "none";
  paychipper.style.display = "block";
  paypi.style.display = "none";
});
paypibtn.addEventListener("click", function(){
  paypibtn.classList.add("active");
  paychipperbtn.classList.remove("active");
  paybankbtn.classList.remove("active");
  paybank.style.display = "none";
  paychipper.style.display = "none";
  paypi.style.display = "block";
});


const paybankbtn1 = document.getElementById("bank-box-btn1");
const paychipperbtn1 = document.getElementById("chipper-box-btn1");
const paypibtn1 = document.getElementById("pi-box-btn1");
// const closingbtn = document.getElementsByClassName("close-btn5");

const paybank1 = document.getElementById("bank-box1")
const paychipper1 = document.getElementById("chipper-box1");
const paypi1 = document.getElementById("pi-box1");
// const closing = document.getElementsByClassName("popup-container4");



paybankbtn1.addEventListener("click", function(){
  paybankbtn1.classList.add("active");
  paychipperbtn1.classList.remove("active");
  paypibtn1.classList.remove("active");
  paybank1.style.display = "block";
  paychipper1.style.display = "none";
  paypi1.style.display = "none";
});
paychipperbtn1.addEventListener("click", function(){
  paychipperbtn1.classList.add("active");
  paybankbtn1.classList.remove("active");
  paypibtn1.classList.remove("active");
  paybank1.style.display = "none";
  paychipper1.style.display = "block";
  paypi1.style.display = "none";
});
paypibtn1.addEventListener("click", function(){
  paypibtn1.classList.add("active");
  paychipperbtn1.classList.remove("active");
  paybankbtn1.classList.remove("active");
  paybank1.style.display = "none";
  paychipper1.style.display = "none";
  paypi1.style.display = "block";
});














