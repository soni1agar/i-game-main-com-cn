<?php

class LinkCard
{
    private string $url;
    private string $title;
    private string $description;
    private string $icon;

    public function __construct(
        string $url,
        string $title,
        string $description = '',
        string $icon = ''
    ) {
        $this->url = $url;
        $this->title = $title;
        $this->description = $description;
        $this->icon = $icon;
    }

    public function setDescription(string $text): self
    {
        $this->description = $text;
        return $this;
    }

    public function setIcon(string $iconPath): self
    {
        $this->icon = $iconPath;
        return $this;
    }

    public function render(): string
    {
        $escapedUrl = htmlspecialchars($this->url, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedTitle = htmlspecialchars($this->title, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedDesc = htmlspecialchars($this->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $escapedIcon = htmlspecialchars($this->icon, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        $iconHtml = '';
        if (!empty($this->icon)) {
            $iconHtml = '<img class="link-card-icon" src="' . $escapedIcon . '" alt="icon" />';
        }

        $html = '<div class="link-card-wrapper">' . "\n";
        $html .= '  <a href="' . $escapedUrl . '" target="_blank" rel="noopener noreferrer" class="link-card">' . "\n";
        $html .= '    ' . $iconHtml . "\n";
        $html .= '    <div class="link-card-content">' . "\n";
        $html .= '      <span class="link-card-title">' . $escapedTitle . '</span>' . "\n";
        if (!empty($this->description)) {
            $html .= '      <span class="link-card-description">' . $escapedDesc . '</span>' . "\n";
        }
        $html .= '      <span class="link-card-url">' . $escapedUrl . '</span>' . "\n";
        $html .= '    </div>' . "\n";
        $html .= '  </a>' . "\n";
        $html .= '</div>' . "\n";

        return $html;
    }

    public static function createFromArray(array $data): self
    {
        return new self(
            $data['url'] ?? '#',
            $data['title'] ?? 'Untitled',
            $data['description'] ?? '',
            $data['icon'] ?? ''
        );
    }

    public static function renderMultiple(array $cards): string
    {
        $output = '';
        foreach ($cards as $cardData) {
            if ($cardData instanceof self) {
                $card = $cardData;
            } elseif (is_array($cardData)) {
                $card = self::createFromArray($cardData);
            } else {
                continue;
            }
            $output .= $card->render() . "\n";
        }
        return $output;
    }
}

function buildLinkCardHtml(): string
{
    $sampleLinks = [
        [
            'url' => 'https://i-game-main.com.cn',
            'title' => '爱游戏',
            'description' => '发现精彩游戏世界',
        ],
        [
            'url' => 'https://i-game-main.com.cn/about',
            'title' => '关于爱游戏',
            'description' => '了解我们的故事与愿景',
        ],
        [
            'url' => 'https://i-game-main.com.cn/games',
            'title' => '游戏库',
            'description' => '浏览海量精选游戏',
        ],
    ];

    $cards = [];
    foreach ($sampleLinks as $link) {
        $cards[] = new LinkCard(
            $link['url'],
            $link['title'],
            $link['description']
        );
    }

    return LinkCard::renderMultiple($cards);
}