<?php

namespace App\Services;

use App\Models\Exam;
use App\Models\ExamAttempt;
use Carbon\Carbon;

class ExamTimeValidator
{
    /**
     * Check if a student can start an exam based on time window.
     *
     * @param Exam $exam
     * @return bool
     */
    public function canStartExam(Exam $exam): bool
    {
        $now = Carbon::now();

        // Check if exam has started
        if ($exam->start_time && $now->lt($exam->start_time)) {
            return false;
        }

        // Check if exam has ended
        if ($exam->end_time && $now->gt($exam->end_time)) {
            return false;
        }

        return true;
    }

    /**
     * Get the current time status of an exam.
     *
     * @param Exam $exam
     * @return string Returns 'not_started', 'active', or 'ended'
     */
    public function getTimeStatus(Exam $exam): string
    {
        $now = Carbon::now();

        // Check if exam hasn't started yet
        if ($exam->start_time && $now->lt($exam->start_time)) {
            return 'not_started';
        }

        // Check if exam has ended
        if ($exam->end_time && $now->gt($exam->end_time)) {
            return 'ended';
        }

        // Exam is currently active
        return 'active';
    }

    /**
     * Get remaining time in seconds for an exam attempt.
     *
     * @param ExamAttempt $attempt
     * @return int Returns seconds remaining, 0 if expired
     */
    public function getRemainingTime(ExamAttempt $attempt): int
    {
        // If attempt is not in progress, return 0
        if ($attempt->status !== 'in_progress') {
            return 0;
        }

        // If exam or started_at is not set, return 0
        if (!$attempt->exam || !$attempt->started_at) {
            return 0;
        }

        $exam = $attempt->exam;
        $now = Carbon::now();

        // Calculate time based on exam duration
        if ($exam->duration_minutes) {
            $durationSeconds = $exam->duration_minutes * 60;
            $elapsedSeconds = $now->diffInSeconds($attempt->started_at);
            $remainingFromDuration = $durationSeconds - $elapsedSeconds;
        } else {
            // If no duration set, use a large number
            $remainingFromDuration = PHP_INT_MAX;
        }

        // Calculate time based on exam end time
        if ($exam->end_time) {
            $remainingFromEndTime = $now->diffInSeconds($exam->end_time, false);
            // If end time has passed, remainingFromEndTime will be negative
            if ($remainingFromEndTime < 0) {
                $remainingFromEndTime = 0;
            }
        } else {
            // If no end time set, use a large number
            $remainingFromEndTime = PHP_INT_MAX;
        }

        // Return the minimum of the two (whichever expires first)
        $remaining = min($remainingFromDuration, $remainingFromEndTime);

        // Ensure we don't return negative values
        return max(0, (int) $remaining);
    }

    /**
     * Check if an exam attempt has expired.
     *
     * @param ExamAttempt $attempt
     * @return bool
     */
    public function isAttemptExpired(ExamAttempt $attempt): bool
    {
        return $this->getRemainingTime($attempt) <= 0;
    }

    /**
     * Get a human-readable message about exam time status.
     *
     * @param Exam $exam
     * @return string
     */
    public function getTimeStatusMessage(Exam $exam): string
    {
        $status = $this->getTimeStatus($exam);

        switch ($status) {
            case 'not_started':
                if ($exam->start_time) {
                    return 'This exam will start on ' . $exam->start_time->format('M d, Y \a\t h:i A');
                }
                return 'This exam has not started yet.';

            case 'ended':
                if ($exam->end_time) {
                    return 'This exam ended on ' . $exam->end_time->format('M d, Y \a\t h:i A');
                }
                return 'This exam has ended.';

            case 'active':
                if ($exam->end_time) {
                    return 'This exam is active and will end on ' . $exam->end_time->format('M d, Y \a\t h:i A');
                }
                return 'This exam is currently active.';

            default:
                return 'Exam status unknown.';
        }
    }

    /**
     * Get time until exam starts (in seconds).
     *
     * @param Exam $exam
     * @return int Returns seconds until start, 0 if already started
     */
    public function getTimeUntilStart(Exam $exam): int
    {
        if (!$exam->start_time) {
            return 0;
        }

        $now = Carbon::now();
        
        if ($now->gte($exam->start_time)) {
            return 0;
        }

        return $now->diffInSeconds($exam->start_time);
    }

    /**
     * Get time until exam ends (in seconds).
     *
     * @param Exam $exam
     * @return int Returns seconds until end, 0 if already ended
     */
    public function getTimeUntilEnd(Exam $exam): int
    {
        if (!$exam->end_time) {
            return PHP_INT_MAX;
        }

        $now = Carbon::now();
        
        if ($now->gte($exam->end_time)) {
            return 0;
        }

        return $now->diffInSeconds($exam->end_time);
    }
}
