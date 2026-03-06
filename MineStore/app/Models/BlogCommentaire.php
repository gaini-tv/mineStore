<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogCommentaire extends Model
{
    protected $table = 'blog_commentaires';

    protected $primaryKey = 'id_blog_commentaire';

    protected $fillable = [
        'blog_article_id',
        'user_id',
        'contenu',
    ];

    public function blogArticle()
    {
        return $this->belongsTo(BlogArticle::class, 'blog_article_id', 'id_blog_article');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getRouteKeyName(): string
    {
        return 'id_blog_commentaire';
    }
}
