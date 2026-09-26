<?php

namespace Blaze\AdminCore\Traits;

use Blaze\AdminCore\Models\Faq;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasFaqs
{
    protected static function bootHasFaqs(): void
    {
        static::deleted(function (Model $model): void {
            $faqIds = $model->faqs()->pluck('faqs.id')->all();

            $model->faqs()->detach();
            Faq::refreshUsageCounts($faqIds);
        });
    }

    public function faqs(): MorphToMany
    {
        return $this->morphToMany(Faq::class, 'faqable')
            ->withTimestamps()
            ->orderBy('sort_order');
    }

    public function syncFaqs(array $faqIds): void
    {
        $existingFaqIds = $this->faqs()->pluck('faqs.id')->all();
        $this->faqs()->sync($faqIds);
        Faq::refreshUsageCounts([...$existingFaqIds, ...$faqIds]);
    }
}
