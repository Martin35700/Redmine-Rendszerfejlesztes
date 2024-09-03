<?php

namespace Redmine;

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

    public function urlRoute()
    {
        switch(end($this->url)){
            case "projektOsszesLeker":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->projektOsszesLeker());
                    break;
                }
            case "projektLeker":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->projektLeker($this->erkezettAdatok->projektTipus));
                    break;
                }
            case "projektTipusLeker":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->projektTipusLeker());
                    break;
                }
            case "projektTaskLeker":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->projektTaskLeker($this->erkezettAdatok->projektSzam));
                    break;
                }
            case "projektTaskFeltolt":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->projektTaskFeltolt($this->erkezettAdatok->nev,$this->erkezettAdatok->leiras,$this->erkezettAdatok->datum, $this->erkezettAdatok->projektSzam, $this->erkezettAdatok->userID));
                    break;
                }
            case "managerTaskLeker":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->managerTaskLeker($this->erkezettAdatok->userID));
                    break;
                }
            case "managerLeker":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->managerLeker());
                    break;
                }
            case "devLeker":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->devLeker($this->erkezettAdatok->projektSzam));
                    break;
                }
            case "devHozzaad":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->devHozzaad($this->erkezettAdatok->devSzam,$this->erkezettAdatok->projektSzam));
                    break;
                }

            case "dbCreate":
                {
                    $redmine=new Redmine();
                    echo json_encode($redmine->dbCreate());
                    break;
                }
            default:
            {
                break;
            }
        }
    }
}
?>