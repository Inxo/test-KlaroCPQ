<?php

namespace App\Entity;

use App\Repository\SuperDataRepository;
use App\Service\DataInterface;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;

#[ORM\Entity(repositoryClass: SuperDataRepository::class)]
class SuperData implements JsonSerializable, DataInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'datetime_immutable')]
    private DateTimeImmutable $created_at;

    #[ORM\Column(type: 'json')]
    private array $data = [];

    #[ORM\Column(type: 'json')]
    private array $data_modify = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function setData(array $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getDataModify(): ?array
    {
        return $this->data_modify;
    }

    public function setDataModify(array $data): self
    {
        $this->data_modify = $data;

        return $this;
    }

    public function setTimestamp(): void
    {
        $this->created_at = new DateTimeImmutable();
    }

    #[ArrayShape(['id' => "int", 'created_at' => "\DateTimeImmutable", 'old_data' => "array", 'new_data' => "array"])]
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'created_at' => $this->created_at,
            'old_data' => $this->data,
            'new_data' => $this->data_modify
        ];
    }
}
