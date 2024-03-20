<?php
namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Models\Translate;

trait HasTranslation
{
    protected $translationLocale = null;

    public function translates()
    {
        return $this->morphMany(Translate::class, 'reference');
    }

    public function getTranslatableAttributes(): array
    {
        return is_array($this->translatable)
            ? $this->translatable
            : [];
    }    

    public static function usingLocale(string $locale): self
    {
        return (new self())->setLocale($locale);
    }

    public function useFallbackLocale(): bool
    {
        if (property_exists($this, 'useFallbackLocale')) {
            return $this->useFallbackLocale;
        }

        return true;
    }    

    public function setLocale(string $locale): self
    {
        $this->translationLocale = $locale;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->translationLocale ?: config('app.locale');
    }    

    public function isTranslatableAttribute(string $key): bool
    {
        return in_array($key, $this->getTranslatableAttributes());
    }    

    public function getAttributeValue($key)
    {
        if (! $this->isTranslatableAttribute($key)) {
            return parent::getAttributeValue($key);
        }

        return $this->getTranslation($key, $this->getLocale());
    }    

    public function getTranslation(string $key, string $locale)
    {
        if($item = $this->translates()->where('language', $locale)->first()) {
            $translation = Arr::get($item->data, $key, parent::getAttributeValue($key));

            if ($this->hasGetMutator($key)) {
                return $this->mutateAttribute($key, $translation);
            }
    
            if($this->hasAttributeMutator($key)) {
                return $this->mutateAttributeMarkedAttribute($key, $translation);
            }

            return $translation;
        }

        return parent::getAttributeValue($key);
    }

    public function setTranslation(string $locale, string $key, $value)
    {
        $data = [];

        if ($this->hasSetMutator($key)) {
            $method = 'set'.Str::studly($key).'Attribute';

            $this->{$method}($value, $locale);

            $value = $this->attributes[$key];
        } elseif($this->hasAttributeSetMutator($key)) { // handle new attribute mutator
            $this->setAttributeMarkedMutatedAttributeValue($key, $value);

            $value = $this->attributes[$key];
        }
        
        $translate_data = optional($this->translates()->select(['data'])->where('language', $locale)->first())->data ?: [];
        foreach($this->getTranslatableAttributes() as $trans_Key) {
            $data[$trans_Key] = Arr::get($translate_data, $trans_Key, "");
        }

        $data[$key] = $value;
        $this->translates()->updateOrCreate([
            'language' => $locale
        ], ["data" => $data]);

        // $this->attributes[$key] = $value;

        return $this;
    }

    public function setAttribute($key, $value)
    {
        if ($this->isTranslatableAttribute($key) && $this->getLocale() != config('app.default_locale', 'vi')) {
            return $this->setTranslation($this->getLocale(), $key, $value);
        }

        return parent::setAttribute($key, $value);
    }

    public function attributesToArray()
    {
        $attributes = $this->getArrayableAttributes();

        if($this->getLocale() != config('app.default_locale', 'vi')) {
            if($item = $this->translates()->where('language', $this->getLocale())->first()) {
                foreach($attributes as $key => $value) {
                    if($this->isTranslatableAttribute($key)) {
                        $translation = Arr::get($item->data, $key, $value);
                        $attributes[$key] = $translation;
                    }
                }            
            }  
        }      

        // If an attribute is a date, we will cast it to a string after converting it
        // to a DateTime / Carbon instance. This is so we will get some consistent
        // formatting while accessing attributes vs. arraying / JSONing a model.
        $attributes = $this->addDateAttributesToArray($attributes);

        $attributes = $this->addMutatedAttributesToArray(
            $attributes, $mutatedAttributes = $this->getMutatedAttributes()
        );

        // Next we will handle any casts that have been setup for this model and cast
        // the values to their appropriate type. If the attribute has a mutator we
        // will not perform the cast on those attributes to avoid any confusion.
        $attributes = $this->addCastAttributesToArray(
            $attributes, $mutatedAttributes
        );

        // Here we will grab all of the appended, calculated attributes to this model
        // as these attributes are not really in the attributes array, but are run
        // when we need to array or JSON the model for convenience to the coder.
        foreach ($this->getArrayableAppends() as $key) {
            $attributes[$key] = $this->mutateAttributeForArray($key, null);
        }

        return $attributes;
    }      
}