<?php

namespace Blaze\AdminCore\Models;

use Blaze\AdminCore\Traits\HasCache;
use Blaze\AdminCore\Traits\HasSortOrder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasCache, HasFactory, HasSortOrder;

    protected $fillable = [
        'title',
        'subtitle',
        'icon',
        'parent_id',
        'type',
        'reference_id',
        'url',
        'sort_order',
        'status',
        'is_editable',
        'is_deletable',
    ];

    protected $casts = [
        'status' => 'boolean',
        'is_editable' => 'boolean',
        'is_deletable' => 'boolean',
    ];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'parent_id')->orderBy('sort_order');
    }

    public function serviceCategory(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'reference_id');
    }

    public function getUrlAttribute(): string
    {
        if ($this->type === 'custom_url') {
            return $this->attributes['url'] ?? '#';
        }

        if ($this->type === 'page' && $this->reference_id) {
            $page = Page::find($this->reference_id);

            return $page ? route('pages.show', $page->slug) : '#';
        }

        if ($this->type === 'service_category' && $this->reference_id) {
            $category = $this->relationLoaded('serviceCategory') && $this->serviceCategory
                ? $this->serviceCategory
                : ServiceCategory::find($this->reference_id);

            if ($category) {
                return route('services.category', $category->slug);
            }

            return '#';
        }

        return '#';
    }

    /**
     * Retrieve the cached navigation menu tree (top-level menus with active children).
     *
     * @return Collection<int, self>
     */
    public static function cachedTree(): Collection
    {
        $data = static::rememberCache('tree', function () {
            return static::whereNull('parent_id')
                ->where('status', true)
                ->orderBy('sort_order')
                ->with([
                    'children' => function ($query) {
                        $query->where('status', true)
                            ->orderBy('sort_order')
                            ->with([
                                'serviceCategory.services' => function ($sq) {
                                    $sq->where('status', true)->orderBy('sort_order');
                                },
                                'serviceCategory.children.services' => function ($sq) {
                                    $sq->where('status', true)->orderBy('sort_order');
                                },
                                'serviceCategory.children.children.services' => function ($sq) {
                                    $sq->where('status', true)->orderBy('sort_order');
                                },
                                'children' => function ($cq) {
                                    $cq->where('status', true)
                                        ->orderBy('sort_order')
                                        ->with([
                                            'serviceCategory.services' => function ($sq) {
                                                $sq->where('status', true)->orderBy('sort_order');
                                            },
                                            'serviceCategory.children.services' => function ($sq) {
                                                $sq->where('status', true)->orderBy('sort_order');
                                            },
                                        ]);
                                },
                            ]);
                    },
                ])
                ->get()
                ->toArray();
        });

        if (! is_array($data) || empty($data)) {
            return new Collection;
        }

        $items = collect($data)->map(function ($menuData) {
            $childrenData = $menuData['children'] ?? [];
            unset($menuData['children']);

            $menu = new static;
            $menu->setRawAttributes($menuData, true);
            $menu->exists = true;

            $children = collect($childrenData)->map(function ($childData) {
                $subChildrenData = $childData['children'] ?? [];
                $serviceCategoryData = $childData['service_category'] ?? null;
                unset($childData['children'], $childData['service_category']);

                $child = new static;
                $child->setRawAttributes($childData, true);
                $child->exists = true;

                if ($serviceCategoryData) {
                    $category = new ServiceCategory;
                    $servicesData = $serviceCategoryData['services'] ?? [];
                    $subCatsData = $serviceCategoryData['children'] ?? [];
                    unset($serviceCategoryData['services'], $serviceCategoryData['children']);

                    $category->setRawAttributes($serviceCategoryData, true);
                    $category->exists = true;

                    $services = collect($servicesData)->map(function ($sData) {
                        $s = new Service;
                        $s->setRawAttributes($sData, true);
                        $s->exists = true;

                        return $s;
                    });
                    $category->setRelation('services', new Collection($services->all()));

                    $subCats = collect($subCatsData)->map(function ($scData) {
                        $sc = new ServiceCategory;
                        $subServicesData = $scData['services'] ?? [];
                        $nestedSubCatsData = $scData['children'] ?? [];
                        unset($scData['services'], $scData['children']);
                        $sc->setRawAttributes($scData, true);
                        $sc->exists = true;

                        $subServices = collect($subServicesData)->map(function ($sData) {
                            $s = new Service;
                            $s->setRawAttributes($sData, true);
                            $s->exists = true;

                            return $s;
                        });
                        $sc->setRelation('services', new Collection($subServices->all()));

                        $nestedSubCats = collect($nestedSubCatsData)->map(function ($nscData) {
                            $nsc = new ServiceCategory;
                            $nServicesData = $nscData['services'] ?? [];
                            unset($nscData['services']);
                            $nsc->setRawAttributes($nscData, true);
                            $nsc->exists = true;

                            $nServices = collect($nServicesData)->map(function ($sData) {
                                $s = new Service;
                                $s->setRawAttributes($sData, true);
                                $s->exists = true;

                                return $s;
                            });
                            $nsc->setRelation('services', new Collection($nServices->all()));

                            return $nsc;
                        });
                        $sc->setRelation('children', new Collection($nestedSubCats->all()));

                        return $sc;
                    });
                    $category->setRelation('children', new Collection($subCats->all()));

                    $child->setRelation('serviceCategory', $category);
                }

                $subChildren = collect($subChildrenData)->map(function ($subChildData) {
                    $subServiceCategoryData = $subChildData['service_category'] ?? null;
                    unset($subChildData['children'], $subChildData['service_category']);

                    $subChild = new static;
                    $subChild->setRawAttributes($subChildData, true);
                    $subChild->exists = true;

                    if ($subServiceCategoryData) {
                        $category = new ServiceCategory;
                        $servicesData = $subServiceCategoryData['services'] ?? [];
                        unset($subServiceCategoryData['services']);
                        $category->setRawAttributes($subServiceCategoryData, true);
                        $category->exists = true;

                        $services = collect($servicesData)->map(function ($sData) {
                            $s = new Service;
                            $s->setRawAttributes($sData, true);
                            $s->exists = true;

                            return $s;
                        });
                        $category->setRelation('services', new Collection($services->all()));
                        $subChild->setRelation('serviceCategory', $category);
                    }

                    return $subChild;
                });
                $child->setRelation('children', new Collection($subChildren->all()));

                return $child;
            });

            $menu->setRelation('children', new Collection($children->all()));

            return $menu;
        });

        return new Collection($items->all());
    }

    public static function getCacheSuffixes(): array
    {
        return ['tree'];
    }
}
