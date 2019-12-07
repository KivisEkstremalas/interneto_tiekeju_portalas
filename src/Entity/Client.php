<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 */
class Client
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $surname;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Computer", mappedBy="client")
     */
    private $computers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Provider", inversedBy="clients")
     */
    private $provider;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", cascade={"persist", "remove"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Payment", mappedBy="client")
     */
    private $payments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Credit", mappedBy="client")
     */
    private $credits;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contractName;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $contractSigned;

    public function __construct()
    {
        $this->computers = new ArrayCollection();
        $this->payments = new ArrayCollection();
        $this->credits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return Collection|Computer[]
     */
    public function getComputers(): Collection
    {
        return $this->computers;
    }

    public function addComputer(Computer $computer): self
    {
        if (!$this->computers->contains($computer)) {
            $this->computers[] = $computer;
            $computer->setClient($this);
        }

        return $this;
    }

    public function removeComputer(Computer $computer): self
    {
        if ($this->computers->contains($computer)) {
            $this->computers->removeElement($computer);
            // set the owning side to null (unless already changed)
            if ($computer->getClient() === $this) {
                $computer->setClient(null);
            }
        }

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Payment[]
     */
    public function getPayments(): Collection
    {
        return $this->payments;
    }

    public function addPayment(Payment $payment): self
    {
        if (!$this->payments->contains($payment)) {
            $this->payments[] = $payment;
            $payment->setClient($this);
        }

        return $this;
    }

    public function removePayment(Payment $payment): self
    {
        if ($this->payments->contains($payment)) {
            $this->payments->removeElement($payment);
            // set the owning side to null (unless already changed)
            if ($payment->getClient() === $this) {
                $payment->setClient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Credit[]
     */
    public function getCredits(): Collection
    {
        return $this->credits;
    }

    public function addCredit(Credit $credit): self
    {
        if (!$this->credits->contains($credit)) {
            $this->credits[] = $credit;
            $credit->setClient($this);
        }

        return $this;
    }

    public function removeCredit(Credit $credit): self
    {
        if ($this->credits->contains($credit)) {
            $this->credits->removeElement($credit);
            // set the owning side to null (unless already changed)
            if ($credit->getClient() === $this) {
                $credit->setClient(null);
            }
        }

        return $this;
    }

    public function getContractName(): ?string
    {
        return $this->contractName;
    }

    public function setContractName(?string $contractName): self
    {
        $this->contractName = $contractName;

        return $this;
    }

    public function getContractSigned(): ?bool
    {
        return $this->contractSigned;
    }

    public function setContractSigned(?bool $contractSigned): self
    {
        $this->contractSigned = $contractSigned;

        return $this;
    }
}
