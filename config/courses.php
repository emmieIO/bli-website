<?php

return [
    'lesson_video_disk' => env('COURSE_LESSON_VIDEO_DISK', 's3'),
    'lesson_document_disk' => env('COURSE_LESSON_DOCUMENT_DISK', 'public'),
    'signed_url_ttl_minutes' => (int) env('COURSE_SIGNED_URL_TTL', 60),
];
