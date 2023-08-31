<?php

namespace App\Entity;

use App\Entity\Sklbl\SklblLogs;
use App\Entity\Trait\CreatedAtTrait;
use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UsersRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    use CreatedAtTrait;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $lastname = null;

    #[ORM\Column(length: 100)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(length: 5)]
    private ?string $zipcode = null;

    #[ORM\Column(length: 150)]
    private ?string $city = null;

    #[ORM\Column(type: 'boolean')]
    private $is_verified = false;

    #[ORM\Column(length: 100)]
    private ?string $resetToken = null;
 

    #[ORM\OneToMany(mappedBy: 'checked_by', targetEntity: QualityCtrl::class)]
    private Collection $qualityCtrls;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: SklblLogs::class)]
    private Collection $sklblLogs;

    public function __construct()
    {

        $this->created_at = new \DateTimeImmutable();
        $this->qualityCtrls = new ArrayCollection();
        $this->sklblLogs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getIsVerified(): ?bool
    {
        return $this->is_verified;
    }

    public function setIsVerified(bool $is_verified): self
    {
        $this->is_verified = $is_verified;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->resetToken;
    }

    public function setResetToken(string $resetToken): self
    {
        $this->resetToken = $resetToken;
        return $this;
    }

    

    /**
     * @return Collection<int, QualityCtrl>
     */
    public function getQualityCtrls(): Collection
    {
        return $this->qualityCtrls;
    }

    public function addQualityCtrl(QualityCtrl $qualityCtrl): static
    {
        if (!$this->qualityCtrls->contains($qualityCtrl)) {
            $this->qualityCtrls->add($qualityCtrl);
            $qualityCtrl->setCheckedBy($this);
        }

        return $this;
    }

    public function removeQualityCtrl(QualityCtrl $qualityCtrl): static
    {
        if ($this->qualityCtrls->removeElement($qualityCtrl)) {
            // set the owning side to null (unless already changed)
            if ($qualityCtrl->getCheckedBy() === $this) {
                $qualityCtrl->setCheckedBy(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, SklblLogs>
     */
    public function getSklblLogs(): Collection
    {
        return $this->sklblLogs;
    }

    public function addSklblLog(SklblLogs $sklblLog): static
    {
        if (!$this->sklblLogs->contains($sklblLog)) {
            $this->sklblLogs->add($sklblLog);
            $sklblLog->setUser($this);
        }

        return $this;
    }

    public function removeSklblLog(SklblLogs $sklblLog): static
    {
        if ($this->sklblLogs->removeElement($sklblLog)) {
            // set the owning side to null (unless already changed)
            if ($sklblLog->getUser() === $this) {
                $sklblLog->setUser(null);
            }
        }

        return $this;
    }
}
