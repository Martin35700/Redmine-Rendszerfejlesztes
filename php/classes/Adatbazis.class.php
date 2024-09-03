<?php

class Adatbazis
{
    private $hely="localhost";
    private $felhasznalo="root";
    private $jelszo="";
    private $konkretAdatbazis="redmine";
    private $db;
    public function __construct()
    {
        $this->db=new mysqli($this->hely,$this->felhasznalo,$this->jelszo,$this->konkretAdatbazis);
    }

    protected function Leker($muvelet)
    {
        $eredmeny=$this->db->query($muvelet);
        if($this->db->errno==0)
        {
            if($eredmeny->num_rows!=0)
            {
                $adatok=$eredmeny->fetch_all(MYSQLI_ASSOC);
            }
            else
            {
                $adatok=array("valasz"=>"Nincs találat!");
            }
        }
        else
        {
            $adatok=array("valasz"=> $this->db->error);
        }
        return json_encode($adatok,JSON_UNESCAPED_UNICODE);
    }

    protected function adatokRogzitese($muvelet)
    {
        $this->db->query($muvelet);
        if($this->db->affected_rows > 0)
        {
            $adatok=array("valasz"=> "Sikeres rögzítés!");
        }
        else
        {
            $adatok=array("valasz"=>"Sikertelen rögzítés!");
        }
        return json_encode($adatok,JSON_UNESCAPED_UNICODE);
    }
}

?>