<?php

namespace App\Domain\Feed\Parser;

use App\Domain\Feed\Exception\ParserException;
use App\Domain\Feed\Feed\Author;
use App\Domain\Feed\Feed\Feed;
use App\Domain\Feed\Feed\Item;
use App\Domain\Feed\Feed\Tag;
use App\Domain\Feed\FeedType;
use Carbon\Carbon;

class JsonParser implements ParserInterface
{

    /**
     * @var array<mixed>
     */
    public array $json;

    public function __construct(string $content)
    {
        $json = json_decode($content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new ParserException('Invalid JSON');
        }

        $this->json = $json;
    }

    public function parse(): Feed
    {
        $version = $this->json['version'] ?? '1.0';
        $version = str_replace('https://jsonfeed.org/version/', '', $version);

        $title = strval($this->json['title'] ?? '');
        $homepageUrl = strval($this->json['home_page_url'] ?? '');
        $feedUrl = strval($this->json['feed_url'] ?? '');

        $description = $this->json['description'] ?? '';
        $description = empty($description) ? null : strval($description);

        $icon = $this->json['icon'] ?? '';
        $favicon = $this->json['favicon'] ?? '';
        $icon = empty($favicon) ? $icon : $favicon;
        $icon = empty($icon) ? null : strval($icon);

        $language = $this->json['language'] ?? '';
        $language = empty($language) ? null : strval($language);

        $items = $this->json['items'] ?? [];
        $items = is_array($items) ? $items : [];
        $itemsObjects = [];
        foreach ($items as $item) {
            try {
                $itemsObjects[] = $this->parseItem($item);
            } catch (ParserException) {
                //
            }
        }

        return new Feed(
            FeedType::JSON,
            $version,
            $title,
            $homepageUrl,
            $feedUrl,
            $description,
            $icon,
            $language,
            items: $itemsObjects
        );
    }


    /**
     * @param array<mixed> $item
     * @return Item
     */
    private function parseItem(mixed $item): Item
    {
        if (!is_array($item)) {
            throw new ParserException('Item must be an array');
        }

        $id = strval($item['id'] ?? '');
        $url = strval($item['url'] ?? '');
        $title = strval($item['title'] ?? '');

        $contentHtml = $item['content_html'] ?? null;
        $contentHtml = empty($contentHtml) ? null : strval($contentHtml);

        $contentText = $item['content_text'] ?? null;
        $contentText = empty($contentText) ? null : strval($contentText);

        if ($contentHtml === null && $contentText === null) {
            throw new ParserException('Either content_html or content_text must be provided');
        }

        $summary = $item['summary'] ?? null;
        $summary = empty($summary) ? null : strval($summary);

        $imageValue = $item['image'] ?? null;
        $bannerImageValue = $item['banner_image'] ?? null;
        $image = $imageValue ?? $bannerImageValue ?? null;

        $publishedAt = $this->date($item['date_published'] ?? null);
        $updatedAt = $this->date($item['date_modified'] ?? null);

        $authorsValue = $item['authors'] ?? [];
        $authorsValue = is_array($authorsValue) ? $authorsValue : [];
        $authors = [];

        foreach ($authorsValue as $author) {
            try {
                $authors[] = $this->parseAuthor($author);
            } catch (ParserException) {
                // Skip
            }
        }

        $tagsValue = $item['tags'] ?? [];
        $tagsValue = is_array($tagsValue) ? $tagsValue : [];
        $tags = [];

        foreach ($tagsValue as $tag) {
            $tags[] = new Tag(strval($tag));
        }

        $language = $item['language'] ?? null;
        $language = empty($language) ? null : strval($language);

        return new Item(
            $id,
            $url,
            $title,
            $contentHtml,
            $contentText,
            $summary,
            $image,
            $publishedAt,
            $updatedAt,
            $authors,
            $tags,
            $language
        );
    }

    /**
     * @param array<mixed> $author
     * @return Author
     */
    private function parseAuthor(mixed $author): Author
    {
        if (!is_array($author)) {
            throw new ParserException('Author must be an array');
        }

        $name = $author['name'] ?? null;

        if (!is_string($name)) {
            throw new ParserException('Author name is required');
        }

        $url = $author['url'] ?? null;
        $url = empty($url) ? null : strval($url);

        $avatar = $author['avatar'] ?? null;
        $avatar = empty($avatar) ? null : strval($avatar);

        return new Author($name, $url, $avatar);
    }

    private function date(mixed $date): ?Carbon
    {
        if (!is_string($date)) {
            return null;
        }

        try {
            return Carbon::parse($date);
        } catch (\Exception) {
            return null;
        }
    }
}
