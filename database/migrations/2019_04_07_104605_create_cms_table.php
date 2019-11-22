<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // 文章分类
        Schema::create('cms_article_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('parent_id')->default(0);
            $table->integer('left_key')->default(0);
            $table->integer('right_key')->default(0);
            $table->integer('depth')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 文章表
        Schema::create('cms_articles', function (Blueprint $table){
            $table->increments('id');
            $table->integer('category_id');
            $table->string('title');
            $table->string('intro')->nullable();
            $table->string('seo_keyword')->nullable();
            $table->string('seo_description')->nullable();
            // 1 原创 2转载
            $table->tinyInteger('from_type')->default(1);
            $table->string('from_text')->nullable();
            $table->string('from_link')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('content');
            $table->integer('admin_user_id');
            $table->tinyInteger('status')->default(1);
            $table->integer('hits')->default(0);
            $table->boolean('allow_comment')->default(1);
            $table->timestamps();
        });

        // 文章评论表
        Schema::create('cms_article_comments', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->integer('article_id');
            $table->integer('user_id')->nullable();
            $table->integer('admin_user_id')->nullable();
            $table->text('content');
            $table->integer('parent_id')->default(0);
            $table->integer('left_key')->default(0);
            $table->integer('right_key')->default(0);
            $table->integer('depth')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('praise_count')->default(0);
            $table->timestamps();
        });

        // 点赞列表
        Schema::create('cms_aticle_comment_praises', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('comment_id');
            $table->integer('user_id')->nullable();
            $table->string('user_client_ip')->nullable();
            $table->timestamp('created_at');
        });

        // 单页面~
        Schema::create('cms_single_pages', function (Blueprint $table){
            $table->increments('id');
            $table->string('title');
            $table->string('seo_keyword')->nullable();
            $table->string('seo_description')->nullable();
            $table->text('content');
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 广告位
        Schema::create('cms_banner_positions', function (Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('size')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        // 广告
        Schema::create('cms_banners', function (Blueprint $table){
            $table->increments('id');
            $table->integer('position_id');
            $table->string('image_url');
            $table->string('link')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('sort_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cms_article_categories');
        Schema::dropIfExists('cms_articles');
        Schema::dropIfExists('cms_article_comments');
        Schema::dropIfExists('cms_aticle_comment_praises');
        Schema::dropIfExists('cms_single_pages');
        Schema::dropIfExists('cms_banner_positions');
        Schema::dropIfExists('cms_banners');
    }
}
