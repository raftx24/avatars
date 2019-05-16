<?php

namespace LaravelEnso\Avatars\app\Services;

use Illuminate\Http\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use LaravelEnso\Core\app\Models\User;
use Illuminate\Support\Facades\Storage;
use LaravelEnso\Avatars\app\Models\Avatar;

class DefaultAvatar
{
    private const Filename = 'avatar';
    private const Extension = 'jpg';
    private const FontSize = 128;

    private $user;
    private $avatar;
    private $filePath;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create()
    {
        DB::transaction(function () {
            $this->avatar = $this->user->avatar()
                ->firstOrCreate(['user_id' => $this->user->id]);

            $this->generate();

            $this->avatar->attach(
                new File($this->filePath()), $this->originalName()
            );
        });

        return $this->avatar;
    }

    private function generate()
    {
        $this->avatar->ensureFolderExists();

        \Avatar::create($this->user->person->name)
            ->setDimension(Avatar::ImageWidth, Avatar::ImageHeight)
            ->setFontSize(self::FontSize)
            ->setBackground($this->background())
            ->getImageObject()
            ->save($this->filePath());
    }

    private function originalName()
    {
        return self::Filename.$this->user->id.'.'.self::Extension;
    }

    private function hashName()
    {
        return Str::random(40).'.'.self::Extension;
    }

    private function filePath()
    {
        return $this->filePath
            ?? $this->filePath = Storage::path(
                $this->avatar->folder().DIRECTORY_SEPARATOR.$this->hashName()
            );
    }

    private function background()
    {
        return collect(config('laravolt.avatar.backgrounds'))
            ->random();
    }
}