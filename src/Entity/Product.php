<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ProductRepository::class)
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="nom produit doit etre non vide")
     * @Assert\Length(
     *      min = 5,
     *      minMessage=" Entrer un nom au mini de 5 caracteres"
     *
     *     )
     * @ORM\Column(type="string", length=255)
     */
    private $Nom;

    /**
     * @Assert\NotBlank(message="prix produit doit etre non vide")
     * @Assert\Positive
     * @Assert\Range(
     *      min = 3,
     *      max = 1000,
     *      notInRangeMessage = "le prix doit etre valid",
     *     )
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @Assert\NotBlank(message="description produit doit etre non vide")
     * @Assert\Length(
     *      min = 7,
     *      max = 100,
     *      minMessage = "doit etre >=7 ",
     *      maxMessage = "doit etre <=100" )
     * @ORM\Column(type="string", length=1000)
     */
    private $description;

    /**
     * @Assert\NotBlank(message="couleur produit doit etre non vide")
     * @ORM\Column(type="string", length=255)
     */
    private $coleur;
    /**
     * @ORM\Column(type="datetime",nullable=true)
     */
    private $dateCreation;




    /**
     * @ORM\Column(type="string", length=500,nullable=true)
     */
    private $image;


    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;


    /**
     * @ORM\Column(type="boolean", length=500,nullable=true)
     */
    private $etat;

    /**
     * @ORM\Column(type="integer",nullable=true)
     */
    private $note;




    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="category_id")
     */
    private $category;

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category): void
    {
        $this->category = $category;
    }






    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): self
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getColeur()
    {
        return $this->coleur;
    }

    /**
     * @param mixed $coleur
     */
    public function setColeur($coleur): void
    {
        $this->coleur = $coleur;
    }

    /**
     * @return mixed
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param mixed $image
     */
    public function setImage($image): void
    {
        $this->image = $image;
    }

    //UPLOAD IMAGE
    //GeT PUBLIC FOLDER

    //UPLOAD IMAGE
    //GeT PUBLIC FOLDER
    public function getPublicFolder() {

        return __DIR__.'/../../public/uploads/produit_image';

    }

    /**
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param UploadedFile
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }


    //te5o image w tzidha fi dossier produit_image
    public function upload()
    {
        if(null === $this->getFile()) {
            return;
        }

        $this->getFile()->move(
            $this->getPublicFolder(),//destinataire
            $this->getFile()->getClientOriginalName()//esem fichier (image)
        );

        $this->image = $this->getFile()->getClientOriginalName();//

        $this->file = null; // liberation memoire
    }

    /**
     * @return mixed
     */
    public function getEtat()
    {
        return $this->etat;
    }

    /**
     * @param mixed $etat
     */
    public function setEtat($etat): void
    {
        $this->etat = $etat;
    }

    /**
     * @return mixed
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param mixed $note
     */
    public function setNote($note): void
    {
        $this->note = $note;
    }







}
