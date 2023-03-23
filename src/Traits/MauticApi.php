<?php

namespace Combindma\Mautic\Traits;

trait MauticApi
{
    public function subscribe(string $email, array $attributes = [])
    {
        return $this->contactApi()->create(array_merge([
            'email' => $email,
            'ipAddress' => request()->ip(),
        ], $attributes));
    }
}
