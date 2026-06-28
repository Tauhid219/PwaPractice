<?php

namespace App\Services;

use App\Imports\LiveExamQuestionImport;
use App\Models\LiveExam;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class LiveExamService
{
    /**
     * Import questions from a file and assign them to a LiveExam.
     *
     * @param  LiveExam  $liveExam
     * @param  UploadedFile  $file
     * @return void
     *
     * @throws \Exception
     */
    public function importQuestions(LiveExam $liveExam, UploadedFile $file): void
    {
        try {
            Excel::import(new LiveExamQuestionImport($liveExam), $file);
            Cache::forget("exam_questions_{$liveExam->id}");
        } catch (\Exception $e) {
            Log::error('Live Exam Import error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }
    }

    /**
     * Sync questions to a LiveExam (add or remove).
     *
     * @param  LiveExam  $liveExam
     * @param  string  $action  'add' or 'remove'
     * @param  array  $questionIds
     * @return string
     */
    public function syncQuestions(LiveExam $liveExam, string $action, array $questionIds): string
    {
        if (empty($questionIds)) {
            return 'No questions selected.';
        }

        if ($action === 'add') {
            $liveExam->questions()->syncWithoutDetaching($questionIds);
            $message = count($questionIds) . ' questions added successfully.';
        } elseif ($action === 'remove') {
            $liveExam->questions()->detach($questionIds);
            $message = count($questionIds) . ' questions removed successfully.';
        } else {
            $message = 'Invalid action.';
        }

        Cache::forget("exam_questions_{$liveExam->id}");

        return $message;
    }
}
