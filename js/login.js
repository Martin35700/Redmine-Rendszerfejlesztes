
var entityMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#39;',
    '/': '&#x2F;',
    '`': '&#x60;',
    '=': '&#x3D;'
  };
  
function escapeHtml (string) {
    return String(string).replace(/[&<>"'`=\/]/g, function (s) {
      return entityMap[s];
    });
}

async function login()
{
    email=escapeHtml(document.getElementById("email").value);
    pwd=escapeHtml(document.getElementById("password").value);
    let salt="sajt";
    pwd=pwd+salt;
    pwd=CryptoJS.SHA256(pwd).toString();
    let adatKuldes={
        "email":email,
        "pwd":pwd
    };
    let valasz = await fetch("php/index.php/loginLeker", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adat = await valasz.json();
    if(adat.valasz!="Hiba!")
    {
        window.location.href=adat.valasz.toString();
    }
    else
    {
        alert("Login failed! Please try again!");
        document.getElementById("email").value="";
        document.getElementById("password").value="";
        email="";
        pwd="";
    }
}

async function dbCreate()
{
    try{
        let valasz=await fetch("php/index.php/dbCreate");
        let adatok=await valasz.json();
        console.log(adatok.valasz);
    }
    catch(e)
    {
        console.log(e);
    }
}

let btn=document.getElementById("login");

window.addEventListener("load",dbCreate);
btn.addEventListener("click",login);