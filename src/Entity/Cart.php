<?php

namespace App\Entity;

use App\Repository\CartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartRepository::class)]
class Cart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'cart', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $username = null;


    #[ORM\OneToMany(mappedBy: 'cart', targetEntity: CartItem::class, cascade: ['persist', 'remove'])]
    private Collection $cartItems;

    public function __construct()
    {
        $this->cartItems = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?User
    {
        return $this->username;
    }

    public function setUsername(User $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, CartItem>
     */
    public function getCartItems(): Collection
    {
        return $this->cartItems;
    }

    public function addCartItem(CartItem $cartItem): static
    {
        if (!$this->cartItems->contains($cartItem)) {
            $this->cartItems->add($cartItem);
            $cartItem->setCart($this);
        }

        return $this;
    }

    public function removeCartItem(CartItem $cartItem): static
    {
        if ($this->cartItems->contains($cartItem)) {
            $this->cartItems->removeElement($cartItem);
            $cartItem->setCart(null); // Clear reference to the cart
        }

        return $this;
    }

    public function __toString(): string
    {
        return 'Cart #' . $this->id; // Customize this to your desired string representation
    }
    public function getFormattedCartItems(): string
    {
        $formattedItems = [];

        /** @var CartItem $cartItem */
        foreach ($this->cartItems as $cartItem) {
            $formattedItems[] = sprintf(
                '%s (Quantity: %d)',
                $cartItem->getProducts()->getProductName(),
                $cartItem->getQuantity()
            );
        }

        return implode(', ', $formattedItems);
    }
}
