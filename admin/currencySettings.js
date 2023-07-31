// selecting elements from the dom
const domStrings = {
  select: document.querySelector("#send-select"),
  valueToSend: document.querySelector("#sentVal"),
  confiemBtn: document.querySelector("#naira-confirm-send"),
  bankName: document.querySelector("#bank-name"),
  accntNumb: document.querySelector("#acc-num"),
  narration: document.querySelector("#trans-narr"),
};
// Currency Adding new currency;
//measure the currency by a base currency rate.

function currency() {
  class Currency {
    constructor(name, value, rate, logo) {
      this._currencyName = name;
      this._currencyLogo = logo;
      this._currencyValue = value;
      this._currencyRate = rate;
    }
  }

  //initializing the total amount.
  let total = 0;

  // where to save the lists of the currency.
  let currencyLists = [];

  return {
    //Method to set the values
    setCurrency(name, value, rate, logo) {
      let cur = new Currency(name, value, rate, logo);
      currencyLists.push(cur);
    },

    //method to get the values
    getCurrency() {
      currencyLists.map((currency) => {
        //creating an html to map to each currency and put into the dom.
        var html =
          '<div class="insights"><div class="stat"><div class="balance"><div class="left"><h3 id="total">%name<span class="material-icons-sharp">visibility</span></h3><h2><span">%logo</span><span id="amount">%value</span></h2></div></div></div></div>';
        var newHtml = html.replace("%logo", currency._currencyLogo);
        newHtml = newHtml.replace("%name", currency._currencyName);
        newHtml = newHtml.replace("%value", currency._currencyValue);
        //adding the html stringśinto the dom.
        document
          .querySelector(".insights")
          .insertAdjacentHTML("afterend", newHtml);
        //Summing up all the values values inside each currency to make one total.
        total += currency._currencyValue;
        document.querySelector("#total").innerHTML = Math.ceil(total);
      });
    },
    //method to add money to the account.
    addMoney(currName, currValue) {
      // if statement
    },

    //method to senďout money from the account.
    send(name, value) {
      currencyLists.forEach((cur) => {
        if (cur._currencyName == name && value <= cur._currencyValue) {
          // initial balance on the account
          console.log(cur._currencyValue);
          // the new value after the amount to be sent has been deducted
          let newValue = cur._currencyValue - value;
          //the amount deducted for sending
          let sent = cur._currencyValue - newValue;
          //setting the initial value back to the new value after the transaction;
          cur._currencyValue = newValue;

          //function to update the total;
          function updateTotal() {
            //removing the sent value from the total value and updating it;
            var update = (total -= sent);
            document.querySelector("#total").innerHTML = update;

            console.log("From total", update);
          }

          console.log(newValue, sent);
          //selected each currency container by id;
          let amountCont = document.querySelectorAll("#amount");
          console.log(amountCont);
          //selected individual currency by their container index number in the dom and updating the innerHtml as money is beig removeďor added to it
          switch (cur._currencyName) {
            case "Naira":
              updateTotal();
              amountCont[4].innerHTML = newValue;
              break;
            case "Dollar":
              updateTotal();
              amountCont[3].innerHTML = newValue;
              break;
            case "Pounds":
              updateTotal();
              amountCont[2].innerHTML = newValue;
              break;
            case "Euro":
              updateTotal();
              amountCont[1].innerHTML = newValue;
              break;
            case "Yuen":
              updateTotal();
              amountCont[0].innerHTML = newValue;
              break;
            default:
              break;
          }
        } else if (cur._currencyName == name && value > cur._currencyValue) {
          console.log("Insufficient Balance");
        }
      });
    },
  };
}

function safe() {
  //initializing a variale to save the returned value from the currency function.
  let money = currency();
  money.setCurrency("Naira", 10000, 15, "₦");
  money.setCurrency("Dollar", 250, 5, "$");
  money.setCurrency("Cedi", 673, 12, "C");
  money.setCurrency("Kes", 276.5, 15, "K");
  money.setCurrency("Yuen", 2700, 15, "£");
  /*
  Cedi ghana
  kes kenya
  mwk malawi
  rwf rwanda
  tzs tanzania
  ugx uganda
  xof togo
  zmw zambia
  zar sa
  xaf CAR
  xaf chad
  xaf gabon
  xaf congo
  xaf cameroon
  */

  //Select the value from the select options.
  document.querySelector("#send-to-bank").addEventListener("click", (e) => {
    //the values from the option box will be set as the name of the currency.
    domStrings.confiemBtn.addEventListener("click", (ef) => {
      ef.preventDefault();

      console.log(
        domStrings.valueToSend.value,
        domStrings.bankName.value,
        domStrings.accntNumb.value,
        domStrings.narration.value
      );

      //Pass the value from the input box into the send money function.
      money.send(e.target.value, parseFloat(domStrings.valueToSend.value));
      //set the values of the input boxes back to empty.
      domStrings.valueToSend.value = "";
      domStrings.bankName.value = "";
      domStrings.accntNumb.value = "";
      domStrings.narration.value = "";
    });
  });

  money.getCurrency();
}
//Calling the init function;
safe();