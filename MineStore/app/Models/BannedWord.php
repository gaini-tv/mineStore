<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannedWord extends Model
{
    protected $table = 'banned_words';

    protected $fillable = [
        'word',
    ];

    public static function filterText(string $text): string
    {
        $words = static::query()->pluck('word')->all();

        if (empty($words)) {
            return $text;
        }

        foreach ($words as $word) {
            $pattern = '/\b' . preg_quote($word, '/') . '\b/iu';

            $text = preg_replace_callback($pattern, function (array $matches) {
                $length = mb_strlen($matches[0]);

                if ($length <= 0) {
                    return $matches[0];
                }

                return str_repeat('*', $length);
            }, $text);
        }

        return $text;
    }
}

