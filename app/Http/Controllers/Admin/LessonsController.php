<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLessonRequest;
use App\Models\Lesson;
use App\Services\FileDeletionService;
use App\Services\LessonService;
use Illuminate\Http\Request;

class LessonsController extends Controller
{
    public function index(Request $request, LessonService $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $allowedSorts = ['title', 'description', 'lesson_date', 'created_at', 'id'];
        $order = Helper::sortableOrder($request->query('sort'), $request->query('dir'), $allowedSorts);

        $lessons = $service->list(10, 'hy', $order);
        return view('admin.lessons.index', compact('lessons'));
    }

    public function create(LessonService $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $data = $service->getCreateData();

        return view('admin.lessons.create', $data);
    }

    public function store(StoreLessonRequest $request, LessonService $service): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $lesson = $service->store($request->validated());

        if ($request->wantsJson()) {
            return response()->json($lesson, 201);
        }

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson created successfully');
    }

    public function edit(Lesson $lesson, LessonService $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $data = $service->getEditData($lesson);
        return view('admin.lessons.edit', $data);
    }
    public function editz(Lesson $lesson, LessonService $service): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $data = $service->getCreateData();
        $lesson = $lesson->load(['translations', 'parts.teacher']);

        // Process lesson parts data for JavaScript
        $lessonPartsData = $lesson->parts->map(function($part) {
            return [
                'id' => $part->id,
                'teacher_id' => (string)$part->teacher_id,
                'part_number' => $part->part_number,
                'audio_file_urls' => $part->audio_file_urls ?? '',
                'duration_minutes' => $part->duration_minutes ?? ''
            ];
        })->toArray();

        $data['lesson'] = $lesson;
        $data['lessonPartsData'] = $lessonPartsData;

        return view('admin.lessons.edit', $data);
    }

    public function update(StoreLessonRequest $request, LessonService $service, Lesson $lesson): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $service->update($lesson, $request->validated());

        if ($request->wantsJson()) {
            return response()->json($lesson->fresh(['translations', 'parts.teacher']), 200);
        }

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson updated successfully');
    }

    public function destroy(Lesson $lesson, FileDeletionService $fileDeletionService): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        // Delete all lesson files before deleting the lesson
        $fileDeletionService->deleteAllLessonFiles($lesson);

        $lesson->delete();

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Lesson deleted successfully'], 200);
        }

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson deleted successfully');
    }

    public function deleteMaterial(Lesson $lesson, string $locale, int $index, FileDeletionService $fileDeletionService): \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $translation = $lesson->translations()->where('locale', $locale)->first();

        if (!$translation) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Translation not found'], 404);
            }
            return redirect()->back()->with('error', 'Translation not found');
        }

        $success = $fileDeletionService->deleteMaterialFile($translation, $index);

        if (!$success) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Material not found'], 404);
            }
            return redirect()->back()->with('error', 'Material not found');
        }

        if (request()->wantsJson()) {
            return response()->json(['message' => 'Material deleted successfully'], 200);
        }

        return redirect()->back()->with('success', 'Material deleted successfully');
    }
}
