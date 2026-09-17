<?php

foreach (['/tmp/views', '/tmp/sessions', '/tmp/cache'] as $directory) {
   if (! is_dir($directory)) {
      @mkdir($directory, 0777, true);
   }
}

// Forward request dari Vercel Serverless ke public/index.php milik Laravel
require __DIR__ . '/../public/index.php';
