<?php

namespace App\Support;

class Toc
{
    /**
     * ساخت فهرست مطالب از تگ‌های h2 و افزودن id به آن‌هایی که id ندارند.
     *
     * @return array{html: string, items: array<int, array{id: string, text: string}>}
     */
    public static function build(string $html): array
    {
        $items = [];
        $counter = 0;
        $usedIds = self::existingIds($html);

        $result = preg_replace_callback(
            '/<h2\b([^>]*)>(.*?)<\/h2>/isu',
            function (array $match) use (&$items, &$counter, &$usedIds) {
                $attributes = $match[1];
                $content = $match[2];

                $id = self::attributeId($attributes);

                if ($id === null) {
                    // اگر id خالی (id="") وجود داشت، حذفش می‌کنیم تا attribute تکراری نسازیم
                    $attributes = preg_replace('/(?<![\w-])id\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s"\'=<>`]+)/isu', '', $attributes) ?? $attributes;

                    do {
                        $counter++;
                        $id = 'sec-'.$counter;
                    } while (in_array($id, $usedIds, true));

                    $usedIds[] = $id;
                    $attributes = rtrim($attributes).' id="'.self::escape($id).'"';
                }

                $text = html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $text = trim(preg_replace('/\s+/u', ' ', $text) ?? '');

                if ($text !== '') {
                    $items[] = ['id' => $id, 'text' => $text];
                }

                return '<h2'.$attributes.'>'.$content.'</h2>';
            },
            $html
        );

        return [
            'html' => $result ?? $html,
            'items' => $items,
        ];
    }

    /**
     * همه‌ی idهای موجود روی h2ها (برای جلوگیری از id تکراری).
     *
     * @return array<int, string>
     */
    private static function existingIds(string $html): array
    {
        preg_match_all('/<h2\b([^>]*)>/isu', $html, $matches);

        $ids = [];

        foreach ($matches[1] ?? [] as $attributes) {
            $id = self::attributeId($attributes);

            if ($id !== null) {
                $ids[] = $id;
            }
        }

        return $ids;
    }

    /**
     * خواندن مقدار attribute id از رشته‌ی attributeها.
     */
    private static function attributeId(string $attributes): ?string
    {
        $pattern = "/(?<![\w-])id\s*=\s*(?:\"([^\"]*)\"|'([^']*)'|([^\s\"'=<>`]+))/isu";

        if (! preg_match($pattern, $attributes, $match)) {
            return null;
        }

        foreach ([1, 2, 3] as $group) {
            if (isset($match[$group]) && trim($match[$group]) !== '') {
                return trim($match[$group]);
            }
        }

        return null;
    }

    private static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}
