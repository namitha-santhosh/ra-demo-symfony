<?php

namespace App\Entity;

use App\Repository\CartItemRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CartItemRepository::class)]
class CartItem
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Cart::class, inversedBy: 'cartItems', cascade: ['persist', 'remove'])]
    private ?Cart $cart = null;

    #[ORM\ManyToOne(targetEntity: Products::class, inversedBy: 'cartItems', cascade: ['persist'])]
    private ?Products $products = null;

    #[ORM\Column]
    private ?int $quantity = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCart(): ?Cart
    {
        return $this->cart;
    }

    public function setCart(?Cart $cart): static
    {
        $this->cart = $cart;

        return $this;
    }

    public function getProducts(): ?Products
    {
        return $this->products;
    }

    public function setProducts(?Products $products): static
    {
        $this->products = $products;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function __toString(): string
    {
        return 'CartItem #' . $this->getId(); // Provide an appropriate representation
    }

    #[ORM\PreRemove]
    public function preRemove()
    {
        if ($this->cart !== null) {
            $this->cart->removeCartItem($this); // Ensure bidirectional relationship is handled
        }
    }
}
