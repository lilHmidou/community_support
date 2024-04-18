<?php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EmailLogRepository;

#[ORM\Entity(repositoryClass: EmailLogRepository::class)]
#[ORM\Table(name: 'email_log')]
class EmailLog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $message = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "sender_id", referencedColumnName: "id")]
    private ?User $sender = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: "recipient_id", referencedColumnName: "id")]
    private ?User $recipient = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private $senderEmail;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private $recipientEmail;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    public function getSender(): ?User
    {
        return $this->sender;
    }

    public function setSender(?User $sender): self
    {
        $this->sender = $sender;
        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSenderEmail()
    {
        return $this->senderEmail;
    }

    /**
     * @param mixed $senderEmail
     */
    public function setSenderEmail($senderEmail): void
    {
        $this->senderEmail = $senderEmail;
    }

    /**
     * @return mixed
     */
    public function getRecipientEmail()
    {
        return $this->recipientEmail;
    }

    /**
     * @param mixed $recipientEmail
     */
    public function setRecipientEmail($recipientEmail): void
    {
        $this->recipientEmail = $recipientEmail;
    }

}
