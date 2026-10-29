<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LocationRepository::class)]
class Location
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Name = null;

    #[ORM\Column(length: 2)]
    private ?string $Country = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $Latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7)]
    private ?string $Longitude = null;

    /**
     * @var Collection<int, Forecast>
     */
    #[ORM\OneToMany(targetEntity: Forecast::class, mappedBy: 'location')]
    private Collection $forecasts;

    public function __construct()
    {
        $this->forecasts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): static
    {
        $this->Name = $Name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->Country;
    }

    public function setCountry(string $Country): static
    {
        $this->Country = $Country;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->Latitude;
    }

    public function setLatitude(string $Latitude): static
    {
        $this->Latitude = $Latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->Longitude;
    }

    public function setLongitude(string $Longitude): static
    {
        $this->Longitude = $Longitude;

        return $this;
    }

    /**
     * @return Collection<int, Forecast>
     */
    public function getForecasts(): Collection
    {
        return $this->forecasts;
    }

    public function addForecast(Forecast $forecast): static
    {
        if (!$this->forecasts->contains($forecast)) {
            $this->forecasts->add($forecast);
            $forecast->setLocation($this);
        }

        return $this;
    }

    public function removeForecast(Forecast $forecast): static
    {
        if ($this->forecasts->removeElement($forecast)) {
            // set the owning side to null (unless already changed)
            if ($forecast->getLocation() === $this) {
                $forecast->setLocation(null);
            }
        }

        return $this;
    }
}
