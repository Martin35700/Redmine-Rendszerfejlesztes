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
#[Table('project_developers')]
class ProjectDeveloper
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;


    #[ManyToOne(targetEntity: Developer::class, inversedBy: 'developerProjects')]
    #[JoinColumn(name: 'developer_id', referencedColumnName: 'id')]
    private $developer;

    #[ManyToOne(targetEntity: Project::class, inversedBy: 'projectDevelopers')]
    #[JoinColumn(name: 'project_id', referencedColumnName: 'id')]
    private $project;

    /**
     * @return Developer
     */
    public function getDeveloper(): Developer
    {
        return $this->developer;
    }

    /**
     * @param Developer $developer
     */
    public function setDeveloper(Developer $developer): void
    {
        $this->developer = $developer;
    }

    /**
     * @return Project
     */
    public function getProject(): Project
    {
        return $this->project;
    }

    /**
     * @param Project $project
     */
    public function setProject(Project $project): void
    {
        $this->project = $project;
    }

    public function __construct() {
        $this->developer = new Developer();
        $this->project = new Project();
    }

    public function expose() {
        // return get_object_vars($this);
        $array=[
            "id"=>$this->id,
            "developer"=>$this->developer->expose(),
            "project"=>$this->project->expose()
        ];
        return $array;
    }
}
?>