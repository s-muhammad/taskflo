<?php

use App\Models\Blog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

function makeBlog(array $attributes = []): Blog
{
    return Blog::create(array_merge([
        'title' => 'مدیریت زمان برای تیم‌ها',
        'slug' => 'test-'.Str::lower(Str::random(8)),
        'summary' => 'خلاصه‌ای کوتاه از مقاله برای توضیحات متا',
        'image' => 'uploads/blog/test-cover.png',
        'description' => '<p style="line-height:2">متن مقدمه</p>',
    ], $attributes));
}

it('renders the table of contents above the body when there is no placeholder', function () {
    $description = '<p style="line-height:2">مقدمه‌ی مقاله</p>'
        .'<h2>چرا مدیریت&zwnj; زمان مهم است؟</h2><p>متن یک</p>'
        .'<h2 id="custom-heading" style="color:red">ابزارهای پیشنهادی</h2><p>متن دو</p>'
        .'<h2>روش‌های عملی</h2><p>متن سه</p>'
        .'<h2>نتیجه&zwnj;گیری</h2><p>متن چهار</p>';

    $blog = makeBlog([
        'description' => $description,
        'meta_title' => 'عنوان سئوی مقاله',
        'meta_description' => 'توضیحات متای مقاله',
    ]);

    $response = $this->get(route('blog.single', $blog->slug));

    $response->assertOk();
    $response->assertSee('aria-label="فهرست مطالب"', false);
    $response->assertSee('href="#sec-1"', false);
    $response->assertSee('href="#custom-heading"', false);
    $response->assertSee('id="custom-heading"', false);
    $response->assertSee('style="color:red"', false);
    $response->assertSee('چرا مدیریت‌ زمان مهم است؟', false);

    // فهرست قبل از اولین h2 و بدون تکرار تگ‌ها
    $html = $response->getContent();
    expect(strpos($html, 'aria-label="فهرست مطالب"'))->toBeLessThan(strpos($html, 'id="sec-1"'));
    expect(substr_count($html, 'aria-label="فهرست مطالب"'))->toBe(1);
    expect(substr_count($html, 'style="color:red"'))->toBe(1);

    // سئو
    $response->assertSee('<title>عنوان سئوی مقاله</title>', false);
    $response->assertSee('<meta name="description" content="توضیحات متای مقاله">', false);
    $response->assertSee('<link rel="canonical" href="'.route('blog.single', $blog->slug).'">', false);
    $response->assertSee('<meta property="og:title" content="عنوان سئوی مقاله">', false);
    $response->assertSee('<meta property="og:image" content="'.asset('uploads/blog/test-cover.png').'">', false);
    $response->assertSee('"@type":"Article"', false);
    $response->assertSee('"datePublished":"'.$blog->created_at->toIso8601String().'"', false);
    $response->assertSee('"name":"TaskFlo"', false);
    $response->assertSee('"@id":"'.route('blog.single', $blog->slug).'"', false);

    // JSON-LD باید JSON معتبر و بدون خرابی Blade باشد
    preg_match('/<script type="application\/ld\+json">\s*(.*?)\s*<\/script>/su', $html, $schemaMatch);
    $schema = json_decode($schemaMatch[1] ?? '', true);

    expect($schema)->toBeArray()
        ->and($schema['@context'])->toBe('https://schema.org')
        ->and($schema['@type'])->toBe('Article')
        ->and($schema['headline'])->toBe($blog->title)
        ->and($schema['datePublished'])->toBe($blog->created_at->toIso8601String())
        ->and($schema['dateModified'])->toBe($blog->updated_at->toIso8601String())
        ->and($schema['author']['@type'])->toBe('Person')
        ->and($schema['publisher']['name'])->toBe('TaskFlo')
        ->and($schema['mainEntityOfPage']['@id'])->toBe(route('blog.single', $blog->slug));
});

it('places the table of contents exactly where the placeholder is', function () {
    $description = '<p style="line-height:2">مقدمه</p>'
        .'<p>[[TOC]]</p>'
        .'<h2>بخش اول</h2><p>متن</p>'
        .'<h2>بخش دوم</h2><p>متن</p>'
        .'<h2>بخش سوم</h2><p>متن</p>';

    $blog = makeBlog(['description' => $description]);

    $response = $this->get(route('blog.single', $blog->slug));

    $response->assertOk();
    $response->assertDontSee('[[TOC]]', false);
    $response->assertSee('aria-label="فهرست مطالب"', false);
    $response->assertSeeInOrder(['مقدمه</p>', 'aria-label="فهرست مطالب"', 'id="sec-1"'], false);
    expect(substr_count($response->getContent(), 'aria-label="فهرست مطالب"'))->toBe(1);
});

it('falls back to title and summary when meta fields are empty', function () {
    $blog = makeBlog([
        'title' => 'عنوان اصلی مقاله',
        'summary' => 'خلاصه‌ی مقاله برای متا',
        'meta_title' => null,
        'meta_description' => null,
    ]);

    $response = $this->get(route('blog.single', $blog->slug));

    $response->assertOk();
    $response->assertSee('<title>عنوان اصلی مقاله</title>', false);
    $response->assertSee('<meta name="description" content="خلاصه‌ی مقاله برای متا">', false);
    $response->assertSee('<meta property="og:title" content="عنوان اصلی مقاله">', false);
    $response->assertSee('<meta property="og:description" content="خلاصه‌ی مقاله برای متا">', false);
});

it('hides the table of contents when there are two headings or fewer', function () {
    $blog = makeBlog([
        'description' => '<h2>اول</h2><p>متن</p><h2>دوم</h2><p>متن</p>',
    ]);

    $response = $this->get(route('blog.single', $blog->slug));

    $response->assertOk();
    $response->assertDontSee('aria-label="فهرست مطالب"', false);
    $response->assertSee('id="sec-1"', false);
});
