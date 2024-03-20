<?php
namespace App\Media\Interfaces;

interface HasMedia
{
    public function getMedia(string $column);
    public function getMediaUrl(string $column);
    public function registerMediaSavePath(): void;
    public function setMediaSavePath(string $path): self;
    public function useFallbackUrl(string $url): self;
}