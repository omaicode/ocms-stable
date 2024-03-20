<?php
namespace App\Contracts;

interface InteractWithBulletinBoardFiles {
    public function files() : \Illuminate\Database\Eloquent\Relations\MorphMany;
}
