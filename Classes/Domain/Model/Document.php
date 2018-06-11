<?php

namespace SourceBroker\Hugo\Domain\Model;

/**
 * Class Document
 *
 * @package SourceBroker\Hugo\Traversing
 */
class Document
{
    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $pid;

    /**
     * @var string
     */
    protected $slug = '';

    /**
     * @var int
     */
    protected $weight = 0;

    /**
     * @var string
     */
    protected $layout = null;

    /**
     * @var array
     */
    protected $frontMatter = [
        'title' => '',
        'draft' => 0,
        'menu' => []
    ];


    /**
     * @var null
     */
    protected $storeFilename = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return self
     */
    public function setId(int $id): self
    {
        $this->frontMatter['id'] = $id;
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getPid()
    {
        return $this->pid;
    }

    /**
     * @param int $pid
     * @return Document
     */
    public function setPid(int $pid): self
    {
        $this->frontMatter['pid'] = $pid;
        $this->pid = $pid;
        return $this;
    }

    /**
     * @return null
     */
    public function getStoreFilename()
    {
        return $this->storeFilename;
    }

    /**
     * @param null $storeFilename
     * @return Document
     */
    public function setStoreFilename($storeFilename): Document
    {
        $this->storeFilename = $storeFilename;
        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     * @return Document
     */
    public function setWeight(int $weight): self
    {
        $this->frontMatter['weight'] = $weight;
        $this->weight = $weight;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     *
     * @return self
     */
    public function setSlug(string $slug): self
    {
        $this->slug = $slug;
        return $this;
    }

    /**
     * @param string $title
     * @return Document
     */
    public function setTitle(string $title): self
    {
        $this->frontMatter['title'] = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->frontMatter['title'];
    }

    /**
     * @return array
     */
    public function getFrontMatter(): array
    {
        return $this->frontMatter;
    }

    /**
     * @return bool
     */
    public function getDraft(): bool
    {
        return (bool)$this->frontMatter['draft'];
    }

    /**
     * @param bool $draft
     * @return Document
     */
    public function setDraft(bool $draft): self
    {
        $this->frontMatter['draft'] = $draft;
        return $this;
    }

    /**
     * @return string
     */
    public function getLayout(): string
    {
        return $this->layout;
    }

    /**
     * @param string $layout
     * @return Document
     */
    public function setLayout(string $layout): self
    {
        $this->frontMatter['layout'] = $layout;
        return $this;
    }

    /**
     * @param array $contentElements
     * @return Document
     */
    public function setContent(array $contentElements): self
    {
        foreach ((array)$contentElements as $contentElement) {
            $this->frontMatter['columns']['col' . $contentElement['colPos']][$contentElement['sorting']] = $contentElement['uid'];
        }
        foreach ((array)$this->frontMatter['columns'] as $key => $values) {
            $this->frontMatter['columns'][$key] = array_values($values);
        }
        return $this;
    }

    /**
     * @param string $menuId
     * @param $page
     * @param $parentPage
     * @return Document
     */
    public function addToMenu(string $menuId, $page, $parentPage = null): self
    {
        if (empty($page['nav_hide'])) {
            if (!in_array($menuId, $this->frontMatter['menu']) && !$page['is_siteroot']) {
                $menu = [
                    'weight' => $page['sorting'],
                    'identifier' => $page['uid']
                ];
                if (empty($parentPage['is_siteroot']) && $page['pid']) {
                    $menu = array_merge($menu, ['parent' => $page['pid']]);
                }
                $this->frontMatter['menu'][$menuId] = $menu;
            }
        }
        return $this;
    }
}