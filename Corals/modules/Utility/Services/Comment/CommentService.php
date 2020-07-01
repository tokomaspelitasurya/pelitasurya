<?php

namespace Corals\Modules\Utility\Services\Comment;

use Corals\Foundation\Services\BaseServiceClass;
use Corals\Modules\Utility\Classes\Comment\CommentManager;

class CommentService extends BaseServiceClass
{
    public function createComment($data, $commentableClass, $commentable_hashed_id)
    {
        if (is_null($commentableClass)) {
            abort(400, 'Comment class is null');
        }

        $commentable = $commentableClass::findByHash($commentable_hashed_id);

        if (!$commentable) {
            abort(404, 'Not Found!!');
        }


        $commentableManagerClass = new CommentManager($commentable, user());

        return $commentableManagerClass->createComment([
            'body' => $data['body'],
            'status' => $data['status'] ?? null,
        ]);
    }


    public function replyComment($data, $comment)
    {
        $commentableClass = new CommentManager($comment, user());

        $reply = $commentableClass->createComment([
            'body' => $data['body'],
            'status' => $data['status'] ?? null,
        ]);

        return $reply;
    }
}
