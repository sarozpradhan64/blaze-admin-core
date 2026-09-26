<?php

namespace Blaze\AdminCore\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Facades\DB;

class Faq extends Model
{
    use HasFactory;

    protected $table = 'faqs';

    protected $guarded = [];

    protected $casts = [
        'status' => 'boolean',
        'sort_order' => 'integer',
        'usage_count' => 'integer',
    ];

    public function services(): MorphToMany
    {
        return $this->morphedByMany(Service::class, 'faqable');
    }

    public function blogs(): MorphToMany
    {
        return $this->morphedByMany(Blog::class, 'faqable');
    }

    public static function refreshUsageCounts(array $faqIds = []): void
    {
        $faqIds = array_values(array_unique(array_filter($faqIds)));

        if ($faqIds === []) {
            return;
        }

        static::whereKey($faqIds)->update(['usage_count' => 0]);

        DB::table('faqables')
            ->select('faq_id', DB::raw('count(*) as aggregate'))
            ->whereIn('faq_id', $faqIds)
            ->groupBy('faq_id')
            ->orderBy('faq_id')
            ->each(function (object $faqable): void {
                static::whereKey($faqable->faq_id)->update(['usage_count' => $faqable->aggregate]);
            });
    }
}
