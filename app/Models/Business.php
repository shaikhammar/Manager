<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Str;

class Business extends Model
{
    protected $fillable = [
        'name',
        'code',
        'user_id',
        'country_id',
        'currency_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }

    public static function generateUniqueCode(string $name, ?string $desiredCode = null): string
    {
        $limit = 5;
        
        if ($desiredCode) {
            $slug = Str::upper($desiredCode);
        } else {
            // Generate abbreviation
            $words = explode(' ', $name);
            $slug = '';
            
            foreach ($words as $word) {
                // If the word looks like an acronym (all caps, e.g. LLC), keep it
                if (strtoupper($word) === $word && strlen($word) > 1) {
                    $slug .= $word;
                } else {
                    $slug .= substr($word, 0, 1);
                }
            }
            
            $slug = preg_replace('/[^A-Z0-9]/', '', Str::upper($slug));

            // Fallback for single words or short results
            if (strlen($slug) < 2) {
                $slug = Str::slug($name); // medialocate-llc -> medialocate-llc
                // Remove hyphens to condense
                $slug = str_replace('-', '', $slug); 
                // e.g. transperfect -> transperfect
                
                // Try to remove vowels to make it "shorter" like TPT? 
                // Let's just take the first $limit characters
                $slug = substr(Str::upper($slug), 0, $limit);
            }
        }

        // Ensure length limit if it was auto-generated (not strict for user provided?)
        // The prompt says "5 character or less code".
        if (! $desiredCode) {
            $slug = substr($slug, 0, $limit);
        }

        $code = $slug;
        $count = 1;

        // If it exists, append numbers (e.g. TPT, TPT1, TPT2)
        while (static::where('code', $code)->exists()) {
             // If appending a number makes it longer than 5, we have to allow it or trunc?
             // Usually we allow growth for uniqueness.
             $code = "{$slug}{$count}";
             $count++;
        }

        return $code;
    }

    protected static function booted(): void
    {
        static::creating(function (self $business) {
            if (empty($business->code)) {
                $business->code = static::generateUniqueCode($business->name);
            }
        });
    }
}
