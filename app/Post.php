<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;

class Post extends Model
{
    use Sluggable;

//    const IS_DRAFT = 0; // Для метода setDraft()
//    const IS_PUBLIC = 1; // Для метода setPublic()

    protected $fillable = ['title', 'content'];

    public function category()
    {
        return $this->hasOne(Category::class);
    }

    public function author()
    {
        return $this->hasOne(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(
            Tag::class,
            'post_tags',
            'post_id',
            'tag_id'
        );
    }

    public function sluggable()
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public static function add($fields)
    {
        $post = new static;     // Создается экземпляр данного класса. Можно использовать self вместо static
        $post->fill($fields);   // Заполнение экземпляра класса нужными данными (указанными в массиве $fillable)
        $post->user_id = 1;     // Присваиваем пользователю ID
        $post->save();

        return $post;
    }

    public function edit($fields)
    {
        $this->fill($fields);
    }

    public function remove()
    {
        // Здесь прописать метод для удаления картинки поста
        $this->delete(); // Удаление поста
    }

    public function uploadImage($image)
    {
        if($image == null)
        {
            return;
        }
        Storage::delete('uploads/' . $this->image);
        $filename = str_random(10) . "." . $image->extension();
        $image->saveAs('uploads', $filename); // Папку для сохранения указываем относительно папки public
        $this->image = $filename;
        $this->save();
    }

    public function getImage()
    {
        if($this->image == null)
        {
            return '/img/no-image.png';
        }
        return '/uploads' . $this->image;
    }

    public function setCategory($id)
    {
        if($id == null)
        {
            return;
        }
        $this->category_id = $id;
        $this->save();
    }

    public function setTags($ids)
    {
        if($ids == null)
        {
            return;
        }
        $this->tags()->sync( $ids);
    }

    public function setDraft()
    {
        $this->status = 0; // Ниже - аналогичная команда, но вверху нужно создать константу IS_DRAFT, равную 0
//      $this->status = Post::IS_DRAFT;
        $this->save();
    }

    public function setPublic()
    {
        $this->status = 1; // Ниже - аналогичная команда, но вверху нужно создать константу IS_PUBLIC, равную 1
//      $this->status = Post::IS_PUBLIC;
        $this->save();
    }

    public function toggleStatus($value)
    {
        if($value == null)
        {
            return $this->setDraft();
        }
        else
        {
            return $this->setPublic();
        }
    }

    public function setFeatured()
    {
        $this->is_featured = 1;
        $this->save();
    }

    public function setStandart()
    {
        $this->is_featured = 0;
        $this->save();
    }

    public function toggleFeatured($value)
    {
        if($value == null)
        {
            return $this->setStandart();
        }
        else
        {
            return $this->setFeatured();
        }
    }

}
