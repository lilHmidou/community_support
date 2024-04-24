<?php

namespace App\Service\EmailService;

use App\Entity\EmailLog;
use App\Entity\User;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailServiceImpl implements EmailServiceInterface
{
    public MailerInterface $mailer;
    private EntityManagerInterface $entityManager;
    private PostRepository $postRepository;

    public function __construct(MailerInterface $mailer, EntityManagerInterface $entityManager, PostRepository $postRepository)
    {
        $this->mailer = $mailer;
        $this->entityManager = $entityManager;
        $this->postRepository = $postRepository;
    }

    /**
     * Sends an email and logs the action in the database.
     *
     * @throws TransportExceptionInterface if there is an error sending the email.
     */
    public function sendEmail(User $sender, User $recipient, string $subject, string $htmlContent): void
    {
        try {
            $email = (new Email())
                ->from($sender->getEmail())
                ->to($recipient->getEmail())
                ->subject($subject)
                ->html($htmlContent);

            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            // Log the error or handle it according to your needs
            throw new \Exception('Failed to send email: ' . $e->getMessage());
        }

        $log = new EmailLog();
        $log->setSender($sender);
        $log->setRecipient($recipient);
        $log->setMessage($htmlContent);
        $log->setSenderEmail($sender->getEmail());
        $log->setRecipientEmail($recipient->getEmail());
        $this->entityManager->persist($log);
        $this->entityManager->flush();
    }

    public function sendParticipateEmail(User $participe): Response
    {
        $email = (new Email())
            ->from('cs.assitance.13579@gmail.com')
            ->to($participe->getEmail())
            ->subject('Your participation at the Event')
            ->text('Hello, thank you for participating in the event.');

        try {
            $this->mailer->send($email);
            $response = 'Email sent successfully';
        } catch (\Exception $e) {
            $response = 'Failed to send email: ' . $e->getMessage();
        }

        return new Response($response);
    }


    #Create a method who get the User of the post
    public function findUserByPostId(int $postId): ?User
    {
        return $this->postRepository->findUserByPostId($postId);
    }

}
