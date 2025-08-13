<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Table(name: '`order`')]
class Order
{
    use Timestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[Groups(groups: ['order:full'])]
    private ?User $business = null;

    #[ORM\Column]
    #[Groups(groups: ['order:full'])]
    private ?float $total = null;

    #[ORM\Column(length: 50)]
    #[Groups(groups: ['order:full'])]
    private ?string $status = null;

    /**
     * @var Collection<int, OrderDetail>
     */
    #[ORM\OneToMany(targetEntity: OrderDetail::class, mappedBy: 'orderId',cascade: ['persist','remove'],orphanRemoval: true)]
    #[Groups(groups: ['order:full'])]
    private Collection $orderDetails;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['order:full'])]
    private ?string $customer = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['order:full'])]
    private ?string $customer_email = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['order:full'])]
    private ?string $customer_phone = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['order:full'])]
    private ?string $customer_address = null;

    /**
     * @var Collection<int, BackOrder>
     */
    #[ORM\OneToMany(targetEntity: BackOrder::class, mappedBy: 'orderId')]
    private Collection $backOrders;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['order:full'])]
    private ?string $recipient = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['order:full'])]
    private ?string $recipientPhone = null;

    #[ORM\Column(length: 255)]
    #[Groups(groups: ['order:full'])]
    private ?string $recipientAddress = null;

    #[ORM\Column(length: 50)]
    #[Groups(groups: ['order:full'])]
    private ?string $recipientProvince = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(groups: ['order:full'])]
    private ?string $notes = null;

    public function __construct(
        string $customer,
        string $customer_email,
        string $customer_phone,
        string $customer_address,
        string $status,
        string $recipient,
        string $recipientPhone,
        string $recipientAddress,
        string $recipientProvince,
        string $notes

    ) {
        $this->setCustomer($customer);
        $this->setCustomerEmail($customer_email);
        $this->setCustomerPhone($customer_phone);
        $this->setCustomerAddress($customer_address);
        $this->setStatus($status);
        $this->setRecipient($recipient);
        $this->setRecipientPhone($recipientPhone);
        $this->setRecipientAddress($recipientAddress);
        $this->setRecipientProvince($recipientProvince);
        $this->setNotes($notes);

        $this->orderDetails = new ArrayCollection();
        $this->backOrders = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBusiness(): ?User
    {
        return $this->business;
    }

    public function setBusiness(?User $business): static
    {
        $this->business = $business;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(float $total): static
    {
        $this->total = $total;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, OrderDetail>
     */
    public function getOrderDetails(): Collection
    {
        return $this->orderDetails;
    }

    public function addOrderDetail(OrderDetail $orderDetail): static
    {
        if (!$this->orderDetails->contains($orderDetail)) {
            $this->orderDetails->add($orderDetail);
            $orderDetail->setOrderId($this);
        }

        return $this;
    }

    public function removeOrderDetail(OrderDetail $orderDetail): static
    {
        if ($this->orderDetails->removeElement($orderDetail)) {
            if ($orderDetail->getOrderId() === $this) {
                $orderDetail->setOrderId(null);
            }
        }

        return $this;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function setCustomer(string $customer): static
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customer_email;
    }

    public function setCustomerEmail(string $customer_email): static
    {
        $this->customer_email = $customer_email;

        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->customer_phone;
    }

    public function setCustomerPhone(string $customer_phone): static
    {
        $this->customer_phone = $customer_phone;

        return $this;
    }

    public function getCustomerAddress(): ?string
    {
        return $this->customer_address;
    }

    public function setCustomerAddress(string $customer_address): static
    {
        $this->customer_address = $customer_address;

        return $this;
    }

    /**
     * @return Collection<int, BackOrder>
     */
    public function getBackOrders(): Collection
    {
        return $this->backOrders;
    }

    public function addBackOrder(BackOrder $backOrder): static
    {
        if (!$this->backOrders->contains($backOrder)) {
            $this->backOrders->add($backOrder);
            $backOrder->setOrderId($this);
        }

        return $this;
    }

    public function removeBackOrder(BackOrder $backOrder): static
    {
        if ($this->backOrders->removeElement($backOrder)) {
            // set the owning side to null (unless already changed)
            if ($backOrder->getOrderId() === $this) {
                $backOrder->setOrderId(null);
            }
        }

        return $this;
    }

    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    public function setRecipient(string $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getRecipientPhone(): ?string
    {
        return $this->recipientPhone;
    }

    public function setRecipientPhone(string $recipientPhone): static
    {
        $this->recipientPhone = $recipientPhone;

        return $this;
    }

    public function getRecipientAddress(): ?string
    {
        return $this->recipientAddress;
    }

    public function setRecipientAddress(string $recipientAddress): static
    {
        $this->recipientAddress = $recipientAddress;

        return $this;
    }

    public function getRecipientProvince(): ?string
    {
        return $this->recipientProvince;
    }

    public function setRecipientProvince(string $recipientProvince): static
    {
        $this->recipientProvince = $recipientProvince;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $notes): static
    {
        $this->notes = $notes;

        return $this;
    }
}
