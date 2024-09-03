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
    if(user["data"]["role"]!="Admin")
    {
        alert("You are not an Admin!");
        window.location.href="login.php";
    }
    return user;
}

async function logout()
{
    try{
        let valasz=await fetch("php/index.php/adminLogout");
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

function content()
{
    let cont=document.createElement("div");
    cont.setAttribute("class","container-fluid");
    let row=document.createElement("div");
    row.setAttribute("class","row");
    let col1=document.createElement("div");
    col1.setAttribute("class","col-12 col-lg-4");
    col1.setAttribute("id","content1");
    let col2=document.createElement("div");
    col2.setAttribute("class","col-12 col-lg-4");
    col2.setAttribute("id","content2");
    let col3=document.createElement("div");
    col3.setAttribute("class","col-12 col-lg-4");
    col3.setAttribute("id","content3");
    let span1=document.createElement("h4");
    span1.innerHTML="Projects in progress";
    span1.style.marginTop="100px";
    let span2=document.createElement("h4");
    span2.innerHTML="Managers";
    span2.style.marginTop="100px";
    let span3=document.createElement("h4");
    span3.innerHTML="Developers";
    span3.style.marginTop="100px";
    col1.appendChild(span1);
    col2.appendChild(span2);
    col3.appendChild(span3);
    row.appendChild(col1);
    row.appendChild(col2);
    row.appendChild(col3);
    cont.appendChild(row);
    document.getElementById("body").appendChild(cont);

    projectLeker(1);
    managerLeker(1);
    developerLeker(1);
}

function projectKartyaKiir(adatok)
{
    for(let adat of adatok)
    {
        let card=document.createElement("div");
        card.setAttribute("class","card my-5");
        let cardheader=document.createElement("div");
        cardheader.setAttribute("class","card-header d-flex justify-content-between")
    
        let cardbody=document.createElement("div");
        cardbody.setAttribute("class","card-body");
        let title=document.createElement("h4");
        title.innerHTML="#"+adat.id+" "+adat.name;
        title.setAttribute("class","card-title");
        let date=document.createElement("h4");
        date.setAttribute("class","card-subtitle");
        date.innerHTML=adat.type.name;
        let text=document.createElement("p");
        text.setAttribute("class","card-text d-block");
        text.innerHTML=adat.description;

        let button=document.createElement("button");
        button.setAttribute("type","button");
        button.setAttribute("class","btn btn-primary");
        button.innerHTML="Tasks";
        button.addEventListener("click",function(){
            taskLeker(adat.id);
        });
            
        cardheader.appendChild(title);
        cardheader.appendChild(date);
        cardbody.appendChild(text);
        cardbody.appendChild(button);
        card.appendChild(cardheader);
        card.appendChild(cardbody);
        document.getElementById("content1").appendChild(card);
    }
    
}

async function taskLeker(id)
{
    let adatKuldes={
        "projektSzam":id
    };
    let valasz=await fetch("php/index.php/projektTaskLeker", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adatok=await valasz.json();
    taskKiirFelulet(adatok);
}

function taskKiirFelulet(adatok) {
    authCheck();
    navbar();
    let table = document.createElement("table");
    table.setAttribute("class", "table table-striped table-bordered table-hover table-responsive-md mt-5");

    let thead = document.createElement("thead");
    let tr = document.createElement("tr");
    let th1 = document.createElement("th");
    th1.innerHTML = "Task ID";
    let th2 = document.createElement("th");
    th2.innerHTML = "Task Name";
    let th3 = document.createElement("th");
    th3.innerHTML = "Description";
    let th4 = document.createElement("th");
    th4.innerHTML = "Deadline";
    let th5 = document.createElement("th");
    th5.innerHTML = "Manager";
    let th6 = document.createElement("th");
    th6.innerHTML = "Delete Task";

    tr.appendChild(th1);
    tr.appendChild(th2);
    tr.appendChild(th3);
    tr.appendChild(th4);
    tr.appendChild(th5);
    tr.appendChild(th6);
    thead.appendChild(tr);
    table.appendChild(thead);

    let tbody = document.createElement("tbody");
    for (let adat of adatok) {
        let tr = document.createElement("tr");
        let td1 = document.createElement("td");
        td1.innerHTML = "#"+adat.id;
        let td2 = document.createElement("td");
        td2.innerHTML = adat.name;
        let td3 = document.createElement("td");
        td3.innerHTML = adat.description;
        let td4 = document.createElement("td");
        td4.innerHTML = adat.deadline.toString().substring(0, 10);
        let td5 = document.createElement("td");
        td5.innerHTML = adat.userid.name;
        let td6 = document.createElement("td");
        let button = document.createElement("button");
        button.setAttribute("type", "button");
        button.setAttribute("class", "btn btn-danger");
        button.innerHTML = "Delete";
        button.addEventListener("click", function () {
            taskTorol(adat.id);
        });
        td6.appendChild(button);

        tr.appendChild(td1);
        tr.appendChild(td2);
        tr.appendChild(td3);
        tr.appendChild(td4);
        tr.appendChild(td5);
        tr.appendChild(td6);
        tbody.appendChild(tr);
    }
    table.appendChild(tbody);

    document.getElementById("body").appendChild(table);
}

async function taskTorol(id)
{
    let adatKuldes={
        "taskID":id
    };
    let valasz=await fetch("php/index.php/taskTorol", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adat=await valasz.json();
    if(adat.valasz=="siker!")
    {
        alert("Task removed!");
        navbar();
        content();
    }
    else
    {
        alert("Remove failed!");
        navbar();
        content();
    }
}

function managerKartyaKiir(adatok)
{
    for(let adat of adatok)
    {
        let card=document.createElement("div");
        card.setAttribute("class","card my-5");
        let cardheader=document.createElement("div");
        cardheader.setAttribute("class","card-header d-flex justify-content-between")
    
        let cardbody=document.createElement("div");
        cardbody.setAttribute("class","card-body");
        let title=document.createElement("h4");
        title.innerHTML="#"+adat.id+" "+adat.name;
        title.setAttribute("class","card-title");
        let mail=document.createElement("p");
        mail.setAttribute("class","card-text d-block");
        mail.innerHTML=adat.email;
            
        cardheader.appendChild(title);
        cardbody.appendChild(mail);
        card.appendChild(cardheader);
        card.appendChild(cardbody);
        document.getElementById("content2").appendChild(card);
    }
}

function developerKartyaKiir(adatok)
{
    for(let adat of adatok)
    {
        let card=document.createElement("div");
        card.setAttribute("class","card my-5");
        let cardheader=document.createElement("div");
        cardheader.setAttribute("class","card-header d-flex justify-content-between")
    
        let cardbody=document.createElement("div");
        cardbody.setAttribute("class","card-body");
        let title=document.createElement("h4");
        title.innerHTML="#"+adat.id+" "+adat.name;
        title.setAttribute("class","card-title");
        let mail=document.createElement("p");
        mail.setAttribute("class","card-text d-block");
        mail.innerHTML=adat.email;
        cardbody.appendChild(mail);
        
        if(adat.projects.length>0)
        {
            let h=document.createElement("h5");
            h.innerHTML="Projects:";
            cardbody.appendChild(h);
            for(let p of adat.projects)
            {
                let project=document.createElement("p");
                project.setAttribute("class","card-text d-block");
                project.innerHTML="&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp"+p.name;
                cardbody.appendChild(project);
            }
        }
            
        cardheader.appendChild(title);
        
        
        card.appendChild(cardheader);
        card.appendChild(cardbody);
        document.getElementById("content3").appendChild(card);
    }

}

function navbar()
{
    document.getElementById("body").innerHTML="";

    let cont=document.createElement("div");
    cont.setAttribute("class","container-fluid");

    let row=document.createElement("div");
    row.setAttribute("class","row");
    row.setAttribute("id","toolBar");

    let button1=document.createElement("button");
    button1.setAttribute("type","button");
    button1.setAttribute("id","logOut");
    button1.innerHTML="Logout";
    button1.addEventListener("click",logout);

    let button2=document.createElement("button");
    button2.setAttribute("type","button");
    button2.setAttribute("id","manager");
    button2.innerHTML="Recruit a new manager";
    button2.addEventListener("click",managerUploadFelulet);

    let button3=document.createElement("button");
    button3.setAttribute("type","button");
    button3.setAttribute("id","remove");
    button3.innerHTML="Remove Manager";
    button3.addEventListener("click",function(){
        managerRemoveFelulet();
        managerLeker(0);
    });

    let button4=document.createElement("button");
    button4.setAttribute("type","button");
    button4.setAttribute("id","uploadproject");
    button4.innerHTML="Upload Project";
    button4.addEventListener("click",uploadProjectFelulet);

    let button5=document.createElement("button");
    button5.setAttribute("type","button");
    button5.setAttribute("id","removeproject");
    button5.innerHTML="Remove Project";
    button5.addEventListener("click",function(){
        projectRemoveFelulet();
        projectLeker(0);
    });

    let button6=document.createElement("button");
    button6.setAttribute("type","button");
    button6.setAttribute("id","developer");
    button6.innerHTML="Recruit a new developer";
    button6.addEventListener("click",developerUploadFelulet);

    let button7=document.createElement("button");
    button7.setAttribute("type","button");
    button7.setAttribute("id","removeDeveloper");
    button7.innerHTML="Remove Developer";
    button7.addEventListener("click",function(){
        developerRemoveFelulet();
        developerLeker(0);
    });
    
    let button8=document.createElement("button");
    button8.setAttribute("type","button");
    button8.setAttribute("class","d-block  ml-auto");
    button8.setAttribute("id","home");
    button8.innerHTML="Home";
    button8.addEventListener("click",function(){
        navbar();
        content();
    });

    let row2=document.createElement("div");
    row2.setAttribute("class","row");
    row2.setAttribute("id","h3Row");
    row2.style.marginTop="100px";
    let col2=document.createElement("div");
    col2.setAttribute("class","col-12");
    let h3=document.createElement("h3");
    h3.setAttribute("class","text-center");

    let token=getCookieValue("jwt");
    let name=parseJwt(token)["data"]["name"];
    h3.innerHTML="Welcome "+name+"!";
    col2.appendChild(h3);
    row2.appendChild(col2);

    row.appendChild(button1);
    row.appendChild(button2);
    row.appendChild(button3);
    row.appendChild(button4);
    row.appendChild(button5);
    row.appendChild(button6);
    row.appendChild(button7);
    row.appendChild(button8);

    cont.appendChild(row);
    cont.appendChild(row2);

    document.getElementById("body").appendChild(cont);
}

async function developerLeker(type)
{
    let valasz=await fetch("php/index.php/devOsszesLeker");
    let adatok=await valasz.json();
    if(type==0)
        developerKiir(adatok);
    else
        developerKartyaKiir(adatok);
}

function developerKiir(adatok)
{
    let select=document.getElementById("developerSelect");
    select.innerHTML="";
    for(let adat of adatok)
    {
        let option=document.createElement("option");
        option.setAttribute("value",adat.id);
        option.innerHTML=adat.name;
        select.appendChild(option);
    }
}

function developerRemoveFelulet()
{
    authCheck();
    navbar();
    let cont1=document.createElement("div");
    cont1.setAttribute("class","container mt-5");
    let row1=document.createElement("div");
    row1.setAttribute("class","row justify-content-center");
    let col1=document.createElement("div");
    col1.setAttribute("class","col-12");
    let form=document.createElement("form");
    let formgroup=document.createElement("div");
    formgroup.setAttribute("class","form-group");
    let label=document.createElement("label");
    label.setAttribute("for","developerSelect");
    label.innerHTML="Select Developer to Remove:";
    let select=document.createElement("select");
    select.setAttribute("class","form-control");
    select.setAttribute("id","developerSelect");
    select.setAttribute("name","developerSelect");
    formgroup.appendChild(label);
    formgroup.appendChild(select);
    form.appendChild(formgroup);
    let button=document.createElement("button");
    button.setAttribute("type","button");
    button.setAttribute("class","btn-lg btn-danger");
    button.innerHTML="Remove Developer";
    button.addEventListener("click",removeDeveloper);
    form.appendChild(button);
    col1.appendChild(form);
    row1.appendChild(col1);
    cont1.appendChild(row1);
    document.getElementById("body").appendChild(cont1);
}

async function removeDeveloper()
{
    let select=document.getElementById("developerSelect");
    let id=select.value;

    let adatKuldes={
        "developerID":id
    };
    let valasz = await fetch("php/index.php/devTorol", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adat = await valasz.json();
    if(adat.valasz=="siker!")
    {
        alert("Developer removed!");
        navbar();
        content();
    }
    else
    {
        alert("Remove failed!");
        navbar();
    }
}

function developerUploadFelulet()
{
    authCheck();
    navbar();
    let cont1=document.createElement("div");
    cont1.setAttribute("class","container");
    let row1=document.createElement("div");
    row1.setAttribute("class","row justify-content-center");
    let col1=document.createElement("div");
    col1.setAttribute("class","col-md-12");
    col1.style.marginTop="100px";
    let h2=document.createElement("h2");
    h2.setAttribute("class","text-center mb-4");
    h2.innerHTML="Recruit a new developer";
    let form=document.createElement("form");
    let formgroup1=document.createElement("div");
    let label1=document.createElement("label");
    label1.setAttribute("for","developername");
    label1.innerHTML="Developer name";
    let input1=document.createElement("input");
    input1.setAttribute("type","text");
    input1.setAttribute("class","form-control");
    input1.setAttribute("id","developername");
    input1.setAttribute("placeholder","Enter name");
    let formgroup2=document.createElement("div");
    let label2=document.createElement("label");
    label2.setAttribute("for","developeremail");
    label2.innerHTML="Email address";
    let input2=document.createElement("input");
    input2.setAttribute("type","email");
    input2.setAttribute("class","form-control");
    input2.setAttribute("id","developeremail");
    input2.setAttribute("placeholder","Enter email");
    
    let button=document.createElement("button");
    button.setAttribute("type","button");
    button.setAttribute("class","btn btn-primary btn-block mt-5");
    button.setAttribute("id","upload");
    button.addEventListener("click",uploadDeveloper);
    button.innerHTML="Upload Developer";
    
    formgroup1.appendChild(label1);
    formgroup1.appendChild(input1);
    formgroup2.appendChild(label2);
    formgroup2.appendChild(input2);
    form.appendChild(formgroup1);
    form.appendChild(formgroup2);
    form.appendChild(button);
    col1.appendChild(h2);
    col1.appendChild(form);
    row1.appendChild(col1);
    cont1.appendChild(row1);
    document.getElementById("body").appendChild(cont1);
}

async function uploadDeveloper()
{
    let name=escapeHtml(document.getElementById("developername").value.trim());
    let mail=escapeHtml(document.getElementById("developeremail").value.trim());
    let adatKuldes={
        "developername":name,
        "developeremail":mail,
    };
    let valasz = await fetch("php/index.php/devFeltolt", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adat = await valasz.json();
    if(adat.valasz=="siker!")
    {
        alert("Developer uploaded!");
        navbar();
        content();
    }
    else
    {
        alert("Upload failed! Please try again!");
        navbar();
        content();
    }

}

async function projectLeker(type)
{
    let valasz=await fetch("php/index.php/projektOsszesLeker");
    let adatok=await valasz.json();
    if(type==0)
        projectKiir(adatok);
    else
        projectKartyaKiir(adatok);
}

function projectKiir(adatok)
{
    let select=document.getElementById("projectSelect");
    select.innerHTML="";
    for(let adat of adatok)
    {
        let option=document.createElement("option");
        option.setAttribute("value",adat.id);
        option.innerHTML=adat.name;
        select.appendChild(option);
    }
}

function projectRemoveFelulet()
{
    authCheck();
    navbar();
    let cont1=document.createElement("div");
    cont1.setAttribute("class","container mt-5");
    let row1=document.createElement("div");
    row1.setAttribute("class","row justify-content-center");
    let col1=document.createElement("div");
    col1.setAttribute("class","col-12");
    let form=document.createElement("form");
    let formgroup=document.createElement("div");
    formgroup.setAttribute("class","form-group");
    let label=document.createElement("label");
    label.setAttribute("for","projectSelect");
    label.innerHTML="Select Project to Remove:";
    let select=document.createElement("select");
    select.setAttribute("class","form-control");
    select.setAttribute("id","projectSelect");
    select.setAttribute("name","projectSelect");
    formgroup.appendChild(label);
    formgroup.appendChild(select);
    form.appendChild(formgroup);
    let button=document.createElement("button");
    button.setAttribute("type","button");
    button.setAttribute("class","btn-lg btn-danger");
    button.innerHTML="Remove Project";
    button.addEventListener("click",removeProject);
    form.appendChild(button);
    col1.appendChild(form);
    row1.appendChild(col1);
    cont1.appendChild(row1);
    document.getElementById("body").appendChild(cont1);

    alert("Caution! Removing a project will also remove all tasks assigned to it!")
}

async function removeProject()
{
    let select=document.getElementById("projectSelect");
    let id=select.value;
    let adatKuldes={
        "projectID":id
    };
    let valasz = await fetch("php/index.php/projectTorol", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adat = await valasz.json();
    if(adat.valasz=="siker!")
    {
        alert("Project removed!");
        navbar();
        content();
    }
    else
    {
        alert("Remove failed!");
        navbar();
        content();
    }
}

function uploadProjectFelulet()
{
    authCheck();
    navbar();
    let cont1=document.createElement("div");
    cont1.setAttribute("class","container mt-5");
    let row1=document.createElement("div");
    row1.setAttribute("class","row justify-content-center");
    let col1=document.createElement("div");
    col1.setAttribute("class","col-12");
    let form=document.createElement("form");
    let formgroup1=document.createElement("div");
    formgroup1.setAttribute("class","form-group");
    let label1=document.createElement("label");
    label1.setAttribute("for","projectname");
    label1.innerHTML="Project name";
    let input1=document.createElement("input");
    input1.setAttribute("type","text");
    input1.setAttribute("class","form-control");
    input1.setAttribute("id","projectname");
    input1.setAttribute("placeholder","Enter name");
    let formgroup2=document.createElement("div");
    formgroup2.setAttribute("class","form-group");
    let label2=document.createElement("label");
    label2.setAttribute("for","projectdescription");
    label2.innerHTML="Description";
    let input2=document.createElement("input");
    input2.setAttribute("type","text");
    input2.setAttribute("class","form-control");
    input2.setAttribute("id","projectdescription");
    input2.setAttribute("placeholder","Enter description");
    let formgroup3=document.createElement("div");
    formgroup3.setAttribute("class","form-group");
    let label3=document.createElement("label");
    label3.setAttribute("for","projecttype");
    label3.innerHTML="Type of project";
    let select=document.createElement("select");
    select.setAttribute("class","form-control");
    select.setAttribute("id","projecttype");
    select.setAttribute("name","projecttype");
    formgroup1.appendChild(label1);
    formgroup1.appendChild(input1);
    formgroup2.appendChild(label2);
    formgroup2.appendChild(input2);
    formgroup3.appendChild(label3);
    formgroup3.appendChild(select);
    form.appendChild(formgroup1);
    form.appendChild(formgroup2);
    form.appendChild(formgroup3);
    let button=document.createElement("button");
    button.setAttribute("type","button");
    button.setAttribute("class","btn-lg btn-success");
    button.innerHTML="Upload Project";
    button.addEventListener("click",uploadProject);
    form.appendChild(button);
    col1.appendChild(form);
    row1.appendChild(col1);
    cont1.appendChild(row1);
    document.getElementById("body").appendChild(cont1);

    typeLeker();
}

async function typeLeker()
{
    let valasz=await fetch("php/index.php/projektTipusLeker");
    let adatok=await valasz.json();
    typeKiir(adatok);
}

function typeKiir(adatok)
{
    let select=document.getElementById("projecttype");
    for(let adat of adatok)
    {
        let option=document.createElement("option");
        option.setAttribute("value",adat.id);
        option.innerHTML=adat.name;
        select.appendChild(option);
    }
}

async function uploadProject()
{
    let name=escapeHtml(document.getElementById("projectname").value.trim());
    let desc=escapeHtml(document.getElementById("projectdescription").value.trim());
    let type=escapeHtml(document.getElementById("projecttype").value);
    let adatKuldes={
        "projectname":name,
        "projectdescription":desc,
        "projecttype":type
    };
    let valasz = await fetch("php/index.php/projectFeltolt", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adat = await valasz.json();
    if(adat.valasz=="siker!")
    {
        alert("Project uploaded!");
        navbar();
        content();
    }
    else
    {
        alert("Upload failed! Please try again!");
        navbar()
        content();
    }
}

function managerUploadFelulet()
{
    authCheck();
    navbar();
    let cont1=document.createElement("div");
    cont1.setAttribute("class","container");
    let row1=document.createElement("div");
    row1.setAttribute("class","row justify-content-center");
    let col1=document.createElement("div");
    col1.setAttribute("class","col-12");
    col1.style.marginTop="100px";
    let h2=document.createElement("h2");
    h2.setAttribute("class","text-center mb-4");
    h2.innerHTML="Recruit a new manager";
    let form=document.createElement("form");
    let formgroup1=document.createElement("div");
    let label1=document.createElement("label");
    label1.setAttribute("for","managername");
    label1.innerHTML="Manager name";
    let input1=document.createElement("input");
    input1.setAttribute("type","text");
    input1.setAttribute("class","form-control");
    input1.setAttribute("id","managername");
    input1.setAttribute("placeholder","Enter name");
    let formgroup2=document.createElement("div");
    let label2=document.createElement("label");
    label2.setAttribute("for","manageremail");
    label2.innerHTML="Email address";
    let input2=document.createElement("input");
    input2.setAttribute("type","email");
    input2.setAttribute("class","form-control");
    input2.setAttribute("id","manageremail");
    input2.setAttribute("placeholder","Enter email");
    let formgroup3=document.createElement("div");
    let label3=document.createElement("label");
    label3.setAttribute("for","managerpassword");
    label3.innerHTML="Password";
    let input3=document.createElement("input");
    input3.setAttribute("type","password");
    input3.setAttribute("class","form-control");
    input3.setAttribute("id","managerpassword");
    input3.setAttribute("placeholder","Password");
    let button=document.createElement("button");
    button.setAttribute("type","button");
    button.setAttribute("class","btn btn-primary btn-block mt-5");
    button.setAttribute("id","upload");
    button.innerHTML="Upload";
    
    formgroup1.appendChild(label1);
    formgroup1.appendChild(input1);
    formgroup2.appendChild(label2);
    formgroup2.appendChild(input2);
    formgroup3.appendChild(label3);
    formgroup3.appendChild(input3);
    form.appendChild(formgroup1);
    form.appendChild(formgroup2);
    form.appendChild(formgroup3);
    form.appendChild(button);
    col1.appendChild(h2);
    col1.appendChild(form);
    row1.appendChild(col1);
    cont1.appendChild(row1);
    document.getElementById("body").appendChild(cont1);

    let upload=document.getElementById("upload");
    upload.addEventListener("click",uploadManager);
}

function managerRemoveFelulet()
{
    authCheck();
    navbar();
    let cont1=document.createElement("div");
    cont1.setAttribute("class","container mt-5");
    let row1=document.createElement("div");
    row1.setAttribute("class","row justify-content-center");
    let col1=document.createElement("div");
    col1.setAttribute("class","col-12");
    let form=document.createElement("form");
    let formgroup=document.createElement("div");
    formgroup.setAttribute("class","form-group");
    let label=document.createElement("label");
    label.setAttribute("for","managerSelect");
    label.innerHTML="Select Manager to Remove:";
    let select=document.createElement("select");
    select.setAttribute("class","form-control");
    select.setAttribute("id","managerSelect");
    select.setAttribute("name","managerSelect");
    formgroup.appendChild(label);
    formgroup.appendChild(select);
    form.appendChild(formgroup);
    let button=document.createElement("button");
    button.setAttribute("type","button");
    button.setAttribute("class","btn-lg btn-danger");
    button.innerHTML="Remove Manager";
    button.addEventListener("click",removeManager);
    form.appendChild(button);
    col1.appendChild(form);
    row1.appendChild(col1);
    cont1.appendChild(row1);
    document.getElementById("body").appendChild(cont1);

    alert("Caution! Removing a manager will also remove all tasks assigned to them!")
}

async function uploadManager()
{
    let name=escapeHtml(document.getElementById("managername").value.trim());
    let mail=escapeHtml(document.getElementById("manageremail").value.trim());
    let pwd=escapeHtml(document.getElementById("managerpassword").value.trim());
    let salt="sajt";
    pwd=pwd+salt;
    pwd=CryptoJS.SHA256(pwd).toString();
    let adatKuldes={
        "managername":name,
        "manageremail":mail,
        "managerpwd":pwd
    };
    let valasz = await fetch("php/index.php/managerFeltolt", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adat = await valasz.json();
    if(adat.valasz=="siker!")
    {
        alert("Manager uploaded!");
        navbar();
        content();
    }
    else
    {
        alert("Upload failed! Please try again!");
        navbar();
        content();
    }
}

async function removeManager()
{
    let select=document.getElementById("managerSelect");
    let id=select.value;
    let adatKuldes={
        "managerID":id
    };
    let valasz = await fetch("php/index.php/managerTorol", {
        method: 'POST',
        body: JSON.stringify(adatKuldes)
    });
    let adat = await valasz.json();
    if(adat.valasz=="siker!")
    {
        alert("Manager removed!");
        navbar();
        content();
    }
    else
    {
        alert("Remove failed!");
        navbar();
        content();
    }
}

async function managerLeker(type)
{
    let valasz=await fetch("php/index.php/managerLeker");
    let adatok=await valasz.json();
    if(type==0)
        managerKiir(adatok);
    else
        managerKartyaKiir(adatok);
}

function managerKiir(adatok)
{
    let select=document.getElementById("managerSelect");
    select.innerHTML="";
    for(let adat of adatok)
    {
        let option=document.createElement("option");
        option.setAttribute("value",adat.id);
        option.innerHTML=adat.name;
        select.appendChild(option);
    }
}

window.addEventListener("load",function(){
    authCheck();
    navbar();
    content();
});