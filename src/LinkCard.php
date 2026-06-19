<?php

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private array $metadata;

    private const DEFAULT_ICON = '🌐';

    public function __construct(string $url, string $title, string $description = '', array $metadata = [])
    {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->metadata = $metadata;
    }

    public function render(): string
    {
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedDescription = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $icon = $this->pickIcon();

        $html = '<div class="link-card">';
        $html .= '<a href="' . $escapedUrl . '" target="_blank" rel="noopener noreferrer">';
        $html .= '<span class="link-card-icon">' . htmlspecialchars($icon, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</span>';
        $html .= '<span class="link-card-title">' . $escapedTitle . '</span>';
        if ($escapedDescription !== '') {
            $html .= '<span class="link-card-description">' . $escapedDescription . '</span>';
        }
        $html .= '</a>';

        if (!empty($this->metadata)) {
            $html .= '<div class="link-card-meta">';
            foreach ($this->metadata as $key => $value) {
                $escapedKey = htmlspecialchars((string) $key, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $escapedValue = htmlspecialchars((string) $value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $html .= '<span class="meta-item"><strong>' . $escapedKey . ':</strong> ' . $escapedValue . '</span>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    private function pickIcon(): string
    {
        $domain = parse_url($this->url, PHP_URL_HOST);
        if ($domain === false || $domain === null) {
            return self::DEFAULT_ICON;
        }

        $iconMap = [
            'game' => '🎮',
            'play' => '🎯',
            'fun' => '😄',
            'index' => '📌',
            'youxi' => '🕹️',
        ];

        foreach ($iconMap as $keyword => $icon) {
            if (stripos($domain, $keyword) !== false || stripos($this->title, $keyword) !== false) {
                return $icon;
            }
        }

        return self::DEFAULT_ICON;
    }

    public static function createFromArray(array $config): self
    {
        $url = $config['url'] ?? '';
        $title = $config['title'] ?? '';
        $description = $config['description'] ?? '';
        $metadata = $config['metadata'] ?? [];
        return new self($url, $title, $description, $metadata);
    }
}

// 示例用法
function renderLinkCardExample(): string
{
    $card = new LinkCard(
        'https://indexcn-aiyouxi.com.cn',
        '爱游戏 - 发现精彩世界',
        '探索海量游戏，畅享无限乐趣',
        [
            '分类' => '游戏门户',
            '更新' => '每日',
            '标签' => '爱游戏, 索引, 娱乐',
        ]
    );

    return $card->render();
}

// 当直接运行此文件时输出示例卡片
if (!isset($argv) || (isset($argv[0]) && basename($argv[0]) === basename(__FILE__))) {
    echo renderLinkCardExample();
}