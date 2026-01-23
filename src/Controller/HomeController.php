<?php

namespace App\Controller;

use App\Form\ContactFormType;
use App\Repository\EventRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function home(): Response
    {
        $instaImages = [
            'insta-post-1.png',
            'insta-post-2.png',
            'insta-post-3.png',
            'insta-post-4.png',
            'insta-post-5.png',
            'insta-post-6.png',
            'insta-post-7.png',
        ];

        return $this->render('pages/home.html.twig', [
            'instaImages' => $instaImages,
        ]);
    }

    #[Route('/debarrassage', name: 'debarrassage')]
    public function debarrassage(): Response
    {
        return $this->render('pages/debarrassage.html.twig');
    }

    #[Route('/brocante', name: 'brocante')]
    public function brocante(EventRepository $eventRepository): Response
    {
        $today = new \DateTimeImmutable('today');
        $limit = $today->modify('+40 days');

        $allEvents = $eventRepository->findBy([], ['startAt' => 'ASC']);

        // Formatters
        $formatterDisplay = new \IntlDateFormatter(
            'fr_FR',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::NONE,
            null,
            null,
            'EEEE d MMMM' // e.g. "samedi 14 décembre"
        );

        $formatterMonthShort = new \IntlDateFormatter(
            'fr_FR',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            null,
            null,
            'MMM' // e.g. "déc."
        );

        $events = [];

        foreach ($allEvents as $event) {
            $start = $event->getStartAt();
            $end   = $event->getEndAt();


            // Filter: only today → +40 days
            if ($start < $today || $start > $limit) {
                continue;
            }

            // French formatting
            $displayDay = ucfirst($formatterDisplay->format($start));
            $monthShort = strtoupper($formatterMonthShort->format($start));

            // Calendar formatting
            $startUTC = $start->format('Ymd\THis\Z');
            $endUTC   = $end->format('Ymd\THis\Z');

            $googleMapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($event->getAddress());

            $googleCalendarUrl =
                'https://calendar.google.com/calendar/render?action=TEMPLATE' .
                '&text=' . urlencode($event->getTitle() . ' - Machine Vagabonde') .
                '&dates=' . $startUTC . '/' . $endUTC .
                '&details=' . urlencode($event->getSubtitle()) .
                '&location=' . urlencode($event->getAddress());

            $icsContent =
                "BEGIN:VCALENDAR\nVERSION:2.0\nBEGIN:VEVENT\n" .
                "DTSTART:$startUTC\nDTEND:$endUTC\n" .
                "SUMMARY:" . $event->getTitle() . "\n" .
                "DESCRIPTION:" . $event->getSubtitle() . "\n" .
                "LOCATION:" . $event->getAddress() . "\n" .
                "END:VEVENT\nEND:VCALENDAR";

            $events[] = [
                'entity' => $event,
                'monthShort' => $monthShort,
                'day' => $start->format('d'),
                'displayDay' => $displayDay,
                'googleUrl' => $googleCalendarUrl,
                'ics' => $icsContent,
                'mapsUrl' => $googleMapsUrl,
            ];
        }

        return $this->render('pages/brocante.html.twig', [
            'events' => $events,
            'instaImages' => [
                'insta-post-1.png',
                'insta-post-2.png',
                'insta-post-3.png',
                'insta-post-4.png',
                'insta-post-5.png',
                'insta-post-6.png',
                'insta-post-7.png',
            ],
        ]);
    }


    #[Route('/surcyclage', name: 'surcyclage')]
    public function surcyclage(): Response
    {
        return $this->render('pages/surcyclage.html.twig');
    }

    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $email = (new Email())
            ->from($data['email'])
            ->replyTo($data['email'])
            ->to('andrewsmithies.dev@gmail.com')
            ->subject('Nouveau message du formulaire de contact')
            ->text(
                "Prénom : {$data['firstname']}\n" .
                "Nom : {$data['lastname']}\n" .
                "Email : {$data['email']}\n" .
                "Téléphone : {$data['phone']}\n" .
                "Sujet : {$data['subject']}\n\n" .
                "Message : {$data['message']}\n"
            );

            $mailer->send($email);

            $this->addFlash('success', 'Votre message a bien été envoyé.');

            return $this->redirectToRoute('contact');
        }

        return $this->render('pages/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
