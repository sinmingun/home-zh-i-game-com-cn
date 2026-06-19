<?php
/**
 * Site Meta Information for Game Portal
 * 
 * Provides the ability to define site metadata and generate concise description text.
 */

class SiteMeta {
    private $metaData = [];

    /**
     * Initialize with an array of site metadata.
     *
     * @param array $data Associative array of site metadata (title, keywords, url, etc.)
     */
    public function __construct(array $data = []) {
        $this->metaData = $data;
    }

    /**
     * Set or update a metadata value.
     *
     * @param string $key   The metadata key.
     * @param mixed  $value The value to set.
     */
    public function setMeta($key, $value) {
        $this->metaData[$key] = $value;
    }

    /**
     * Get a specific metadata value.
     *
     * @param string $key The metadata key.
     * @return mixed|null The value if exists, null otherwise.
     */
    public function getMeta($key) {
        return isset($this->metaData[$key]) ? $this->metaData[$key] : null;
    }

    /**
     * Generate a short description text based on current metadata.
     *
     * @param int $maxWords Maximum number of words for the description.
     * @return string The generated description.
     */
    public function generateDescription($maxWords = 20) {
        $title = isset($this->metaData['title']) ? $this->metaData['title'] : '';
        $keywords = isset($this->metaData['keywords']) ? $this->metaData['keywords'] : [];
        $url = isset($this->metaData['url']) ? $this->metaData['url'] : '';
        $desc = isset($this->metaData['description']) ? $this->metaData['description'] : '';

        if (!empty($desc)) {
            $words = explode(' ', $desc);
            if (count($words) > $maxWords) {
                $desc = implode(' ', array_slice($words, 0, $maxWords)) . '...';
            }
            return $desc;
        }

        $parts = [];
        if (!empty($title)) {
            $parts[] = $title;
        }
        if (!empty($keywords)) {
            $parts[] = implode(', ', array_slice($keywords, 0, 5));
        }
        if (!empty($url)) {
            $parts[] = $url;
        }

        $description = implode(' - ', $parts);
        if (!empty($description)) {
            $words = explode(' ', $description);
            if (count($words) > $maxWords) {
                $description = implode(' ', array_slice($words, 0, $maxWords)) . '...';
            }
        }

        return $description;
    }

    /**
     * Get the entire metadata array.
     *
     * @return array
     */
    public function getAllMeta() {
        return $this->metaData;
    }

    /**
     * Return a sanitized version of metadata for safe HTML output.
     *
     * @return array
     */
    public function getSanitizedMeta() {
        $safe = [];
        foreach ($this->metaData as $key => $value) {
            if (is_string($value)) {
                $safe[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            } elseif (is_array($value)) {
                $safe[$key] = array_map(function($item) {
                    return htmlspecialchars((string)$item, ENT_QUOTES, 'UTF-8');
                }, $value);
            } else {
                $safe[$key] = $value;
            }
        }
        return $safe;
    }
}

// Example usage with site specific data
$siteConfig = [
    'title'       => '爱游戏 - 游戏之家',
    'keywords'    => ['爱游戏', '游戏', '娱乐', '在线游戏', '休闲', '热门游戏'],
    'url'         => 'https://home-zh-i-game.com.cn',
    'description' => '爱游戏为您提供最新最热的游戏资讯和在线娱乐体验。发现精彩游戏，尽在游戏之家。',
    'author'      => '游戏团队',
    'language'    => 'zh-CN',
];

$meta = new SiteMeta($siteConfig);

// Generate a short description for meta tag usage
$shortDesc = $meta->generateDescription(15);

// Display example output (safe for HTML)
echo '<!DOCTYPE html>' . PHP_EOL;
echo '<html lang="zh-CN">' . PHP_EOL;
echo '<head>' . PHP_EOL;
echo '    <meta charset="UTF-8">' . PHP_EOL;
echo '    <title>' . $meta->getSanitizedMeta()['title'] . '</title>' . PHP_EOL;
echo '    <meta name="description" content="' . htmlspecialchars($shortDesc, ENT_QUOTES, 'UTF-8') . '">' . PHP_EOL;
echo '    <meta name="keywords" content="' . implode(', ', $meta->getSanitizedMeta()['keywords']) . '">' . PHP_EOL;
echo '</head>' . PHP_EOL;
echo '<body>' . PHP_EOL;
echo '    <h1>Welcome to ' . $meta->getSanitizedMeta()['title'] . '</h1>' . PHP_EOL;
echo '    <p>Site URL: ' . htmlspecialchars($meta->getMeta('url'), ENT_QUOTES, 'UTF-8') . '</p>' . PHP_EOL;
echo '    <p>Description: ' . htmlspecialchars($shortDesc, ENT_QUOTES, 'UTF-8') . '</p>' . PHP_EOL;
echo '</body>' . PHP_EOL;
echo '</html>' . PHP_EOL;