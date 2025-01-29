<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\DeploymentRepository;


#[ORM\Entity(repositoryClass: DeploymentRepository::class)]
#[ORM\Table(name: 'deployments')]
class Deployment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $jobName;

    #[ORM\Column(type: 'json')]
    private $parameters;

    #[ORM\Column(type: 'string', length: 50)]
    private $status;

    #[ORM\Column(type: 'integer', nullable: true)]
    private $buildNumber;

    #[ORM\Column(type: 'datetime')]
    private $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updatedAt;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $triggeredBy;

    #[ORM\Column(type: 'string', length: 255)]
    private $releaseName;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->status = 'PENDING';
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJobName(): ?string
    {
        return $this->jobName;
    }
    
    public function setJobName(string $jobName): self
    {
        $this->jobName = $jobName;
        
        return $this;
    }

    public function getParameters(): ?array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters): self
    {
        $this->parameters = $parameters;
        
        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        
        return $this;
    }

    public function getBuildNumber(): ?int
    {
        return $this->buildNumber;
    }

    public function setBuildNumber(int $buildNumber): self
    {
        $this->buildNumber = $buildNumber;
        
        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }

    public function getTriggeredBy(): ?string
    {
        return $this->triggeredBy;
    }

    public function setTriggeredBy(string $triggeredBy): self
    {
        $this->triggeredBy = $triggeredBy;
        
        return $this;
    }

     public function getReleaseName(): ?string
     {
         return $this->releaseName;
     }
 
     public function setReleaseName(string $releaseName): self
     {
         $this->releaseName = $releaseName;
         return $this;
     }
}