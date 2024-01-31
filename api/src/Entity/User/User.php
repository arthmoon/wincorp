<?php

declare(strict_types=1);

namespace App\Entity\User;

use App\Entity\User\Token;
use App\Entity\User\Id;
use App\Entity\Wallet\Wallet;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use DomainException;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: 'users')]
final class User
{
    #[ORM\Column(type: 'user_id')]
    #[ORM\Id]
    private Id $id;

    #[ORM\OneToOne(targetEntity: Wallet::class)]
    #[ORM\JoinColumn(name: 'wallet_id', referencedColumnName: 'id')]
    private Wallet|null $wallet = null;

    #[ORM\Embedded(class: Token::class)]
    private ?Token $apiToken = null;

    public function __construct(Id $id, Token $apiToken)
    {
        $this->id = $id;
        $this->apiToken = $apiToken;
    }

    public function getId(): Id
    {
        return $this->id;
    }

    public function getApiToken(): ?Token
    {
        return $this->apiToken;
    }

    #[ORM\PostLoad]
    public function checkEmbeds(): void
    {
        if ($this->apiToken && $this->apiToken->isEmpty()) {
            $this->apiToken = null;
        }
    }

    public function setWallet(?Wallet $wallet): void
    {
        $this->wallet = $wallet;
    }

}
