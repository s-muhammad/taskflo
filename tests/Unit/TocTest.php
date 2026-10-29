<?php

use App\Support\Toc;

it('keeps existing h2 ids and inline styles untouched', function () {
    $html = '<h2 id="custom-id" style="color:red;font-weight:700">عنوان اصلی</h2><p style="line-height:2">متن</p>';

    $result = Toc::build($html);

    expect($result['items'])->toBe([
        ['id' => 'custom-id', 'text' => 'عنوان اصلی'],
    ]);

    expect($result['html'])
        ->toContain('<h2 id="custom-id" style="color:red;font-weight:700">عنوان اصلی</h2>')
        ->toContain('<p style="line-height:2">متن</p>');
});

it('generates sec ids for h2 tags without id', function () {
    $result = Toc::build('<h2>اول</h2><h2 style="color:blue">دوم</h2><h3>زیرتیتر</h3>');

    expect(array_column($result['items'], 'id'))->toBe(['sec-1', 'sec-2']);
    expect(array_column($result['items'], 'text'))->toBe(['اول', 'دوم']);

    expect($result['html'])
        ->toContain('<h2 id="sec-1">اول</h2>')
        ->toContain('<h2 style="color:blue" id="sec-2">دوم</h2>')
        ->not->toContain('<h3 id=');
});

it('decodes html entities and keeps zwnj intact', function () {
    $result = Toc::build('<h2 id="a">مهارت&zwnj;های &laquo;مدیریت&raquo; &amp; زمان</h2>');

    expect($result['items'][0]['text'])->toBe('مهارت‌های «مدیریت» & زمان');
    expect($result['items'][0]['text'])->toContain("\u{200C}");
});

it('does not generate an id that already exists in the article', function () {
    $result = Toc::build('<h2 id="sec-1">اول</h2><h2>دوم</h2>');

    expect(array_column($result['items'], 'id'))->toBe(['sec-1', 'sec-2']);
    expect(substr_count($result['html'], 'id="sec-1"'))->toBe(1);
});

it('replaces an empty id attribute with a generated one', function () {
    $result = Toc::build('<h2 id="">عنوان</h2>');

    expect($result['items'][0]['id'])->toBe('sec-1');
    expect(substr_count($result['html'], 'id='))->toBe(1);
});

it('leaves html without h2 unchanged', function () {
    $html = '<p style="margin:0">فقط متن</p>';

    expect(Toc::build($html))->toBe(['html' => $html, 'items' => []]);
});
