<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(
    fields: ['email'],
    message: 'l\'email existe déjà .',
    groups:['register'],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
     #[Assert\Email(
         message: 'l\'email {{ value }} n\'est pas valide.',
        groups:['register'],
    )]
    #[Assert\NotBlank(message:'Vous devez entrez un email')]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message:'Enter a valid password, please.', groups: ['register'])]
    #[Assert\Regex(
        pattern: '#(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}#',
        match: true,
        message: 'Your Password need to be at least 8 characters long.',
    )]

    private ?string $password = null;
     #[Assert\EqualTo(
         propertyPath: 'password',
         message: 'The passwords don\'t match',
         groups: ['register']
     )]
    public $confirmPassword;
    
    private ?string $oldPassword;
    #[Assert\NotBlank(message:'Enter a valid password, please.')] 
    #[Assert\Regex(
        pattern: '#(?=.*[0-9])(?=.*[a-z])(?=.*[A-Z]).{8,}#',
        match: true,
        message: 'Your Password need to be at least 8 characters long.',
    )]

    private $newPassword;
    #[Assert\EqualTo(
        propertyPath : 'newPassword',
        message :'les deux mots de passe ne sont pas identiques '
    )]
    
    private $confirmNewPassword;



    #[ORM\Column(length: 255)]
    
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your first name must be at least {{ limit }} characters long',
        maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
        groups: ['register']
    )]
    private ?string $firstName = null;


    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 2,
        max: 50,
        minMessage: 'Your last name must be at least {{ limit }} characters long',
        maxMessage: 'Your last name cannot be longer than {{ limit }} characters',
        groups: ['register']
    )]
    private ?string $lastName = null;


    #[ORM\Column]
    #[Assert\Date]
    private ?\DateTimeImmutable $birthDay = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    // public function getBirthDay(): ?\DateTimeImmutable
    // {
    //     return $this->birthDay;
    // }

    // public function setBirthDay(\DateTimeImmutable $birthDay): static
    // {
    //     $this->birthDay = $birthDay;

    //     return $this;
    // }

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function getNewPassword()
    {
        return $this->newPassword;
    }
    
        public function setNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * Get the value of confirmPassword
     */ 
    public function getConfirmPassword()
    {
        return $this->confirmPassword;
    }

    /**
     * Set the value of confirmPassword
     *
     * @return  self
     */ 
    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = $confirmPassword;

        return $this;
    }

    /**
     * Get the value of confirmNewPassword
     */ 
    public function getConfirmNewPassword()
    {
        return $this->confirmNewPassword;
    }

    /**
     * Set the value of confirmNewPassword
     *
     * @return  self
     */ 
    public function setConfirmNewPassword($confirmNewPassword)
    {
        $this->confirmNewPassword = $confirmNewPassword;

        return $this;
    }
}
