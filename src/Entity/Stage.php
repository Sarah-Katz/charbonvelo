<?php

namespace App\Entity;

use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;
use App\Repository\StageRepository;
use Doctrine\DBAL\Types\DateImmutableType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[Vich\Uploadable]
#[ORM\Entity(repositoryClass: StageRepository::class)]
class Stage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(length: 150)]
    private ?string $title = null;

    #[Vich\UploadableField(mapping: "stage_gpx", fileNameProperty: "gpxLink")]
    private ?File $gpxFile = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $gpxLink = null;

    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getGpxLink(): ?string
    {
        return $this->gpxLink;
    }

    public function setGpxLink(string $gpxLink): static
    {
        $this->gpxLink = $gpxLink;

        return $this;
    }
    public function setGpxFile(?File $gpxFile): void
    {
        $this->gpxFile = $gpxFile;

        if ($gpxFile) {
            // Met à jour updatedAt si nécessaire pour déclencher l'upload
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    
    public function getGpxFile(): ?File
    {
        return $this->gpxFile;
    }
}
