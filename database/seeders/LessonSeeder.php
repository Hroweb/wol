<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LessonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Course::all()->each(function (Course $course) {
            for ($i = 1; $i <= 6; $i++) {
                $date = $course->start_date->copy()->addWeeks($i - 1);

                $lesson = Lesson::create([
                    'course_id'   => $course->id,
                    'lesson_date' => $date->toDateString(),
                    'sort_order'  => $i,
                ]);

                // English (different content)
                LessonTranslation::create([
                    'lesson_id'   => $lesson->id,
                    'locale'      => 'en',
                    'title'       => "Lesson {$i}: Foundations of Faith",
                    'description' => "Exploring core doctrines and key passages (week {$i}).",
                    'materials'   => "https://example.com/en/c{$course->id}/materials/lesson-{$i}\nhttps://example.com/en/reading-list/{$i}",
                ]);

                // Armenian (different content)
                LessonTranslation::create([
                    'lesson_id'   => $lesson->id,
                    'locale'      => 'hy',
                    'title'       => "Դաս {$i}․ Հավատի հիմունքներ",
                    'description' => "Քննարկում ենք հիմնական վարդապետությունները և կարևոր հատվածները (շաբաթ {$i}).",
                    'materials'   => "https://example.com/hy/c{$course->id}/materials/lesson-{$i}\nhttps://example.com/hy/reading-list/{$i}",
                ]);
            }
        });
    }
}
