<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\VoteType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VoteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('votes')->insert([
            [
                'title' => "Proposition d'aménagement d'un parc communautaire",
                'description' => "Lors de cette séance de vote, les résidents sont invités à exprimer leur avis sur la création d'un parc communautaire dans notre quartier. La proposition inclut des aires de jeux pour enfants, des espaces verts pour la détente, des sentiers de promenade et des équipements sportifs. Venez voter pour décider si nous devrions aménager cet espace vert qui renforcerait notre communauté et améliorerait la qualité de vie de chacun.",
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'session_id' => 1,
                'status_id' => Status::CLOSED,
                'type_id' => VoteType::PUBLIC,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => "Adoption d'une politique de recyclage renforcée",
                'description' => "Cette séance de vote vise à décider de l'adoption d'une politique de recyclage renforcée pour notre ville. La proposition comprend l'expansion des points de collecte, l'introduction de programmes de sensibilisation à la réduction des déchets, et des incitations pour les entreprises locales favorisant l'utilisation de matériaux recyclables. Ensemble, prenons des mesures importantes pour promouvoir la durabilité environnementale et préserver notre belle communauté.",
                'start_date' => '2023-06-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'session_id' => 2,
                'status_id' => Status::OPEN,
                'type_id' => VoteType::SECRET,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Budget alloué aux activités culturelles et artistiques',
                'description' => "Lors de cette séance de vote, nous décidons de l'allocation d'un budget spécifique pour les activités culturelles et artistiques dans notre ville. Les fonds seront destinés à soutenir les expositions d'artistes locaux, les festivals, les concerts et autres événements culturels. Venez participer au processus décisionnel pour contribuer à l'enrichissement culturel de notre communauté et à l'épanouissement de nos talents artistiques.",
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'session_id' => 3,
                'status_id' => Status::CLOSED,
                'type_id' => VoteType::PUBLIC,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => 'Réaménagement du centre-ville',
                'description' => "Cette séance de vote concerne le réaménagement du centre-ville. Le projet propose la revitalisation des zones commerciales, l'amélioration des infrastructures piétonnes, la création d'espaces verts et la modernisation des transports publics. Venez voter pour façonner l'avenir de notre centre-ville en contribuant à un environnement urbain dynamique, convivial et durable.",
                'start_date' => '2023-01-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'session_id' => 4,
                'status_id' => Status::OPEN,
                'type_id' => VoteType::PUBLIC,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'title' => "Adoption d'une charte pour l'inclusion et la diversité",
                'description' => "Lors de cette séance de vote, nous discutons de l'adoption d'une charte visant à promouvoir l'inclusion et la diversité dans notre communauté. La charte vise à renforcer les programmes d'éducation et de sensibilisation, à encourager l'embauche diversifiée dans les entreprises locales, et à créer un environnement accueillant pour tous, indépendamment de leur origine, genre ou orientation sexuelle. Rejoignez-nous pour voter en faveur d'une communauté ouverte, tolérante et respectueuse des différences.",
                'start_date' => '2023-06-01 12:00:00',
                'end_date' => '2023-12-01 18:00:00',
                'session_id' => 5,
                'status_id' => Status::CLOSED,
                'type_id' => VoteType::SECRET,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ]);
    }
}
