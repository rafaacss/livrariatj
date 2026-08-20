<?php

namespace App\Entity;

use App\Repository\LivroRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: LivroRepository::class)]
#[ORM\Table(name: 'Livro')]
class Livro
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'Codl', type: 'integer')]
    private ?int $Codl = null;

    #[ORM\ManyToMany(
        targetEntity: Assunto::class,
        inversedBy: 'livros'
    )]
    #[ORM\JoinTable(name: 'Livro_Assunto')]
    #[ORM\JoinColumn(
        name: 'Livro_Codl',
        referencedColumnName: 'Codl'
    )]
    #[ORM\InverseJoinColumn(
        name: 'Assunto_codAs',
        referencedColumnName: 'codAs'
    )]
    private Collection $assuntos;

    #[ORM\ManyToMany(
        targetEntity: Autor::class,
        inversedBy: 'livros'
    )]
    #[ORM\JoinTable(name: 'Livro_Autor')]
    #[ORM\JoinColumn(
        name: 'Livro_Codl',
        referencedColumnName: 'Codl'
    )]
    #[ORM\InverseJoinColumn(
        name: 'Autor_CodAu',
        referencedColumnName: 'CodAu'
    )]
    private Collection $autores;

    #[ORM\Column(name: 'Titulo', type: 'string', length: 40)]
    private ?string $Titulo = null;

    #[ORM\Column(name: 'Editora', length: 40)]
    private ?string $Editora = null;

    #[ORM\Column(name: 'Edicao', type: 'integer')]
    private ?int $Edicao = null;

    #[ORM\Column(name: 'AnoPublicacao', type: 'string' ,length: 4)]
    private ?string $AnoPublicacao = null;

    #[ORM\Column(name: 'Valor', type: 'decimal', precision: 10, scale: 2, nullable: true)]
    private ?string $Valor = null;

    public function __construct()
    {
        $this->assuntos = new ArrayCollection();
        $this->autores = new ArrayCollection();
    }

    public function getCodl(): ?int
    {
        return $this->Codl;
    }

    public function getTitulo(): ?string
    {
        return $this->Titulo;
    }

    public function setTitulo(string $Titulo): object
    {
        $this->Titulo = $Titulo;

        return $this;
    }

    public function getEditora(): ?string
    {
        return $this->Editora;
    }

    public function setEditora(string $Editora): object
    {
        $this->Editora = $Editora;

        return $this;
    }

    public function getEdicao(): ?int
    {
        return $this->Edicao;
    }

    public function setEdicao(int $Edicao): object
    {
        $this->Edicao = $Edicao;

        return $this;
    }

    public function getAnoPublicacao(): ?string
    {
        return $this->AnoPublicacao;
    }

    public function setAnoPublicacao(string $AnoPublicacao): object
    {
        $this->AnoPublicacao = $AnoPublicacao;

        return $this;
    }

    public function getValor(): ?string
    {
        return $this->Valor;
    }

    public function setValor(?string $Valor): void
    {
        $this->Valor = $Valor;
    }

    public function getAssuntos(): Collection
    {
        return $this->assuntos;
    }

    public function addAssunto(Assunto $assunto): void
    {
        if (!$this->assuntos->contains($assunto)) {
            $this->assuntos->add($assunto);
        }
    }

    public function removeAssunto(Assunto $assunto): static
    {
        $this->assuntos->removeElement($assunto);

        return $this;
    }

    public function getAutores(): Collection
    {
        return $this->autores;
    }

    public function addAutor(Autor $autor): void
    {
        if (!$this->autores->contains($autor)) {
            $this->autores->add($autor);
        }
    }

    public function removeAutor(Autor $autor): static
    {
        $this->autores->removeElement($autor);

        return $this;
    }


}
