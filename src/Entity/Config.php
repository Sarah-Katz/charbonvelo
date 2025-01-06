<?php

namespace App\Entity;

use App\Repository\ConfigRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ConfigRepository::class)]
class Config
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $logoText = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $block1Title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $block1Text = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $block2Title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $block2Text = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $block3Title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $block3Text = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $footerText = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footerLink1 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footerLink1Label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footerLink2 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footerLink2Label = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footerLink3 = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $footerLink3Label = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogoText(): ?string
    {
        return $this->logoText;
    }

    public function setLogoText(string $logoText): static
    {
        $this->logoText = $logoText;

        return $this;
    }

    public function getBlock1Title(): ?string
    {
        return $this->block1Title;
    }

    public function setBlock1Title(?string $block1Title): static
    {
        $this->block1Title = $block1Title;

        return $this;
    }

    public function getBlock1Text(): ?string
    {
        return $this->block1Text;
    }

    public function setBlock1Text(?string $block1Text): static
    {
        $this->block1Text = $block1Text;

        return $this;
    }

    public function getBlock2Title(): ?string
    {
        return $this->block2Title;
    }

    public function setBlock2Title(?string $block2Title): static
    {
        $this->block2Title = $block2Title;

        return $this;
    }

    public function getBlock2Text(): ?string
    {
        return $this->block2Text;
    }

    public function setBlock2Text(?string $block2Text): static
    {
        $this->block2Text = $block2Text;

        return $this;
    }

    public function getBlock3Title(): ?string
    {
        return $this->block3Title;
    }

    public function setBlock3Title(?string $block3Title): static
    {
        $this->block3Title = $block3Title;

        return $this;
    }

    public function getBlock3Text(): ?string
    {
        return $this->block3Text;
    }

    public function setBlock3Text(?string $block3Text): static
    {
        $this->block3Text = $block3Text;

        return $this;
    }

    public function getFooterText(): ?string
    {
        return $this->footerText;
    }

    public function setFooterText(?string $footerText): static
    {
        $this->footerText = $footerText;

        return $this;
    }

    public function getFooterLink1(): ?string
    {
        return $this->footerLink1;
    }

    public function setFooterLink1(?string $footerLink1): static
    {
        $this->footerLink1 = $footerLink1;

        return $this;
    }

    public function getFooterLink1Label(): ?string
    {
        return $this->footerLink1Label;
    }

    public function setFooterLink1Label(?string $footerLink1Label): static
    {
        $this->footerLink1Label = $footerLink1Label;

        return $this;
    }

    public function getFooterLink2(): ?string
    {
        return $this->footerLink2;
    }

    public function setFooterLink2(?string $footerLink2): static
    {
        $this->footerLink2 = $footerLink2;

        return $this;
    }

    public function getFooterLink2Label(): ?string
    {
        return $this->footerLink2Label;
    }

    public function setFooterLink2Label(?string $footerLink2Label): static
    {
        $this->footerLink2Label = $footerLink2Label;

        return $this;
    }

    public function getFooterLink3(): ?string
    {
        return $this->footerLink3;
    }

    public function setFooterLink3(?string $footerLink3): static
    {
        $this->footerLink3 = $footerLink3;

        return $this;
    }

    public function getFooterLink3Label(): ?string
    {
        return $this->footerLink3Label;
    }

    public function setFooterLink3Label(?string $footerLink3Label): static
    {
        $this->footerLink3Label = $footerLink3Label;

        return $this;
    }
}
