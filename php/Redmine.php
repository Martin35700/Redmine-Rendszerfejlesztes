<?php

namespace Redmine;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Query\ResultSetMapping;

use Exception;
use Redmine\Entity\Developer;
use Redmine\Entity\ProjectDeveloper;
use Doctrine\ORM\Tools\SchemaTool;

require_once __DIR__ . "/../vendor/autoload.php";
class Redmine {
    private $builder;
    private $conn;
    private $manager;

    private $config;
    public function __construct() {
        try{
            $connectionParams=[
                'dbname' => 'redmine',
                'user' => 'root',
                'password' => '',
                'host' => 'localhost',
                'driver' => 'pdo_mysql',
            ];
            
            $this->config = ORMSetup::createAttributeMetadataConfiguration(
                paths: array(__DIR__."/Entity"),
                isDevMode: true
            );
            $this->config->setAutoGenerateProxyClasses(true);

            $this->conn = DriverManager::getConnection($connectionParams);
            $this->manager = new EntityManager($this->conn, $this->config);
            $this->builder = $this->manager->createQueryBuilder();

        }catch(Exception $e){
            var_dump($e->getMessage());
        }
    }

    public function projektOsszesLeker()
    {
        $projects=$this->manager->getRepository(Entity\Project::class)->findAll();
        $array=[];
        foreach ($projects as $p)
        {
            array_push($array, $p->expose());
        }
        return $array;
    }

    public function projektLeker($tipus)
    {
        $projects = $this->builder->select("p")->from(Entity\Project::class,"p")->where("p.type =?1")->setParameter(1,$tipus)->getQuery()->getResult();
        $array=[];
        foreach ($projects as $p)
        {
            array_push($array, $p->expose());
        }
        return $array;
    }
    public function projektTipusLeker()
    {
        $types=$this->manager->getRepository(Entity\Type::class)->findAll();
        $array=[];
        foreach ($types as $t)
        {
            array_push($array, $t->expose());
        }
        return $array;
    }

    public function projektTaskLeker($id)
    {
        $tasks = $this->builder->select("t")->from(Entity\Task::class,"t")->where("t.project =?1")->setParameter(1,$id)->orderBy("t.deadline")->getQuery()->getResult();
        $array=[];
        foreach ($tasks as $t)
        {
            array_push($array, $t->expose());
        }
        return $array;
    }

    public function projektTaskFeltolt($nev,$leiras,$datum,$projekt,$userID)
    {
        try
        {
            $p=$this->manager->getRepository(Entity\Project::class)->findOneBy(["id"=>$projekt]);
            $u=$this->manager->getRepository(Entity\Manager::class)->findOneBy(["id"=>$userID]);
            $task = new Entity\Task();
            $task->setName($nev);
            $task->setDescription($leiras);
            $task->setProject($p);
            $task->setUser($u);
            $deadline = new \DateTime($datum);
            $task->setDeadline($deadline);
            $this->manager->persist($task);
            $this->manager->flush();
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function managerTaskLeker($userID)
    {
        $tasks = $this->builder->select("t")->from(Entity\Task::class,"t")->where("t.user =?1")->setParameter(1,$userID)->getQuery()->getResult();
        $array=[];
        foreach ($tasks as $t)
        {
            array_push($array, $t->expose());
        }
        return $array;
    }

    public function managerLeker()
    {
        $managers=$this->manager->getRepository(Entity\Manager::class)->findAll();
        $array=[];
        foreach ($managers as $m)
        {
            array_push($array, $m->expose());
        }
        return $array;
    }

    public function devLeker($projektID)
    {
        $array=[];
        if($projektID!=null)
        {
            $sql="SELECT `id`, `name` FROM developers WHERE id NOT IN (SELECT developer_id FROM project_developers pd WHERE project_id='{$projektID}')";
            $devs = $this->conn->prepare($sql)->executeQuery()->fetchAllAssociative();

            if(!empty($devs))
            {
                foreach ($devs as $d)
                {
                    array_push($array, $d);
                }
            }
            else
            {
                $array=["valasz"=>"Nincs fejlesztő aki hozzáadható lenne a projekthez!"];
                return $array;
            }
        }
        else
        {
            var_dump("Nincs projektID");
        }
        return $array;
    }

    public function devHozzaad($devID,$projektID)
    {
        try
        {
            $d=$this->manager->getRepository(Entity\Developer::class)->findOneBy(["id"=>$devID]);
            $p=$this->manager->getRepository(Entity\Project::class)->findOneBy(["id"=>$projektID]);
            $pd = new Entity\ProjectDeveloper();
            $pd->setDeveloper($d);
            $pd->setProject($p);
            $this->manager->persist($pd);
            $this->manager->flush();
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function dbCreate()
    {
        $conn = DriverManager::getConnection
        ([
            'dbname' => '',
            'user' => 'root',
            'password' => '',
            'host' => 'localhost',
            'driver' => 'pdo_mysql',
        ]);

        $doesDbExist = $conn->fetchOne("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'redmine'");

        if ($doesDbExist) 
        {
            $array=["valasz"=>"Az adatbázis már létezik!"];
            return $array;
        }

        $conn->executeStatement("CREATE DATABASE redmine");

        $conn = $this->manager->getConnection();

        $tool = new SchemaTool($this->manager);
        $classes = array(
            $this->manager->getClassMetadata(Developer::class),
            $this->manager->getClassMetadata(Entity\Manager::class),
            $this->manager->getClassMetadata(Entity\Project::class),
            $this->manager->getClassMetadata(ProjectDeveloper::class),
            $this->manager->getClassMetadata(Entity\Task::class),
            $this->manager->getClassMetadata(Entity\Type::class),
        );
        $tool->updateSchema($classes);

        $conn->executeStatement("INSERT INTO `project_types` (`id`, `name`) VALUES (1, 'Web Development'), (2, 'Game Development')");
        $conn->executeStatement("INSERT INTO `developers` (`id`, `name`, `email`) VALUES (1, 'Harnos Adrián', 'adrian@harnos.hu'), (2, 'Dömök Martin', 'martin.domok2002@gmail.com')");
        $conn->executeStatement("INSERT INTO `managers` (`id`, `name`, `email`, `password`) VALUES (1, 'Heller Benedek', 'heller.benedek@gmail.com', 'beni123'), (2, 'Ferencz Kristóf', 'ferencz.kristof@gmail.com', 'kristof123')");
        $conn->executeStatement("INSERT INTO `projects` (`id`, `name`, `type_id`, `description`) VALUES (1, 'Platformer x', 2, 'The best platformer game ever'), (2, 'Vanenet.hu', 1, 'Checks if you have internet connection')");
        $conn->executeStatement("INSERT INTO `project_developers` (`id`, `developer_id`, `project_id`) VALUES (1, 1, 1), (2, 2, 2)");
        $conn->executeStatement("INSERT INTO `tasks` (`id`, `name`, `description`, `project_id`, `user_id`, `deadline`) VALUES (2, 'Test automatization', 'auto tests', 1, 1, '2024-04-06 00:00:00'), (3, 'Backend', 'Backend with php', 2, 1, '2024-04-15 00:00:00'), (6, 'Frontend', 'Frontend for the website', 2, 2, '2024-04-12 00:00:00')");   

        $array=["valasz"=>"Sikeres adatbázis létrehozás!"];
        return $array;
    }
}

?>