<?php

namespace Corals\User\Transformers;

use Corals\Foundation\Transformers\BaseTransformer;
use Corals\User\Models\User;

class UserTransformer extends BaseTransformer
{
    public function __construct($extras = [])
    {
        $this->resource_url = config('user.models.user.resource_url');

        parent::__construct($extras);
    }

    /**
     * @param User $user
     * @return array
     * @throws \Throwable
     */
    public function transform(User $user)
    {
        $show_url = $user->getShowURL();

        $transformedArray = [
            'id' => $user->id,
            'full_name' => '<a href="' . $show_url . '">' . $user->full_name . '</a>',
            'checkbox' => $this->generateCheckboxElement($user),
            'name' => '<a href="' . $show_url . '">' . $user->name . '</a>',
            'last_name' => $user->last_name,
            'email' => $user->email,
            'confirmed' => $user->confirmed ? '&#10004;' : '-',
            'roles' => formatArrayAsLabels($user->roles->pluck('label'), 'success'),
            'picture' => $user->picture,
            'picture_thumb' => '<a href="' . $show_url . '">' . '<img src="' . $user->picture_thumb . '" class="img-circle img-responsive" alt="User Picture" width="35"/></a>',
            'created_at' => format_date($user->created_at),
            'updated_at' => format_date($user->updated_at),
            'action' => $this->actions($user),
        ];

        return parent::transformResponse($transformedArray);
    }
}