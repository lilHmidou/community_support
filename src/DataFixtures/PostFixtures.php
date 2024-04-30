<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;

class PostFixtures extends Fixture
{


    public function load(ObjectManager $manager): void
    {
        $userRepository = $manager->getRepository(User::class);

        $user = $userRepository->findOneBy([]);
        $randomDate = new DateTimeImmutable('+'.rand(0, 30).' days');

        $post1 = new Post();
        $post1->setTitle("Collecte de denrées alimentaires");
        $post1->setDescription("Aidez-nous à collecter des denrées alimentaires pour les familles dans le besoin.");
        $post1->setCreatedAtPostValue(new \DateTimeImmutable());
        $post1->setLocation("Lille");
        $post1->setCategory("Alimentaire");
        $post1->setLike(20);
        $post1->setUser($user);
        $post1->setEventDate($randomDate);
        $post1->setMeetingAddress("10 rue des Oliviers");
        $post1->setStartTime("8h00");
        $manager->persist($post1);

        // Post 2
        $post2 = new Post();
        $post2->setTitle("Nettoyage communautaire");
        $post2->setDescription("Participez à notre événement de nettoyage communautaire dans le quartier.");
        $post2->setCreatedAtPostValue(new \DateTimeImmutable());
        $post2->setLocation("Marseille");
        $post2->setCategory("Communautaire");
        $post2->setLike(15);
        $post2->setUser($user);
        $post2->setEventDate($randomDate);
        $post2->setMeetingAddress("26 avenue du General De Gaulle");
        $post2->setStartTime("10h00");
        $manager->persist($post2);

        // Post 3
        $post3 = new Post();
        $post3->setTitle("Plantation d'arbres");
        $post3->setDescription("Joignez-vous à notre initiative de plantation d'arbres pour protéger l'environnement.");
        $post3->setCreatedAtPostValue(new \DateTimeImmutable());
        $post3->setLocation("Strasbourg");
        $post3->setCategory("Environnemental");
        $post3->setLike(30);
        $post3->setUser($user);
        $post3->setEventDate($randomDate);
        $post3->setMeetingAddress("3 boulevard des peupliers");
        $post3->setStartTime("14h00");
        $manager->persist($post3);

        // Post 4
        $post4 = new Post();
        $post4->setTitle("Campagne de sensibilisation");
        $post4->setDescription("Rejoignez notre campagne de sensibilisation sur le recyclage.");
        $post4->setCreatedAtPostValue(new \DateTimeImmutable());
        $post4->setLocation("Grenoble");
        $post4->setCategory("Sensibilisation");
        $post4->setLike(10);
        $post4->setUser($user);
        $post4->setEventDate($randomDate);
        $post4->setMeetingAddress("17 avenue de Lille");
        $post4->setStartTime("18h00");
        $manager->persist($post4);

        // Post 5
        $post5 = new Post();
        $post5->setTitle("Projet éducatif");
        $post5->setDescription("Contribuez à notre projet visant à sensibiliser les jeunes à l'importance de l'éducation.");
        $post5->setCreatedAtPostValue(new \DateTimeImmutable());
        $post5->setLocation("Paris");
        $post5->setCategory("Autre");
        $post5->setLike(25);
        $post5->setUser($user);
        $post5->setEventDate($randomDate);
        $post5->setMeetingAddress("34 rue des cheminées");
        $post5->setStartTime("12h00");
        $manager->persist($post5);

        $manager->flush();
    }
}