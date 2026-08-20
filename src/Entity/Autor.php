<?php

namespace App\Entity;

use App\Repository\AutorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AutorRepository::class)]
#[ORM\Table(name: 'Autor')]
class Autor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'CodAu', type: 'integer')]
    private ?int $CodAu = null;

    #[ORM\Column(name: 'Nome', length: 40)]
    private ?string $Nome = null;

    #[ORM\ManyToMany(
        targetEntity: Livro::class,
        mappedBy: 'autores'
    )]
    private Collection $livros;

    public function __construct()
    {
        $this->livros = new ArrayCollection();
    }

    public function getCodAu(): ?int
    {
        return $this->CodAu;
    }

    public function setCodAu(int $CodAu): static
    {
        $this->CodAu = $CodAu;

        return $this;
    }

    public function getNome(): ?string
    {
        return $this->Nome;
    }

    public function setNome(string $Nome): static
    {
        $this->Nome = $Nome;

        return $this;
    }
}
