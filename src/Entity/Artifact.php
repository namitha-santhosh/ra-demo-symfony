<?php

namespace App\Entity;

use App\Repository\ArtifactRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ArtifactRepository::class)]
class Artifact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'artifact', targetEntity: ArtifactRelease::class, cascade: ['persist', 'remove'])]
    private Collection $artifactReleases;

    public function __construct()
    {
        $this->artifactReleases = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ArtifactRelease[]
     */
    public function getArtifactReleases(): Collection
    {
        return $this->artifactReleases;
    }

    public function addArtifactRelease(ArtifactRelease $artifactRelease): self
    {
        if (!$this->artifactReleases->contains($artifactRelease)) {
            $this->artifactReleases[] = $artifactRelease;
            $artifactRelease->setArtifact($this);
        }

        return $this;
    }

    public function removeArtifactRelease(ArtifactRelease $artifactRelease): self
    {
        if ($this->artifactReleases->removeElement($artifactRelease)) {
            // set the owning side to null (unless already changed)
            if ($artifactRelease->getArtifact() === $this) {
                $artifactRelease->setArtifact(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }
}
