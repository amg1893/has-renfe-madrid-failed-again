<?php

namespace App\DataFixtures;

use App\Entity\HashtagStatus;
use App\Entity\Latest;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class BasicFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $latest = new Latest();
        $latest->setDateTweet('Mon Oct 08 10:00:00 +0000 2018');
        $latest->setLastId('1049225681241034752');

        $manager->persist($latest);

        $lineData = [
            [
                'name' => 'C1',
                'details' => 'Príncipe Pío – Atocha- Recoletos - Chamartín - Aeropuerto T4',
                'hashtag' => 'MadC1'
            ],
            [
                'name' => 'C2',
                'details' => 'Guadalajara – Alcalá de Henares – Atocha – Chamartín',
                'hashtag' => 'MadC2'
            ],
            [
                'name' => 'C3',
                'details' => 'Aranjuez – Atocha – Sol- Chamartín – El Escorial',
                'hashtag' => 'MadC3'
            ],
            [
                'name' => 'C4',
                'details' => 'Parla – Atocha - Sol - Chamartín – Cantoblanco – Alcobendas/San Sebastián de los Reyes o Colmenar Viejo',
                'hashtag' => 'MadC4'
            ],
            [
                'name' => 'C5',
                'details' => 'Móstoles El Soto – Atocha – Fuenlabrada - Humanes',
                'hashtag' => 'MadC5'
            ],
            [
                'name' => 'C7',
                'details' => 'Alcalá de Henares – Atocha – Chamartín – P. Pío',
                'hashtag' => 'MadC7'
            ],
            [
                'name' => 'C8',
                'details' => 'Chamartín – Villalba - Cercedilla',
                'hashtag' => 'MadC8'
            ],
            [
                'name' => 'C9',
                'details' => 'Cercedilla – Cotos',
                'hashtag' => 'MadC9'
            ],
            [
                'name' => 'C10',
                'details' => 'Villalba – Príncipe Pío – Atocha – Recoletos - Chamartín - Aeropuerto T4',
                'hashtag' => 'MadC10'
            ],
        ];

        foreach ($lineData as $data) {
            $hashtagStatus = new HashtagStatus();
            $hashtagStatus->setName($data['name']);
            $hashtagStatus->setDetails($data['details']);
            $hashtagStatus->setName($data['name']);

            $manager->persist($hashtagStatus);
        }

        $manager->flush();
    }
}
