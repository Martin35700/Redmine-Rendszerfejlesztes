<?php

namespace Redmine;


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . "/../vendor/autoload.php";
class Route{
    private $teljesUrl;
    private $url;
    private $erkezettAdatok;
    public function __construct($atadottUrl){
        $this->teljesUrl = $atadottUrl;
        $this->url = explode("/",$this->teljesUrl);
        $this->erkezettAdatok=json_decode(file_get_contents("php://input"),false);
    }

    public function decodeCookie($token)
    {
        try
        {
            $decode=json_decode(base64_decode(str_replace('_', '/', str_replace('-','+',explode('.', $token)[1]))));
            /*$key = new Key('redmine', 'HS256');
            $options = new \stdClass();
            $decode = JWT::decode($token, $key, $options);*/
            return json_decode(json_encode($decode),true);
        }
        catch(\Exception $e)
        {
            return null;
        }
    }

    public function doesValidJWTExist()
    {
        $result = $this->decodeCookie($_COOKIE["jwt"]);
        if($result==null)
        {
            return false;
        }
        return true;
    }

    public function urlRoute()
    {
        switch(end($this->url)){
            case "projektOsszesLeker":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->projektOsszesLeker());
                    break;
                }
            case "projektLeker":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->projektLeker($this->erkezettAdatok->projektTipus));
                    break;
                }
            case "projektTipusLeker":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->projektTipusLeker());
                    break;
                }
            case "projektTaskLeker":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->projektTaskLeker($this->erkezettAdatok->projektSzam));
                    break;
                }
            case "projektTaskFeltolt":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    $decodedCookie = (array) $this->decodeCookie($_COOKIE["jwt"]);
                    $id = $decodedCookie["data"]["id"];
                    echo json_encode($redmine->projektTaskFeltolt($this->erkezettAdatok->nev,$this->erkezettAdatok->leiras,$this->erkezettAdatok->datum, $this->erkezettAdatok->projektSzam,$id));
                    break;
                }
            case "projectFeltolt":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->projectFeltolt($this->erkezettAdatok->projectname,$this->erkezettAdatok->projectdescription,$this->erkezettAdatok->projecttype));
                    break;
                }
            case "projectTorol":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->projectTorol($this->erkezettAdatok->projectID));
                    break;
                }
            case "managerLeker":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->managerLeker());
                    break;
                }
            case "managerTaskLeker":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    $decodedCookie = (array) $this->decodeCookie($_COOKIE["jwt"]);
                    $id = $decodedCookie["data"]["id"];
                    echo json_encode($redmine->managerTaskLeker($id));
                    break;
                }
            case "devLeker":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->devLeker($this->erkezettAdatok->projektSzam));
                    break;
                }
            case "devOsszesLeker":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->devOsszesLeker());
                    break;
                }
            case "devTorol":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->devTorol($this->erkezettAdatok->developerID));
                    break;
                }
            case "devHozzaad":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->devHozzaad($this->erkezettAdatok->devSzam,$this->erkezettAdatok->projektSzam));
                    break;
                }
            case "devFeltolt":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->devFeltolt($this->erkezettAdatok->developername,$this->erkezettAdatok->developeremail));
                    break;
                }
            case "dbCreate":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->dbCreate());
                    break;
                }
            case "loginLeker":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->login($this->erkezettAdatok->email,$this->erkezettAdatok->pwd));
                    break;
                }
            case "userLogout":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->userLogout());
                    break;
                }
            case "adminLogout":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->adminLogout());
                    break;
                }
            
            case "managerFeltolt":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->managerUpload($this->erkezettAdatok->managername,$this->erkezettAdatok->manageremail,$this->erkezettAdatok->managerpwd));
                    break;
                }
            case "managerTorol":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->managerDelete($this->erkezettAdatok->managerID));
                    break;
                }
            case "taskTorol":
                {
                    if(!$this->doesvalidJWTExist())
                    {
                        header("location: login.php");
                        return;
                    }
                    $redmine=new Redmine();
                    echo json_encode($redmine->taskTorol($this->erkezettAdatok->taskID));
                    break;
                }
            default:
            {
                break;
            }
        }
    }
}