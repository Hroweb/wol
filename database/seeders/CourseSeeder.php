<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseTranslation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 3; $i++) {
            $start = now()->startOfMonth()->addMonths($i - 1);
            $end   = (clone $start)->addMonths(8); // ~9 months span

            $course = Course::create([
                'academic_year' => $start->format('Y').'-'.$end->format('Y'),
                'start_date'    => $start->toDateString(),
                'end_date'      => $end->toDateString(),
            ]);

            // English (different text)
            CourseTranslation::create([
                'course_id' => $course->id,
                'locale'    => 'en',
                'title'     => "Bible School {$start->format('Y')}–{$end->format('Y')} (C{$i})",
                'slug'      => Str::slug("bible school {$start->format('Y')} {$end->format('Y')} {$i}"),
                'description' => 'A structured, 9-month journey through Scripture, doctrine, and practical ministry.',
                'curriculum_pdf_url' => 'https://example.com/en/curriculum.pdf',
                'welcome_video_url'  => 'https://example.com/en/welcome.mp4',
            ]);

            // Armenian (different text + slug)
            CourseTranslation::create([
                'course_id' => $course->id,
                'locale'    => 'hy',
                'title'     => "Աստվածաշնչի դպրոց {$start->format('Y')}–{$end->format('Y')} (Դ{$i})",
                'slug'      => "astvatsashunchi-dproc-{$start->format('Y')}-{$end->format('Y')}-{$i}",
                'description' => 'Համակարգված 9 ամսյա ուղի՝ Սուրբ Գրքի, աստվածաբանության և ծառայության պրակտիկայի շուրջ։',
                'curriculum_pdf_url' => 'https://example.com/hy/curriculum.pdf',
                'welcome_video_url'  => 'https://example.com/hy/welcome.mp4',
            ]);
        }
    }
}
