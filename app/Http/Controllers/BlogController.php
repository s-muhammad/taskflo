<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        $blogs = Blog::latest()->paginate(9);
        return view('blog.index', compact('blogs'));
    }

    public function single(Blog $blog)
    {
        $related = Blog::latest()->take(2)->get();
        $comments = $blog->comments()->where('approved', true)->latest()->get();
        $this->ensureCaptcha();

        return view('blog.single', compact('blog', 'related', 'comments'));
    }

    /**
     * ساخت سوال امنیتی تصادفی و نگه‌داشتن آن در سشن
     */
    private function ensureCaptcha(): void
    {
        if (session()->has('comment_captcha_answer')) {
            return;
        }

        $a = random_int(1, 9);
        $b = random_int(1, 9);

        if (random_int(0, 1) === 1) {
            [$a, $b] = [max($a, $b), min($a, $b)];
            $question = fa_digits($a) . ' - ' . fa_digits($b) . ' = ?';
            $answer = $a - $b;
        } else {
            $question = fa_digits($a) . ' + ' . fa_digits($b) . ' = ?';
            $answer = $a + $b;
        }

        session([
            'comment_captcha_question' => $question,
            'comment_captcha_answer' => $answer,
        ]);
    }

    public function comment(Request $request, Blog $blog)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:64'],
            'comment' => ['required', 'string', 'max:2000'],
            'captcha_answer' => [
                'required',
                'integer',
                function (string $attribute, mixed $value, \Closure $fail) {
                    $expected = session('comment_captcha_answer');

                    if ($expected === null || (int) $value !== (int) $expected) {
                        $fail('پاسخ سوال امنیتی صحیح نیست.');
                    }
                },
            ],
        ], [], [
            'name' => 'نام',
            'comment' => 'متن نظر',
            'captcha_answer' => 'پاسخ سوال امنیتی',
        ]);

        $blog->comments()->create([
            'name' => $data['name'],
            'comment' => $data['comment'],
            'approved' => false,
        ]);

        session()->forget(['comment_captcha_question', 'comment_captcha_answer']);

        return redirect()
            ->route('blog.single', $blog->slug)
            ->with('comment_success', 'نظر شما ثبت شد و پس از تایید مدیریت نمایش داده می‌شود.');
    }
}
