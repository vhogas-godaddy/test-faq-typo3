<?php

namespace HostEuropeGmbh\HosteuropeFaq\Domain\Model;

/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2016
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Category
 */
class Category extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * headline
     *
     * @var string
     */
    protected $headline = '';

    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';

    /**
     * subline
     *
     * @var string
     */
    protected $subline = '';

    /**
     * content
     *
     * @var string
     */
    protected $content = '';

    /**
     * parent
     *
     * @var \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category
     */
    protected $parent = null;

    /**
     * showForm
     *
     * @var bool
     */
    protected $showForm = false;

    /**
     * showVote
     *
     * @var string
     */
    protected $showVote = '';

    /**
     * alias
     *
     * @var string
     */
    protected $alias = '';

    /**
     * icon
     *
     * @var string
     */
    protected $icon = '';

    /**
     * seotitle
     *
     * @var string
     */
    protected $seotitle = '';

    /**
     * seodescription
     *
     * @var string
     */
    protected $seodescription = '';

    /**
     * links
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Link>
     * @TYPO3\CMS\Extbase\Annotation\ORM\Cascade("remove")
     */
    protected $links = null;

    /**
     * relatedCategories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category>
     */
    protected $relatedCategories = null;

    /**
     * relatedQuestions
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question>
     */
    protected $relatedQuestions = null;

    /**
     * __construct
     */
    public function __construct()
    {
        //Do not remove the next line: It would break the functionality
        $this->initStorageObjects();
    }

    /**
     * Initializes all ObjectStorage properties
     * Do not modify this method!
     * It will be rewritten on each save in the extension builder
     * You may modify the constructor of this class instead
     *
     * @return void
     */
    protected function initStorageObjects()
    {
        $this->links = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->relatedCategories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $this->relatedQuestions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
    }

    /**
     * Returns the headline
     *
     * @return string $headline
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * Sets the headline
     *
     * @param string $headline
     * @return void
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;
    }

    /**
     * Returns the subline
     *
     * @return string $subline
     */
    public function getSubline()
    {
        return $this->subline;
    }

    /**
     * Sets the subline
     *
     * @param string $subline
     * @return void
     */
    public function setSubline($subline)
    {
        $this->subline = $subline;
    }

    /**
     * Returns the content
     *
     * @return string $content
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sets the content
     *
     * @param string $content
     * @return void
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * Returns the parent
     *
     * @return \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Sets the parent
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $parent
     * @return void
     */
    public function setParent(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Returns the showForm
     *
     * @return bool $showForm
     */
    public function getShowForm()
    {
        return $this->showForm;
    }

    /**
     * Sets the showForm
     *
     * @param bool $showForm
     * @return void
     */
    public function setShowForm($showForm)
    {
        $this->showForm = $showForm;
    }

    /**
     * Returns the boolean state of showForm
     *
     * @return bool
     */
    public function isShowForm()
    {
        return $this->showForm;
    }

    /**
     * Returns the showVote
     *
     * @return string $showVote
     */
    public function getShowVote()
    {
        return $this->showVote;
    }

    /**
     * Sets the showVote
     *
     * @param string $showVote
     * @return void
     */
    public function setShowVote($showVote)
    {
        $this->showVote = $showVote;
    }

    /**
     * Returns the alias
     *
     * @return string $alias
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Sets the alias
     *
     * @param string $alias
     * @return void
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;
    }

    /**
     * Returns the icon
     *
     * @return string $icon
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Sets the icon
     *
     * @param string $icon
     * @return void
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;
    }

    /**
     * Returns the seotitle
     *
     * @return string $seotitle
     */
    public function getSeotitle()
    {
        return $this->seotitle;
    }

    /**
     * Sets the seotitle
     *
     * @param string $seotitle
     * @return void
     */
    public function setSeotitle($seotitle)
    {
        $this->seotitle = $seotitle;
    }

    /**
     * Returns the seodescription
     *
     * @return string $seodescription
     */
    public function getSeodescription()
    {
        return $this->seodescription;
    }

    /**
     * Sets the seodescription
     *
     * @param string $seodescription
     * @return void
     */
    public function setSeodescription($seodescription)
    {
        $this->seodescription = $seodescription;
    }

    /**
     * Adds a Category
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $relatedCategory
     * @return void
     */
    public function addRelatedCategory(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $relatedCategory)
    {
        $this->relatedCategories->attach($relatedCategory);
    }

    /**
     * Removes a Category
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $relatedCategoryToRemove The Category to be removed
     * @return void
     */
    public function removeRelatedCategory(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $relatedCategoryToRemove)
    {
        $this->relatedCategories->detach($relatedCategoryToRemove);
    }

    /**
     * Returns the relatedCategories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category> $relatedCategories
     */
    public function getRelatedCategories()
    {
        return $this->relatedCategories;
    }

    /**
     * Sets the relatedCategories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category> $relatedCategories
     * @return void
     */
    public function setRelatedCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relatedCategories)
    {
        $this->relatedCategories = $relatedCategories;
    }

    /**
     * Adds a Question
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question $relatedQuestion
     * @return void
     */
    public function addRelatedQuestion(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question $relatedQuestion)
    {
        $this->relatedQuestions->attach($relatedQuestion);
    }

    /**
     * Removes a Question
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question $relatedQuestionToRemove The Question to be removed
     * @return void
     */
    public function removeRelatedQuestion(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question $relatedQuestionToRemove)
    {
        $this->relatedQuestions->detach($relatedQuestionToRemove);
    }

    /**
     * Returns the relatedQuestions
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question> $relatedQuestions
     */
    public function getRelatedQuestions()
    {
        return $this->relatedQuestions;
    }

    /**
     * Sets the relatedQuestions
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Question> $relatedQuestions
     * @return void
     */
    public function setRelatedQuestions(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $relatedQuestions)
    {
        $this->relatedQuestions = $relatedQuestions;
    }

    /**
     * Adds a Link
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Link $link
     * @return void
     */
    public function addLink(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Link $link)
    {
        $this->links->attach($link);
    }

    /**
     * Removes a Link
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Link $linkToRemove The Link to be removed
     * @return void
     */
    public function removeLink(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Link $linkToRemove)
    {
        $this->links->detach($linkToRemove);
    }

    /**
     * Returns the links
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Link> $links
     */
    public function getLinks()
    {
        return $this->links;
    }

    /**
     * Sets the links
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Link> $links
     * @return void
     */
    public function setLinks(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $links)
    {
        $this->links = $links;
    }

    /**
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return array
     */
    public function getLinkarguments()
    {
        $arguments = array();

        if ($this->getParent()) {
            $arguments = $this->getParent()->getLinkarguments();
        }

        $arguments[] = $this->slug;

        return $arguments;

    }

    /**
     * @return array
     */
    public function getParentCategories()
    {
        $arguments = array();

        if ($this->getParent()) {
            $arguments = $this->getParent()->getParentCategories();
        }

        $arguments[] = $this->getHeadline();

        return $arguments;

    }

}