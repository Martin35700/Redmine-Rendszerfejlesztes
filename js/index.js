let selectType=document.getElementById("projectTypeSelect");
let selectProject=document.getElementById("projectSelect");
let taskview=document.getElementById("taskView");
let buttonview=document.getElementById("buttonView");

let saveTaskButton=document.getElementById("taskSave");
let logoutButton=document.getElementById("logOut");
let saveDevButton=document.getElementById("devSave");
let devs=document.getElementById("developers");

let futott=false;

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

function parseJwt(token)
{
    try
    {
        var base64Url = token.split('.')[1];
        var base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        var jsonPayload = decodeURIComponent(window.atob(base64).split('').map(function (c)
        {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
    
        return JSON.parse(jsonPayload);
    }
    catch(e){
        alert("Unauthorized!");
        this.document.cookie = "jwt=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        window.location.href="login.php";
        return null;
    }
}

const getCookieValue = (name) => (
    document.cookie.match('(^|;)\\s*' + name + '\\s*=\\s*([^;]+)')?.pop() || ''
)

function authCheck()
{
    let token=getCookieValue("jwt");
    let user=parseJwt(token);
    if(user["data"]["role"]!="Manager")
    {
        alert("You are not a manager!");
        window.location.href="login.php";
    }
    return user;
}

async function projektLeker()
{
    try{
        authCheck();
        if(selectType.value=="-1")
        {
            let valasz=await fetch("php/index.php/projektOsszesLeker");
            let adatok=await valasz.json();
            projektKiir(adatok);
        }
        else
        {
            let adatKuldes = {
                "projektTipus": selectType.value,
            };
            let valasz = await fetch("php/index.php/projektLeker", {
                method: 'POST',
                body: JSON.stringify(adatKuldes)
            });
            let adatok = await valasz.json();
            projektKiir(adatok);
        }
    }
    catch(e)
    {
        console.log(e);
    }
}

function projektKiir(adatok)
{
    taskview.innerHTML="";
    selectProject.innerHTML="";
    buttonview.innerHTML="";
    let defaultoption=document.createElement("option");
    defaultoption.value="-1";
    selectProject.appendChild(defaultoption);

    for(adat of adatok)
    {
        let option=document.createElement("option");
        option.value=adat.id;
        option.innerHTML=adat.name;
        selectProject.appendChild(option);
    }
    console.log("Projektek kiírva!");
}

function gombCreate()
{
    let addTask=document.createElement("button");
    addTask.setAttribute("type","button");
    addTask.setAttribute("class","btn btn-primary my-3 mx-3");
    addTask.setAttribute("data-toggle","modal");
    addTask.setAttribute("data-target","#taskModal");
    addTask.innerHTML="Add task";

    let addDev=document.createElement("button");
    addDev.setAttribute("type","button");
    addDev.setAttribute("class","btn btn-primary my-3 mx-3");
    addDev.setAttribute("data-toggle","modal");
    addDev.setAttribute("data-target","#devModal");
    addDev.innerHTML="Add developer to the project";
    addDev.addEventListener("click",devLeker);

    if(selectProject.value!="-1")
    {
        buttonview.appendChild(addTask);
        buttonview.appendChild(addDev);
    }
}

async function projektTipusLeker()
{
    try{
        authCheck();
        let valasz=await fetch("php/index.php/projektTipusLeker");
        let adatok=await valasz.json();
        projektTipusKiir(adatok);
    }
    catch(e)
    {
        console.log(e);
    }
}

function projektTipusKiir(adatok)
{
    for(adat of adatok)
    {
        let option=document.createElement("option");
        option.value=adat.id;
        option.innerHTML=adat.name;
        selectType.appendChild(option);
    }
    console.log("Projekt típusok kiírva!");
}

async function taskLeker()
{
    try{
        authCheck();
        buttonview.innerHTML="";
        let adatKuldes = {
            "projektSzam": selectProject.value,
        };
        let valasz = await fetch("php/index.php/projektTaskLeker", {
            method: 'POST',
            body: JSON.stringify(adatKuldes)
        });
        let adatok = await valasz.json();
        projektTaskKiir(adatok);
    }
    catch(e)
    {
        console.log(e);
    }
}

function projektTaskKiir(adatok)
{
    taskview.innerHTML="";
    gombCreate();
    for(adat of adatok)
    {
        let card=document.createElement("div");
        card.setAttribute("class","card my-5");
        let cardheader=document.createElement("div");
        cardheader.setAttribute("class","card-header d-flex justify-content-between")

        let cardbody=document.createElement("div");
        cardbody.setAttribute("class","card-body");
        let title=document.createElement("h4");
        title.innerHTML=adat.name;
        title.setAttribute("class","card-title");
        let date=document.createElement("h4");
        date.setAttribute("class","card-subtitle");
        date.innerHTML=adat.deadline.substring(0,10);
        let text=document.createElement("p");
        text.setAttribute("class","card-text d-block");
        text.innerHTML=adat.description;
        
        cardheader.appendChild(title);
        cardheader.appendChild(date);
        cardbody.appendChild(text);
        card.appendChild(cardheader);
        card.appendChild(cardbody);
        taskview.appendChild(card);
    }
    console.log("Projekt típusok kiírva!");
}

async function taskHozzaad()
{
    try{
        authCheck();
        let name=escapeHtml(document.getElementById("name").value);
        let desc=escapeHtml(document.getElementById("desc").value.trim());
        let date=escapeHtml(document.getElementById("date").value);
        let adatKuldes = {
            "nev": name,
            "leiras": desc,
            "datum": date,
            "projektSzam": selectProject.value,
        };
        let valasz = await fetch("php/index.php/projektTaskFeltolt", {
            method: 'POST',
            body: JSON.stringify(adatKuldes)
        });
        let adatok = await valasz.json();
        alert(adatok.valasz);
        taskLeker();
        managerTaskLeker();
    }
    catch(e)
    {
        console.log(e);
    }
}

async function managerTaskLeker()
{
    try
    {
        authCheck();
        let valasz=await fetch("php/index.php/managerTaskLeker");
        let adatok=await valasz.json();
        managerTaskKiir(adatok);
    }
    catch(e)
    {
        console.log(e);
    }
}

function managerTaskKiir(adatok)
{
    let managertaskView=document.getElementById("managerTaskView");
    managertaskView.innerHTML="<h4 class='text-center'>Tasks that you created</h4>";
    const currentDate = new Date();
    for(adat of adatok)
    {
        let card=document.createElement("div");
        card.setAttribute("class","card my-5");
        let cardheader=document.createElement("div");
        cardheader.setAttribute("class","card-header d-flex justify-content-between")

        let cardbody=document.createElement("div");
        cardbody.setAttribute("class","card-body");
        let title=document.createElement("h4");
        title.innerHTML=adat.pname+": "+adat.name;
        title.setAttribute("class","card-title");
        let date=document.createElement("h4");
        date.setAttribute("class","card-text");

        if(Math.floor((Date.parse(adat.deadline)-currentDate))/(24*3600*1000)<7)
        {
            if(!futott)
                alert(adat.pname+": "+adat.name+" task expires soon at: "+adat.deadline);
            cardbody.classList.add("text-danger");
        }
        date.innerHTML=adat.deadline.toString().substring(0,10);
        
        cardheader.appendChild(title);
        cardbody.appendChild(date);
        card.appendChild(cardheader);
        card.appendChild(cardbody);
        managertaskView.appendChild(card);
    }
    futott=true;
    console.log("Manager Taskok kiírva!");
}

async function devLeker()
{
    authCheck();
    let adatKuldes={
        "projektSzam":selectProject.value
    };
    let valasz = await fetch("php/index.php/devLeker", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adatok = await valasz.json();
    devKiir(adatok);
}

function devKiir(adatok)
{
    devs.innerHTML="";
    if(adatok.valasz=="Nincs fejlesztő aki hozzáadható lenne a projekthez!")
    {
        let option=document.createElement("option");
        option.value="-1";
        option.innerHTML="No developers suitable for this project!";
        devs.appendChild(option);
        return;
    }
    for(adat of adatok)
    {
        let option=document.createElement("option");
        option.value=adat.id;
        option.innerHTML=adat.id+".: "+adat.name;
        devs.appendChild(option);
    }
}

async function devHozzaad()
{
    authCheck();
    if(devs.value=="-1" || selectProject.value=="-1")
    {
        alert("Invalid developer or project!");
        return;
    }
    let adatKuldes={
        "devSzam":devs.value,
        "projektSzam":selectProject.value
    };
    let valasz = await fetch("php/index.php/devHozzaad", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adatok = await valasz.json();
    alert(adatok.valasz);
}

window.addEventListener("load",function(){
    let user=authCheck();
    let welcome=document.getElementById("welcome");
    welcome.innerHTML="You are logged in as "+user["data"]["name"]+"!";
    projektLeker();
    projektTipusLeker();
    managerTaskLeker();
});

async function logout()
{
    try{
        let valasz=await fetch("php/index.php/userLogout");
        let adatok=await valasz.json();
        if(adatok.valasz="siker!")
        {
            window.location.href="login.php";
        }
        else
        {
            alert("Logout failed!");
        }
    }
    catch(e)
    {
        console.log(e);
    }
}

selectProject.addEventListener("change",taskLeker);
selectType.addEventListener("change",projektLeker);
saveTaskButton.addEventListener("click",taskHozzaad);
saveDevButton.addEventListener("click",devHozzaad);
logoutButton.addEventListener("click",function(){
    logout();
});