<?php

namespace App\Observers;

use App\Models\Book;
use App\Jobs\UpdateAuthorBookCount;

class BookObserver
{
    /**
     * Handle the book "created" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function created(Book $book)
    {
        UpdateAuthorBookCount::dispatch($book->author_id);
    }

    /**
     * Handle the book "updated" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function updated(Book $book)
    {
        // Si el author_id ha cambiado
        if ($book->isDirty('author_id')) {
            // Despachar para el nuevo autor
            if ($book->author_id) {
                UpdateAuthorBookCount::dispatch($book->author_id);
            }
            // Despachar para el autor original
            if ($book->getOriginal('author_id')) {
                UpdateAuthorBookCount::dispatch($book->getOriginal('author_id'));
            }
        }
    }

    /**
     * Handle the book "deleted" event.
     *
     * @param  \App\Models\Book  $book
     * @return void
     */
    public function deleted(Book $book)
    {
        if ($book->author_id) {
            UpdateAuthorBookCount::dispatch($book->author_id);
        }
    }
}
