const domStrings = {
  sendSellectOpt: document.querySelector("#send-select"),
  totalBalContainer: document.querySelector("#total"),
  walletBallance: document.querySelector(".wallet-bal"),
  availableCurrencyDisplay: document.querySelector("#available_curr"),
};

/**/
const balanceReq = new XMLHttpRequest();
balanceReq.onload = function () {
  const data = JSON.parse(this.responseText);
  console.log(data);
  const naira = data['Naira'],
  dollar = data['Dollar'],
  rand = data['Rand'],
  cedi = data['Cedi'],
  pound = data['Pound'];
  sessionStorage.setItem('Naira',naira);
  sessionStorage.setItem('Dollar',dollar);
  sessionStorage.setItem('Rand',rand);
  sessionStorage.setItem('Cedi',cedi);
  sessionStorage.setItem('Pound',pound);
  console.log(sessionStorage.getItem('Cedi'));
  for (const key in data) {
    const curr = {
      walletName: key,
      walletValue: data[key],
    }; 
    var html = `<div class="insights"><div class="stat"><div class="balance"><div class="left"><h3 id="total">${curr.walletName}<span class="material-icons-sharp">
      visibility</span></h3><h2><span">%logo</span><span id="amount">${curr.walletValue}</span></h2></div></div></div></div>`;
    //will leave this because of the currency logo.
    var newHtml = html.replace("%name", curr.walletName);
    newHtml = newHtml.replace("%value", curr.walletValue);

    var availableCurrOption = `<option value="${curr.walletName}" name="${curr.walletName}">${curr.walletName} Wallet</option>`;
    // var enterDom = availableCurrOption.replace('%WALLETNAME',curr.walletName);
    // enterDom = availableCurrOption.replace('%WALLETNAME',curr.walletName);
    // enterDom = availableCurrOption.replace('%WALLETNAME',curr.walletName);
    //This array will collect all the value exchanged and sum it all to get the total exchange rate in a specific currency.

    // const exchange = [];
    //Creating the exchange rate logic
    if (curr.walletName == "Naira") {
      newHtml = newHtml.replace("%logo", "NGN");
      //Get the current exchange rate oƒ dollar
      const dollar = 730;
      domStrings.totalBalContainer.innerHTML = exchangeRate(
        dollar,
        curr.walletValue
      );
    } else if (curr.walletName == "Dollar") {
      newHtml = newHtml.replace("%logo", "USD");
    } else if (curr.walletName == "Cedi") {
      newHtml = newHtml.replace("%logo", "GHC");
    } else if (curr.walletName == "Rand") {
      newHtml = newHtml.replace("%logo", "ZAR");
    }else if(curr.walletName == "Pound") newHtml = newHtml.replace("%logo","GBP");

    //Wallet to Wallet
    const walletAvailable = document.querySelector(".w p span");
    domStrings.sendSellectOpt.addEventListener("click", (e) => {
      switch (e.target.value) {
        case "Naira":
          walletAvailable.innerHTML = `Balance ₦${data[e.target.value]} `;
          break;
        case "Dollar":
          walletAvailable.innerHTML = `Balance $${data[e.target.value]} `;
          break;
        case "Cedi":
          walletAvailable.innerHTML = `Balance ₡${data[e.target.value]} `;
          break;
        case "Rand":
          walletAvailable.innerHTML = `Balance ₨${data[e.target.value]} `;
          break;
        case "Pound":
          walletAvailable.innerHTML = `Balance ₤${data[e.target.value]} `;
          break;
        default:
          walletAvailable.innerHTML = "";
          break;
      }
    });

    //adding the html stringś into the dom.
    domStrings.availableCurrencyDisplay.insertAdjacentHTML(
      "afterend",
      availableCurrOption
    );
    document.querySelector(".insights").insertAdjacentHTML("afterend", newHtml);
  }
};
balanceReq.open("GET", "./php/user-wallet.php"); //this can make us send both request and response on thesame page
balanceReq.send();

//Exchange rate function
const exchangeRate = function (baseCurr, exchangeCurr) {
  return parseFloat(exchangeCurr / baseCurr).toFixed(2);
};