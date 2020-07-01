<?php

namespace Corals\Modules\Utility\Notifications\Comment;

use Corals\User\Communication\Classes\CoralsBaseNotification;

class CommentToggleStatus extends CoralsBaseNotification
{
    /**
     * @return mixed
     */
    public function getNotifiables()
    {
        $comment = $this->data['comment'];

        $commentable = $comment->commentable;

        $owner = method_exists($commentable, 'owner') ? $commentable->owner() : $commentable->creator;

        if (!empty($owner)) {
            return $owner;
        } else {
            return [];
        }
    }

    public function getNotificationMessageParameters($notifiable, $channel)
    {
        $comment = $this->data['comment'];

        $author = $comment->author;

        $commentable = $comment->commentable;

        return [
            'commentable_identifier' => $commentable->getIdentifier(),
            'commentable_class' => class_basename($commentable),
            'comment_body' => $comment->body ?? '-',
            'comment_status' => $comment->present('status'),
            'author_name' => $author->name,
            'author_email' => $author->email,
        ];
    }

    public static function getNotificationMessageParametersDescriptions()
    {
        return [
            'commentable_identifier' => 'Commentable identifier',
            'commentable_class' => 'Commentable class',
            'author_name' => 'Author name',
            'author_email' => 'Author email',
            'comment_body' => 'Body',
            'comment_status' => 'Status',
        ];
    }
}
