<?php

/*
 * Copyright 2021 Cloud Creativity Limited
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace LaravelJsonApi\OpenApiSpec\Tests\Support\Policies;

use LaravelJsonApi\Core\Store\LazyRelation;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Tag;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\User;
use LaravelJsonApi\OpenApiSpec\Tests\Support\Models\Video;

class VideoPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Video $video): bool
    {
        return true;
    }

    public function viewTags(?User $user, Video $video): bool
    {
        return $this->view($user, $video);
    }

    public function create(?User $user): bool
    {
        return (bool) $user;
    }

    public function update(?User $user, Video $video): bool
    {
        return $this->owner($user, $video);
    }

    public function updateTags(?User $user, Video $video, LazyRelation $tags): bool
    {
        $tags->collect()->each(fn (Tag $tag) => $tag);

        return $this->owner($user, $video);
    }

    public function attachTags(?User $user, Video $video, LazyRelation $tags): bool
    {
        return $this->updateTags($user, $video, $tags);
    }

    public function detachTags(?User $user, Video $video, LazyRelation $tags): bool
    {
        return $this->updateTags($user, $video, $tags);
    }

    public function delete(?User $user, Video $video): bool
    {
        return $this->owner($user, $video);
    }

    public function owner(?User $user, Video $video): bool
    {
        return $user && $video->owner->is($user);
    }
}
