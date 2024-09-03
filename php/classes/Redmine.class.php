<?php

class Redmine extends Adatbazis{
    public function projektOsszesLeker()
    {
        $projektLeker="SELECT `id`, `name`, `type_id`, `description` FROM projects";
        $lekertAdatok=$this->Leker($projektLeker);
        return $lekertAdatok;
    }
    public function projektLeker($tipus)
    {
        $projektLeker="SELECT `id`, `name`, `type_id`, `description` FROM projects WHERE `type_id`='{$tipus}'";
        $lekertAdatok=$this->Leker($projektLeker);
        return $lekertAdatok;
    }
    public function projektTipusLeker()
    {
        $projektLeker="SELECT `id`, `name` FROM project_types";
        $lekertAdatok=$this->Leker($projektLeker);
        return $lekertAdatok;
    }

    public function projektTaskLeker($id)
    {
        $projektLeker="SELECT tasks.`id`,tasks.`name`,tasks.`description`,tasks.`project_id`,tasks.`user_id`,tasks.`deadline`, managers.name AS 'mname' FROM `tasks` INNER JOIN managers ON managers.id=tasks.user_id WHERE tasks.`project_id`='{$id}' ORDER BY tasks.deadline";
        $lekertAdatok=$this->Leker($projektLeker);
        return $lekertAdatok;
    }

    public function projektTaskFeltolt($nev,$leiras,$datum,$projekt,$userID)
    {
        $projektFeltolt="INSERT INTO tasks (`id`,`name`,`description`,`project_id`,`user_id`,`deadline`) VALUES('','{$nev}','{$leiras}','{$projekt}','{$userID}','{$datum}')";
        $lekertAdatok=$this->adatokRogzitese($projektFeltolt);
        return $lekertAdatok;
    }

    public function managerTaskLeker($userID)
    {
        $managerTask="SELECT tasks.`id`,tasks.`name`,tasks.`description`,tasks.`project_id`,tasks.`user_id`,tasks.`deadline`, managers.name AS 'mname', projects.name AS 'pname' FROM `tasks` INNER JOIN managers ON managers.id=tasks.user_id INNER JOIN projects ON projects.id=tasks.project_id WHERE managers.id='{$userID}' ORDER BY tasks.deadline";
        $lekertAdatok=$this->Leker($managerTask);
        return $lekertAdatok;
    }

    public function managerLeker()
    {
        $managers="SELECT `id`, `email`, `password` FROM  managers";
        $lekertAdatok=$this->Leker($managers);
        return $lekertAdatok;
    }

    public function devLeker($projektID)
    {
        $devs="SELECT `id`, `name` FROM developers WHERE id NOT IN (SELECT developer_id FROM project_developers WHERE project_id='{$projektID}')";
        $lekertAdatok=$this->Leker($devs);
        return $lekertAdatok;
    }

    public function devHozzaad($devID,$projektID)
    {
        $devs="INSERT INTO `project_developers`(`developer_id`, `project_id`) VALUES ('{$devID}','{$projektID}')";
        $lekertAdatok=$this->adatokRogzitese($devs);
        return $lekertAdatok;
    }
}

?>