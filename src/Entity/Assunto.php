<?php

namespace App\Entity;

use App\Repository\AssuntoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssuntoRepository::class)]
#[ORM\Table(name: 'Assunto')]
class Assunto
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'codAs', type: 'integer')]
    private ?int $codAs = null;

    #[ORM\Column(name: 'Descricao', length: 20)]
    private ?string $Descricao = null;

    #[ORM\ManyToMany(
        targetEntity: Livro::class,
        mappedBy: 'assuntos'
    )]
    private Collection $livros;

    public function __construct()
    {
        $this->livros = new ArrayCollection();
    }

    public function getCodAs(): ?int
    {
        return $this->CodAs;
    }

    public function setCodAs(int $CodAs): static
    {
        $this->CodAs = $CodAs;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->Descricao;
    }

    public function setDescricao(string $Descricao): static
    {
        $this->Descricao = $Descricao;

        return $this;
    }
}
