<?php

namespace Redmine\Entity;

use Doctrine\ORM\Mapping\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('developers')]
class Developer
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column]
    private string $email;

    #[OneToMany(targetEntity: ProjectDeveloper::class, mappedBy: 'developer')]
    private $developerProjects;
    
    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getDeveloperProjects(): ArrayCollection
    {
        return $this->developerProjects;
    }

    public function addDeveloperProject(ProjectDeveloper $projectDeveloper): void
    {
        $this->developerProjects->add($projectDeveloper);
    }

    public function removeDeveloperProject(ProjectDeveloper $projectDeveloper): void
    {
        $this->developerProjects->removeElement($projectDeveloper);
    }

    public function __construct() {
        $this->developerProjects = new ArrayCollection();
    }

    public function expose() {
        //return get_object_vars($this);
        if(!empty($this->developerProjects)) {
            $projects = [];
            foreach($this->developerProjects as $project) {
                $projects[] = $project->getProject()->expose();
            }
            $array=[
                "id"=>$this->id,
                "name"=>$this->name,
                "email"=>$this->email,
                "projects"=>$projects
            ];
        }
        else
        {
            $array=[
                "id"=>$this->id,
                "name"=>$this->name,
                "email"=>$this->email,
            ];
        }
        return $array;
    }
}
?>