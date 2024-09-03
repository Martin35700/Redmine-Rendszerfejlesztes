
async function getUsers()
{
    let valasz=await fetch("php/index.php/managerLeker");
    let adatok=await valasz.json();
    login(adatok);
}

function login(adatok)
{
    let user=document.getElementById("email").value;
    let pwd=document.getElementById("password").value;
    let volt=false;
    for(adat of adatok)
    {
        if(adat.email==user && adat.password==pwd)
        {
            sessionStorage.setItem("userID",adat.id);
            volt=true;
            window.location="projects.html";
        }
    }
    if(!volt)
    {
        alert("Login failed! Please try again!");
        document.getElementById("email").value="";
        document.getElementById("password").value="";
        user="";
        pwd="";
    }
}

let btn=document.getElementById("login");

btn.addEventListener("click",getUsers);