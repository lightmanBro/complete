//MODAL BOXES Design.
const body = document.querySelector(".container"),
 selectOpt = document.querySelector("#select"),
 sendSellectOpt = document.querySelector("#send-select"),
 accountDisplay = document.querySelector(".banks"),
 payGateways = document.querySelector(".deposits"),
 bankButton = document.querySelector(".form-btn"),
 buttonHide = document.querySelector("#buttons"),
 addBtn = document.querySelector("#add-btn"),
 sendBtn = document.querySelector("#send-btn"),
 sendMoneyModal = document.querySelector("#send-money-modal"),
 bankTransferBtn = document.querySelector(".bank-transfer"),
 sendModalClose = document.querySelector(".send-close-btn"),
 closeBtn = document.querySelector(".close-btn"),
 modalBox = document.querySelector("#add-money-modal"),
 pairToPairbtn = document.querySelector("#pair-to-pair"),
 CurNameFundWallet = document.querySelector(".fund-wallet h2 span"),
 walletToBank = document.querySelector("#bank-deposit"),
 dollarModal = document.querySelector(".dollar-container"),
 walletToBankBtn = document.querySelector(".wallet-to-bank"),
 walletTowalletBtn = document.querySelector(".wallet-to-wallet"),
 walletTowallet = document.querySelector("#wallet"),
 popup = document.querySelector(".popup-container");
 

//initial state of the button
walletToBank.classList.add("none");
walletTowallet.classList.add("none");

//wallet to wallet modal
walletTowalletBtn.addEventListener("click", (e) => {
  console.log(e.target);
  walletTowallet.classList.toggle("none");
  walletToBank.setAttribute("class", "none");
  walletToBankBtn.classList.toggle("none");
});

//wallet to bank
walletToBankBtn.addEventListener("click", () => {
  walletTowallet.classList.add("none");
  walletToBank.classList.toggle("none");
  walletTowalletBtn.classList.toggle("none");
});

//By default the modal boxes should not be displayed should
sendMoneyModal.classList.add("none");
modalBox.setAttribute("class", "none");

//opening the modal box for adding money
addBtn.addEventListener("click", (e) => {
  e.preventDefault();
  modalBox.classList.toggle("add-money-modal");
  // console.log(modalBox.classList);
  sendMoneyModal.setAttribute("class", "none");
  popup.setAttribute("class", "block");
});

//closing the modal for adding money
closeBtn.addEventListener("click", (e) => {
  // console.log(e.target.classList)
  modalBox.setAttribute("class", "none");
});

//closing the send money modal box
sendModalClose.addEventListener("click", () => {
  sendMoneyModal.setAttribute("class", "none");
});
sendBtn.addEventListener("click", (e) => {
  e.preventDefault();
  sendMoneyModal.classList.toggle("send-money-modal");
  modalBox.setAttribute("class", "none");
});

pairToPairbtn.addEventListener("click", (e) => {
  
});

bankTransferBtn.addEventListener("click", () => {
  accountDisplay.classList.remove("none");
  bankTransferBtn.classList.add("none");
});

/*_____________________________________________*/
//The select option for the send modal box.
selectOpt.addEventListener("click", (e) => {
  // console.log(e.target.value)

  switch (e.target.value) {
    case "Naira":
      accountDisplay.classList.remove("none");
      payGateways.classList.add("none");
      CurNameFundWallet.innerHTML = e.target.value;
      bankTransferBtn.classList.add("none");

      //the rest of the logic the value should perform will be written here.
      break;
    case "Pounds":
      payGateways.classList.remove("none");
      accountDisplay.classList.add("none");
      CurNameFundWallet.innerHTML = e.target.value;
      buttonHide.setAttribute("class", "none");
      break;
    case "Dollar":
      payGateways.classList.remove("none");
      accountDisplay.classList.add("none");
      buttonHide.setAttribute("class", "none");
      CurNameFundWallet.innerHTML = e.target.value;
      break;
    case "Yuen":
      payGateways.classList.remove("none");
      accountDisplay.classList.add("none");
      buttonHide.setAttribute("class", "none");
      CurNameFundWallet.innerHTML = e.target.value;
      break;
    case "select-wallet":
      payGateways.classList.add("none");
      accountDisplay.classList.add("none");
      buttonHide.setAttribute("class", "buttons");
      dollarModal.classList.add("none");
      break;
  }
});

// the select option for the fund wallet.
const walletAvailable = document.querySelector(".fund-wallet p span");
sendSellectOpt.addEventListener("click", (e) => {
  switch (e.target.value) {
    case "Naira":
      walletAvailable.innerHTML = e.target.value;
      break;
    case "Pounds":
      walletAvailable.innerHTML = e.target.value;
      break;
    case "Dollar":
      walletAvailable.innerHTML = e.target.value;
      break;
    case "Euro":
      walletAvailable.innerHTML = e.target.value;
      break;
    case "Yuen":
      walletAvailable.innerHTML = e.target.value;
      break;
    default:
      walletAvailable.innerHTML = "";
      break;
  }
});

// sending money to a bank account
document.querySelector("#forms-to-bank").setAttribute("class", "none");
document.querySelector("#dollar-modal").setAttribute("class", "none");
document.querySelector("#send-to-bank").addEventListener("click", (e) => {
  // console.log(e.target.value)

  switch (e.target.value) {
    case "Naira":
      console.log("selected from send to bank");
      document.querySelector("#forms-to-bank").removeAttribute("class", "none");
      document.querySelector("#api-box").setAttribute("class", "none");
      document.querySelector("#dollar-modal").classList.add("none");
      //the rest of the logic the value should perform will be written here.
      break;
    case "Cedi":
      console.log("selected from send to cedi");
      document.querySelector("#forms-to-bank").setAttribute("class", "none");
      document.querySelector("#api-box").removeAttribute("class", "none");
      break;
    case "Dollar":
      document.querySelector("#forms-to-bank").setAttribute("class", "none");
      // document.querySelector("#api-box").removeAttribute("class","none");
      document.querySelector("#dollar-modal").classList.remove("none");
      break;
    case "Yuen":
      document.querySelector("#forms-to-bank").setAttribute("class", "none");
      document.querySelector("#api-box").removeAttribute("class", "none");
      break;
    case "select-wallet":
      document.querySelector("#forms-to-bank").setAttribute("class", "none");
      document.querySelector("#api-box").setAttribute("class", "none");
      document.querySelector("#dollar-modal").classList.add("none");
      console.log(e.target.classList);
      console.log("selected from send to select wallet");
      break;
  }
});

const menuBtn = document.getElementById("menu-btn");
const closemenubtn = document.getElementById("closemenu");
const sideMenu = document.querySelector('aside');
const container = document.querySelector('#big-container');
const logo = document.querySelector('#logo');

//Hide the logo by default
logo.classList.add('none')


closemenubtn.addEventListener("click", ()=> {
  container.classList.add('block');
  sideMenu.style.display = 'none';
  menuBtn.style.display='block';
  logo.classList.remove('none');
  console.log('clicked')
});

menuBtn.addEventListener('click',()=>{
  container.classList.add('container');
  container.classList.remove('block');
  sideMenu.style.display = 'block';
  menuBtn.style.display='none'
  logo.classList.add('none')

})