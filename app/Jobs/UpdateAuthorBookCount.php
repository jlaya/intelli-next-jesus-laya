<?php

namespace App\Jobs;

use App\Models\Author;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateAuthorBookCount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $authorId;

    /**
     * Create a new job instance.
     *
     * @param int $authorId
     * @return void
     */
    public function __construct($authorId)
    {
        $this->authorId = $authorId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $author = Author::find($this->authorId);
        if ($author) {
            $author->books_count = $author->books()->count();
            $author->save();
        }
    }
}
