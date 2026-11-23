<?php

namespace App\Console\Commands;

use App\Models\TaskAttachment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class SecureTaskAttachments extends Command
{
    protected $signature = 'task-attachments:secure';

    protected $description = 'Move existing public task attachments to private storage';

    public function handle(): int
    {
        $public = Storage::disk('public');
        $private = Storage::disk('local');
        $moved = 0;
        $missing = 0;

        foreach (TaskAttachment::cursor() as $attachment) {
            $path = $attachment->file_path;

            if ($private->exists($path) && !$public->exists($path)) {
                continue;
            }

            if (!$public->exists($path)) {
                $this->error("Attachment {$attachment->id} is missing from both storage disks.");
                $missing++;
                continue;
            }

            $stream = $public->readStream($path);
            if (!is_resource($stream)) {
                throw new RuntimeException("Unable to read public attachment {$attachment->id}.");
            }

            try {
                if (!$private->writeStream($path, $stream)) {
                    throw new RuntimeException("Unable to write private attachment {$attachment->id}.");
                }
            } finally {
                fclose($stream);
            }

            if (!$private->exists($path) || !$public->delete($path)) {
                throw new RuntimeException("Unable to secure public attachment {$attachment->id}.");
            }

            $moved++;
        }

        $this->info("Moved {$moved} task attachments to private storage.");

        return $missing > 0 ? self::FAILURE : self::SUCCESS;
    }
}
