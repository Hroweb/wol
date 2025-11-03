<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Home Page
        $homePage = Page::create([
            'slug' => 'home',
            'template' => 'home',
            'is_published' => true,
            'order' => 1,
        ]);

        $homePage->translations()->createMany([
            [
                'locale' => 'en',
                'title' => 'Home',
                'meta_title' => 'Welcome to Our Learning Management System',
                'meta_description' => 'Join our community of learners and grow in faith and knowledge.',
                'meta_keywords' => 'learning, courses, faith, education',
                'content' => null,
            ],
            [
                'locale' => 'hy',
                'title' => 'Գլխավոր',
                'meta_title' => 'Բարի գալուստ մեր ուսուցման կառավարման համակարգ',
                'meta_description' => 'Միացե՛ք մեր սովորողների համայնքին և աճեք հավատքով և գիտելիքով։',
                'meta_keywords' => 'ուսուցում, դասընթացներ, հավատք, կրթություն',
                'content' => null,
            ],
        ]);

        // Hero Section
        /*$heroSection = $homePage->sections()->create([
            'section_type' => 'hero',
            'order' => 1,
            'settings' => ['image' => '/img/hero-bg.jpg'],
            'is_active' => true,
        ]);*/

        /*$heroSection->translations()->createMany([
            [
                'locale' => 'en',
                'title' => 'Welcome to Our Learning Platform',
                'subtitle' => 'Grow in faith and knowledge',
                'content' => '<p>Join thousands of students in their journey of spiritual growth and learning.</p>',
                'cta_text' => 'Browse Courses',
                'cta_link' => '/courses',
            ],
            [
                'locale' => 'hy',
                'title' => 'Բարի գալուստ մեր ուսուցման հարթակ',
                'subtitle' => 'Աճե՛ք հավատքով և գիտելիքով',
                'content' => '<p>Միացե՛ք հազարավոր ուսանողների՝ հոգևոր աճի և ուսուցման իրենց ճանապարհին։</p>',
                'cta_text' => 'Դիտել դասընթացները',
                'cta_link' => '/courses',
            ],
        ]);*/

        // Featured Courses Section
        /*$coursesSection = $homePage->sections()->create([
            'section_type' => 'featured_courses',
            'order' => 2,
            'settings' => ['course_ids' => [], 'limit' => 3], // Empty initially, can be filled later
            'is_active' => true,
        ]);*/

        /*$coursesSection->translations()->createMany([
            [
                'locale' => 'en',
                'title' => 'Featured Courses',
                'subtitle' => 'Explore our most popular courses',
                'content' => null,
                'cta_text' => null,
                'cta_link' => null,
            ],
            [
                'locale' => 'hy',
                'title' => 'Առանձնացված դասընթացներ',
                'subtitle' => 'Բացահայտե՛ք մեր ամենահայտնի դասընթացները',
                'content' => null,
                'cta_text' => null,
                'cta_link' => null,
            ],
        ]);*/

        // About Page
        $aboutPage = Page::create([
            'slug' => 'about',
            'template' => 'default',
            'is_published' => true,
            'order' => 2,
        ]);

        $aboutPage->translations()->createMany([
            [
                'locale' => 'en',
                'title' => 'About Us',
                'meta_title' => 'About Our Ministry',
                'meta_description' => 'Learn about our mission, vision, and the team behind our learning platform.',
                'meta_keywords' => 'about, ministry, mission, team',
                'content' => null,
            ],
            [
                'locale' => 'hy',
                'title' => 'Մեր մասին',
                'meta_title' => 'Մեր սպասավորության մասին',
                'meta_description' => 'Իմացե՛ք մեր առաքելության, տեսլականի և մեր ուսուցման հարթակի թիմի մասին։',
                'meta_keywords' => 'մեր մասին, սպասավորություն, առաքելություն, թիմ',
                'content' => null,
            ],
        ]);

        // About Hero Section
        /*$aboutHero = $aboutPage->sections()->create([
            'section_type' => 'hero',
            'order' => 1,
            'settings' => [],
            'is_active' => true,
        ]);*/

        /*$aboutHero->translations()->createMany([
            [
                'locale' => 'en',
                'title' => 'About Our Ministry',
                'subtitle' => 'Serving the community through education and faith',
                'content' => null,
                'cta_text' => null,
                'cta_link' => null,
            ],
            [
                'locale' => 'hy',
                'title' => 'Մեր սպասավորության մասին',
                'subtitle' => 'Ծառայելով համայնքին կրթության և հավատքի միջոցով',
                'content' => null,
                'cta_text' => null,
                'cta_link' => null,
            ],
        ]);*/

        // About Text Block
        /*$aboutText = $aboutPage->sections()->create([
            'section_type' => 'text_block',
            'order' => 2,
            'settings' => ['style' => 'centered'],
            'is_active' => true,
        ]);*/

        /*$aboutText->translations()->createMany([
            [
                'locale' => 'en',
                'title' => 'Our Mission',
                'subtitle' => 'Empowering believers through quality education',
                'content' => '<p>We are dedicated to providing accessible, high-quality theological and spiritual education to believers around the world. Our mission is to equip students with biblical knowledge and practical skills for ministry and daily life.</p><p>Through our courses, we aim to deepen understanding of Scripture, strengthen faith, and prepare individuals for effective service in their communities.</p>',
                'cta_text' => null,
                'cta_link' => null,
            ],
            [
                'locale' => 'hy',
                'title' => 'Մեր առաքելությունը',
                'subtitle' => 'Հավատացյալներին զորավորել որակյալ կրթության միջոցով',
                'content' => '<p>Մենք նվիրված ենք մատչելի, բարձրորակ աստվածաբանական և հոգևոր կրթություն տրամադրելուն հավատացյալներին ամբողջ աշխարհում։ Մեր առաքելությունն է ուսանողներին հագեցնել աստվածաշնչյան գիտելիքներով և գործնական հմտություններով՝ սպասավորության և առօրյա կյանքի համար։</p><p>Մեր դասընթացների միջոցով մենք նպատակ ունենք խորացնել Սուրբ Գրքի հասկացությունը, ամրապնդել հավատը և պատրաստել մարդկանց արդյունավետ ծառայության համար իրենց համայնքներում։</p>',
                'cta_text' => null,
                'cta_link' => null,
            ],
        ]);*/

        // Contact Page
        $contactPage = Page::create([
            'slug' => 'contact',
            'template' => 'contact',
            'is_published' => true,
            'order' => 3,
        ]);

        $contactPage->translations()->createMany([
            [
                'locale' => 'en',
                'title' => 'Contact Us',
                'meta_title' => 'Contact Us - Get in Touch',
                'meta_description' => 'Have questions? Contact our team and we will be happy to help you.',
                'meta_keywords' => 'contact, support, help, questions',
                'content' => null,
            ],
            [
                'locale' => 'hy',
                'title' => 'Կապ',
                'meta_title' => 'Կապ մեզ հետ - Կապվեք մեզ հետ',
                'meta_description' => 'Հարցե՞ր ունեք։ Կապվեք մեր թիմի հետ և մենք ուրախ կլինենք օգնել ձեզ։',
                'meta_keywords' => 'կապ, աջակցություն, օգնություն, հարցեր',
                'content' => null,
            ],
        ]);

        // Contact Form Section
        /*$contactForm = $contactPage->sections()->create([
            'section_type' => 'contact_form',
            'order' => 1,
            'settings' => ['email' => 'info@example.com'],
            'is_active' => true,
        ]);*/

        /*$contactForm->translations()->createMany([
            [
                'locale' => 'en',
                'title' => 'Get in Touch',
                'subtitle' => 'We would love to hear from you',
                'content' => null,
                'cta_text' => 'Send Message',
                'cta_link' => null,
            ],
            [
                'locale' => 'hy',
                'title' => 'Կապվեք մեզ հետ',
                'subtitle' => 'Մենք ուրախ կլինենք լսել ձեզնից',
                'content' => null,
                'cta_text' => 'Ուղարկել հաղորդագրություն',
                'cta_link' => null,
            ],
        ]);*/

        $this->command->info('Pages seeded successfully!');
    }
}
