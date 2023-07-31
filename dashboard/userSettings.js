//will have to remove these parameters and use the sender methods.
function createUserAccount() {
    let _userName = "";
    let _password = "";
    let _email ="";
    let _id ="";
    //
    return {
      setUsername(newUsername) {
        if (typeof newUsername === "string" && newUsername.length > 5) {
          _userName = newUsername;
          return true;
        } else {
          // console.log("Username length too short")
          return false;
        }
      },
      setPassword(newPassword) {
        if (typeof newPassword === "string" && newPassword.length > 8) {
          _password = encryptPass(newPassword);
          return true;
        } else {
          // console.log("Password length too short");
          return false;
        }
      },
      setEmailAddress(newEmail) {
        if (typeof newEmail === "string" && newEmail.includes("@")) {
          _email = newEmail;
          return true;
        } else if (newEmail.indexOf("@" === -1)) {
          return indexOf("@");
        }
      },
      setNewId(id) {
        if (typeof id === "string") {
          _id = id;
          return true;
        } else {
          return false;
        }
      },
      
      getUserName() {
        // show the username in an html element;
        return _userName;
      },
      getEmail() {
        // show the username in an html element;
        return _email;
      },
      getPass() {
        // show the username in an html element;
        return _password;
      },
    };
  }
  
  
  //password enctypt function
  const encryptPass = function (pass) {
    return pass.split("").reverse().join("");
  };
  
  
  
  const userAccount = createUserAccount();
  userAccount.setUsername("Tayo");
  userAccount.setEmailAddress("omojowo@gmail.com");
  userAccount.setPassword("passwordis2password");
  userAccount.setNewId("youTango");
  const Name= userAccount.getUserName();
  const email = userAccount.getPass();
  const pass = userAccount.getEmail();
  
  // console.log(pass,email,Name);
  