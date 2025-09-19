<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $dummyData = [
            [
                'photo' => 'https://unsplash.com/photos/christian-priest-standing-by-the-altar-X61N_xtAlqk',
                'email' => 'pastorpoxos@example.org',
                'is_featured' => true,
                'social_ig' => 'https://instagram.com/pastorpoxos',
                'social_youtube' => 'https://youtube.com/pastorpoxos',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'photo' => 'https://unsplash.com/photos/christian-priest-standing-by-the-altar-X61N_xtAlqk',
                'email' => 'pastoraram@example.org',
                'is_featured' => false,
                'social_ig' => 'https://instagram.com/pastoraram',
                'social_youtube' => 'https://youtube.com/pastoraram',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'photo' => 'https://unsplash.com/photos/christian-priest-standing-by-the-altar-X61N_xtAlqk',
                'email' => 'pastorisaac@example.org',
                'is_featured' => true,
                'social_ig' => 'https://instagram.com/pastorisaac',
                'social_youtube' => 'https://youtube.com/pastorisaac',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        $dummyTranslations = [
            [ // for teacher #1
                [
                    'teacher_id' => '1',
                    'locale' => 'en',
                    'first_name' => 'Poxos',
                    'last_name' => 'Poxosyan',
                    'bio' => 'Pastor Poxos Poxosyan has been serving as the Senior Pastor of Grace Community Church since 2018. With over 15 years of ministry experience, he brings a heart for community outreach and biblical teaching to his role.',
                    'specializations' => 'Psycologist, Teacher',
                    'position' => 'Pastor',
                    'church_name' => 'Grace Community Church',
                    'city' => 'Yerevan',
                    'country' => 'Armenia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'teacher_id' => '1',
                    'locale' => 'hy',
                    'first_name' => 'Պողոս',
                    'last_name' => 'Պողոսյան',
                    'bio' => 'Հովիվ Պողոս Պողոսյանը 2018 թվականից ծառայում է որպես Շնորհի Համայնքային Եկեղեցու գլխավոր հովիվ: Ունենալով ավելի քան 15 տարվա սպասավորական փորձ, նա իր դերակատարությանը բերում է համայնքային հասանելիության և աստվածաշնչյան ուսուցման նկատմամբ սիրտ:',
                    'specializations' => 'Հոգեբան, Դասախոս',
                    'position' => 'Հովիվ',
                    'church_name' => 'Շնորհք Համայնքային Եկեղեցի',
                    'city' => 'Երևան',
                    'country' => 'Հայաստան',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ],
            [ // for teacher #2
                [
                    'teacher_id' => '2',
                    'locale' => 'en',
                    'first_name' => 'Aram',
                    'last_name' => 'Aramyan',
                    'bio' => 'Pastor Aram Aramyan joined our family a few months ago, he brings a heart for community outreach and biblical teaching to his role.',
                    'specializations' => 'Accountant',
                    'position' => 'Pastor',
                    'church_name' => 'Grace Community Church',
                    'city' => 'Yerevan',
                    'country' => 'Armenia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'teacher_id' => '2',
                    'locale' => 'hy',
                    'first_name' => 'Արամ',
                    'last_name' => 'Արամյան',
                    'bio' => 'Հովիվ Արամ Արամյանը մի քանի ամիս է ինչ միացել է մեր հոգեոր ընտանիքին: Նա իր դերակատարությանը բերում է համայնքային հասանելիության և աստվածաշնչյան ուսուցման նկատմամբ սիրտ:',
                    'specializations' => 'Հաշվապահ',
                    'position' => 'Հովիվ',
                    'church_name' => 'Շնորհք Համայնքային Եկեղեցի',
                    'city' => 'Երևան',
                    'country' => 'Հայաստան',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ],
            [ // for teacher #3
                [
                    'teacher_id' => '3',
                    'locale' => 'en',
                    'first_name' => 'Isaac',
                    'last_name' => 'Smith',
                    'bio' => 'Pastor Isaac Smith is one of our oldest mentors.',
                    'specializations' => 'Accountant',
                    'position' => 'Pastor',
                    'church_name' => 'Grace Community Church',
                    'city' => 'Yerevan',
                    'country' => 'Armenia',
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'teacher_id' => '3',
                    'locale' => 'hy',
                    'first_name' => 'Իսահակ',
                    'last_name' => 'Սմիթ',
                    'bio' => 'Հովիվ Իսահակը մեր ամենատարեց արաջնորդներից մեկն է:',
                    'specializations' => 'Հաշվապահ',
                    'position' => 'Հովիվ',
                    'church_name' => 'Շնորհք Համայնքային Եկեղեցի',
                    'city' => 'Երևան',
                    'country' => 'Հայաստան',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ],
        ];

        DB::transaction(function () use ($dummyData, $dummyTranslations) {
            foreach ($dummyData as $i => $teacherData) {
                // create teacher
                $teacher = Teacher::create($teacherData);

                // take the matching translation pair and strip the hardcoded teacher_id
                $translations = collect($dummyTranslations[$i] ?? [])
                    ->map(function ($t) {
                        unset($t['teacher_id']); // ensure we rely on relation to set it
                        return $t;
                    })->all();

                // attach two translations via relation
                $teacher->translations()->createMany($translations);
            }
        });
    }
}

