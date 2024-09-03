<?php

namespace Redmine;

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Query\ResultSetMapping;

use Exception;
use Redmine\Entity\Developer;
use Redmine\Entity\ProjectDeveloper;
use Redmine\Entity\Type;
use Redmine\Entity\Task;
use Redmine\Entity\Manager;
use Redmine\Entity\Project;
use Redmine\Entity\Admin;
use Doctrine\ORM\Tools\SchemaTool;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

require_once __DIR__ . "/../vendor/autoload.php";
class Redmine {
    private $builder;
    private $conn;
    private $manager;

    private $config;

    private $key="redmine";
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
        $projects=$this->manager->getRepository(Project::class)->findAll();
        $array=[];
        foreach ($projects as $p)
        {
            array_push($array, $p->expose());
        }
        return $array;
    }

    public function projektLeker($tipus)
    {
        $projects = $this->builder->select("p")->from(Project::class,"p")->where("p.type =?1")->setParameter(1,$tipus)->getQuery()->getResult();
        $array=[];
        foreach ($projects as $p)
        {
            array_push($array, $p->expose());
        }
        return $array;
    }
    public function projektTipusLeker()
    {
        $types=$this->manager->getRepository(Type::class)->findAll();
        $array=[];
        foreach ($types as $t)
        {
            array_push($array, $t->expose());
        }
        return $array;
    }

    public function projektTaskLeker($id)
    {
        $tasks = $this->builder->select("t")->from(Task::class,"t")->where("t.project =?1")->setParameter(1,$id)->orderBy("t.deadline")->getQuery()->getResult();
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
            $p=$this->manager->getRepository(Project::class)->findOneBy(["id"=>$projekt]);
            $u=$this->manager->getRepository(Manager::class)->findOneBy(["id"=>$userID]);
            $task = new Task();
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
        $tasks = $this->builder->select("t")->from(Task::class,"t")->where("t.user =?1")->setParameter(1,$userID)->orderBy("t.deadline")->getQuery()->getResult();
        $array=[];
        foreach ($tasks as $t)
        {
            array_push($array, $t->expose());
        }
        return $array;
    }

    public function userLogout()
    {
        try
        {
            setcookie("jwt", "-1", time()-3600, "/","",true);
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function adminLogout()
    {
        try
        {
            setcookie("jwt", "-1", time()-3600, "/","",true);
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function login($email,$pwd)
    {
        try
        {
            $manager=$this->manager->getRepository(Manager::class)->findOneBy(["email"=>$email]);
            if($manager!=null)
            {
                if($pwd==$manager->getPassword())
                {
                    $payload=[
                        "iss"=>"localhost",
                        "aud"=>"localhost",
                        "iat" => time(),
                        "nbf" => time(),
                        "exp" => time() + 86400,
                        "data"=>[
                            "id"=>$manager->getId(),
                            "name"=>$manager->getName(),
                            "role"=>"Manager"
                        ]
                    ];

                    $encode=JWT::encode($payload,$this->key,"HS256");
                    setcookie("jwt",$encode, time()+86400,"/","",true);
                    return array("valasz"=>"site.php");
                }
                else
                {
                    return array("valasz"=>"Hiba!");
                }
            }
            else
            {
                $admin=$this->manager->getRepository(Admin::class)->findOneBy(["email"=>$email]);
                if($admin!=null)
                {
                    if($pwd==$admin->getPassword())
                    {
                        $payload=[
                            "iss"=>"localhost",
                            "aud"=>"localhost",
                            "iat" => time(),
                            "nbf" => time(),
                            "exp" => time() + 86400,
                            "data"=>[
                                "id"=>$admin->getId(),
                                "name"=>$admin->getName(),
                                "role"=>"Admin"
                            ]
                        ];
    
                        $encode=JWT::encode($payload,$this->key,"HS256");
                        setcookie("jwt",$encode, time()+86400,"/","",true);
                        return array("valasz"=>"admin.php");
                    }
                    else
                    {
                        return array("valasz"=>"Hiba!");
                    }
                }
                else
                {
                    return array("valasz"=>"Hiba!");
                }
            }
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function devLeker($projektID)
    {
        $array=[];

        $projektID = filter_var($projektID, FILTER_SANITIZE_NUMBER_INT);
        if($projektID==false)
            return array("valasz"=>"Hibás input!");

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
            $d=$this->manager->getRepository(Developer::class)->findOneBy(["id"=>$devID]);
            $p=$this->manager->getRepository(Project::class)->findOneBy(["id"=>$projektID]);
            $pd = new ProjectDeveloper();
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

    public function managerUpload($name,$mail,$pwd)
    {
        try
        {
            $manager = new Manager();
            $manager->setName($name);
            $manager->setEmail($mail);
            $manager->setPassword($pwd);
            $this->manager->persist($manager);
            $this->manager->flush();
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function managerDelete($id)
    {
        try
        {
            $manager=$this->manager->getRepository(Manager::class)->findOneBy(["id"=>$id]);
            $tasks = $this->manager->getRepository(Task::class)->findBy(["user" => $manager]);
            foreach ($tasks as $task) {
                $this->manager->remove($task);
            }
            $this->manager->flush();
            $this->manager->remove($manager);
            $this->manager->flush();
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function managerLeker()
    {
        $managers=$this->manager->getRepository(Manager::class)->findAll();
        $array=[];
        foreach ($managers as $m)
        {
            array_push($array, $m->expose());
        }
        return $array;
    }

    public function projectFeltolt($nev,$leiras,$tipus)
    {
        try
        {
            $t=$this->manager->getRepository(Type::class)->findOneBy(["id"=>$tipus]);
            $project = new Project();
            $project->setName($nev);
            $project->setType($t);
            $project->setDescription($leiras);
            $this->manager->persist($project);
            $this->manager->flush();
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function projectTorol($id)
    {
        try
        {
            $project=$this->manager->getRepository(Project::class)->findOneBy(["id"=>$id]);
            $tasks = $this->manager->getRepository(Task::class)->findBy(["project" => $project]);
            
            foreach ($tasks as $task) {
                $this->manager->remove($task);
            }
            $this->manager->flush();
    
            $projectDevelopers = $this->manager->getRepository(ProjectDeveloper::class)->findBy(["project" => $project]);
            foreach ($projectDevelopers as $developer)
            {
                $this->manager->remove($developer);
            }
            $this->manager->flush();
    
            $this->manager->remove($project);
            $this->manager->flush();
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function devFeltolt($nev,$email)
    {
        try
        {
            $dev=new Developer();
            $dev->setName($nev);
            $dev->setEmail($email);
            $this->manager->persist($dev);
            $this->manager->flush();
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function devOsszesLeker()
    {
        $devs=$this->manager->getRepository(Developer::class)->findAll();
        $array=[];
        foreach ($devs as $d)
        {
            array_push($array, $d->expose());
        }
        return $array;
    }

    public function devTorol($id)
    {
        try
        {
            $dev=$this->manager->getRepository(Developer::class)->findOneBy(["id"=>$id]);
            $projectDevelopers = $this->manager->getRepository(ProjectDeveloper::class)->findBy(["developer" => $dev]);
            foreach ($projectDevelopers as $projectDeveloper)
            {
                $this->manager->remove($projectDeveloper);
            }
            $this->manager->flush();
            $this->manager->remove($dev);
            $this->manager->flush();
            return array("valasz"=>"siker!");
        }
        catch(Exception $e)
        {
            return array("valasz"=>$e->getMessage());
        }
    }

    public function taskTorol($id)
    {
        try
        {
            $task=$this->manager->getRepository(Task::class)->findOneBy(["id"=>$id]);
            $this->manager->remove($task);
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

        $conn->prepare("CREATE DATABASE redmine")->executeStatement();

        $conn = $this->manager->getConnection();

        $tool = new SchemaTool($this->manager);
        $classes = array(
            $this->manager->getClassMetadata(Developer::class),
            $this->manager->getClassMetadata(Manager::class),
            $this->manager->getClassMetadata(Project::class),
            $this->manager->getClassMetadata(ProjectDeveloper::class),
            $this->manager->getClassMetadata(Task::class),
            $this->manager->getClassMetadata(Type::class),
            $this->manager->getClassMetadata(Admin::class),
        );
        $tool->updateSchema($classes);

        $projectType1=new Type();
        $projectType1->setName("Web Development");
        $projectType2=new Type();
        $projectType2->setName("Game Development");
        $this->manager->persist($projectType1);
        $this->manager->persist($projectType2);

        $developer1 = new Developer();
        $developer1->setName('Harnos Adrián');
        $developer1->setEmail('harnos.adrian@gmail.com');
        $this->manager->persist($developer1);

        $developer2 = new Developer();
        $developer2->setName('Dömök Martin');
        $developer2->setEmail('martin.domok2002@gmail.com');
        $this->manager->persist($developer2);

        $manager1 = new Manager();
        $manager1->setName('Heller Benedek');
        $manager1->setEmail('heller.benedek@gmail.com');
        $manager1->setPassword('6bbaaeb9febabd5f14ee0b8f769ab069a9f4eecb23db563fd3baa07611b4399a');
        $this->manager->persist($manager1);

        $manager2 = new Manager();
        $manager2->setName('Ferencz Kristóf');
        $manager2->setEmail('ferencz.kristof@gmail.com');
        $manager2->setPassword('e59ccefccb0aba4ded85708549bede11fd5cc22ec47c065a914eb26deb4c9fa5');
        $this->manager->persist($manager2);

        $project1 = new Project();
        $project1->setName('Platformer x');
        $project1->setType($projectType2);
        $project1->setDescription('The best platformer game ever');
        $this->manager->persist($project1);

        $project2 = new Project();
        $project2->setName('Vanenet.hu');
        $project2->setType($projectType1);
        $project2->setDescription('Checks if you have internet connection');
        $this->manager->persist($project2);

        $projectDeveloper1 = new ProjectDeveloper();
        $projectDeveloper1->setDeveloper($developer1);
        $projectDeveloper1->setProject($project1);
        $this->manager->persist($projectDeveloper1);

        $projectDeveloper2 = new ProjectDeveloper();
        $projectDeveloper2->setDeveloper($developer2);
        $projectDeveloper2->setProject($project2);
        $this->manager->persist($projectDeveloper2);

        $task1 = new Task();
        $task1->setName('Test automatization');
        $task1->setDescription('auto tests');
        $task1->setProject($project1);
        $task1->setUser($manager1);
        $task1->setDeadline(new \DateTime('2024-04-25 00:00:00'));
        $this->manager->persist($task1);

        $task2 = new Task();
        $task2->setName('Backend');
        $task2->setDescription('Backend with php');
        $task2->setProject($project2);
        $task2->setUser($manager1);
        $task2->setDeadline(new \DateTime('2024-04-30 00:00:00'));
        $this->manager->persist($task2);

        $task3 = new Task();
        $task3->setName('Frontend');
        $task3->setDescription('Frontend for the website');
        $task3->setProject($project2);
        $task3->setUser($manager2);
        $task3->setDeadline(new \DateTime('2024-05-12 00:00:00'));
        $this->manager->persist($task3);

        $admin1=new Admin();
        $admin1->setName('Admin Isztrátor');
        $admin1->setEmail('admin@admin.hu');
        $admin1->setPassword('b7757eeba8adb25e0145e3300ba9d8e09978ee5e90825e7b8776104dbabfcd3d');
        $this->manager->persist($admin1);

        $this->manager->flush();

        $array=["valasz"=>"Sikeres adatbázis létrehozás!"];
        return $array;
    }
}
