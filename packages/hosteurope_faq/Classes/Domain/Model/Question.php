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
 * Question
 */
class Question extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity
{

    /**
     * headline
     *
     * @var string
     */
    protected $headline = '';

    /**
     * faqid
     *
     * @var integer
     */
    protected $faqid = '';

	/**
	 * countja
	 *
	 * @var integer
	 */
	protected $countja = '';

	/**
	 * countnein
	 *
	 * @var integer
	 */
	protected $countnein = '';

	/**
	 * countview
	 *
	 * @var integer
	 */
	protected $countview = '';

	/**
	 * prio
	 *
	 * @var integer
	 */
	protected $prio = '';

	/**
	 * countsend
	 *
	 * @var integer
	 */
	protected $countsend = '';

    /**
     * slug
     *
     * @var string
     */
    protected $slug = '';
    
    /**
     * content
     *
     * @var string
     */
    protected $content = '';
    
    /**
     * showTop
     *
     * @var bool
     */
    protected $showTop = false;
    
    /**
     * showForm
     *
     * @var string
     */
    protected $showForm = '';
    
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
     * categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category>
     */
    protected $categories = null;
    
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
        $this->categories = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
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
     * Returns the content
     *
     * @return string $content
     */
    public function getContent()
    {
        $content = $this->content;
        $content = str_replace("https://www.hosteurope.de/typo3/#","https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."#",$content);
        $content = str_replace("https://www.hosteurope.de/#","https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."#",$content);
        $content = str_replace("https://www.hosteurope.de#","https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']."#",$content);
        $content = str_replace("faq-preview/#","#",$content);
        return $content;
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
     * Returns the showTop
     *
     * @return bool $showTop
     */
    public function getShowTop()
    {
        return $this->showTop;
    }
    
    /**
     * Sets the showTop
     *
     * @param bool $showTop
     * @return void
     */
    public function setShowTop($showTop)
    {
        $this->showTop = $showTop;
    }
    
    /**
     * Returns the boolean state of showTop
     *
     * @return bool
     */
    public function isShowTop()
    {
        return $this->showTop;
    }
    
    /**
     * Returns the showForm
     *
     * @return string $showForm
     */
    public function getShowForm()
    {
        return $this->showForm;
    }
    
    /**
     * Sets the showForm
     *
     * @param string $showForm
     * @return void
     */
    public function setShowForm($showForm)
    {
        $this->showForm = $showForm;
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
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $category
     * @return void
     */
    public function addCategory(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $category)
    {
        $this->categories->attach($category);
    }
    
    /**
     * Removes a Category
     *
     * @param \HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $categoryToRemove The Category to be removed
     * @return void
     */
    public function removeCategory(\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category $categoryToRemove)
    {
        $this->categories->detach($categoryToRemove);
    }
    
    /**
     * Returns the categories
     *
     * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category> $categories
     */
    public function getCategories()
    {
        return $this->categories;
    }
    
    /**
     * Sets the categories
     *
     * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\HostEuropeGmbh\HosteuropeFaq\Domain\Model\Category> $categories
     * @return void
     */
    public function setCategories(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $categories)
    {
        $this->categories = $categories;
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
    public function getSlug() {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug( $slug ) {
        $this->slug = $slug;
    }

    /**
     *  @return array
     */
    public function getLinkarguments() {

        /**
         * @var \HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository $categoryRepository
         */
        $categoryRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository::class);
        $category = $categoryRepository->findOneByPid($this->getPid());

        if($category){
            $arguments = $category->getLinkarguments();
        }else{
            $arguments = array();
        }

        $arguments[] = $this->slug;

        return $arguments;
    }

	/**
	 *  @return array
	 */
	public function getParentCategories() {

		/**
		 * @var \HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository $categoryRepository
		 */
		$categoryRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\HostEuropeGmbh\HosteuropeFaq\Domain\Repository\CategoryRepository::class);
		$category = $categoryRepository->findOneByPid($this->getPid());

		if($category){
			$arguments = $category->getParentCategories();
		}else{
			$arguments = array();
		}

		return $arguments;

	}

    /**
     * @return int
     */
    public function getFaqid() {
        return $this->faqid;
    }

    /**
     * @param int $faqid
     */
    public function setFaqid( $faqid ) {
        $this->faqid = $faqid;
    }

	/**
	 * @return int
	 */
	public function getCountview() {
		return $this->countview;
	}

	/**
	 * @param int $countview
	 */
	public function setCountview( $countview ) {
		$this->countview = $countview;
	}

	/**
	 * @return int
	 */
	public function getCountnein() {
		return $this->countnein;
	}

	/**
	 * @param int $countnein
	 */
	public function setCountnein( $countnein ) {
		$this->countnein = $countnein;
	}

	/**
	 * @return int
	 */
	public function getCountja() {
		return $this->countja;
	}

	/**
	 * @param int $countja
	 */
	public function setCountja( $countja ) {
		$this->countja = $countja;
	}

	/**
	 * @return int
	 */
	public function getCountsend() {
		return $this->countsend;
	}

	/**
	 * @param int $countsend
	 */
	public function setCountsend( $countsend ) {
		$this->countsend = $countsend;
	}

	public function getIndex(){
		$index = array();

		$index['s_name'] = $this->getHeadline();
		$index['slug'] = $this->getSlug();
		$index['prio'] = $this->getPrio();
		$index['linkArguments'] = $this->getLinkarguments();
		$index['s_categories'] = $this->getParentCategories();

		$c = strip_tags($this->getContent());
		$c = preg_replace('/\s+/', ' ',$c);
		$c = str_replace("\n"," ",$c);
		$c = str_replace("\t"," ",$c);
		$c = str_replace("\r"," ",$c);
		$index['content'] =  $c;
		$desc = mb_substr($c,0,400);

		if(strlen($c) > 400){
			$desc = $desc."...";
		}

		$index['s_description'] = $desc;
		$index['views'] = $this->getCountview();
		$index['s_language_uid'] = 0;
		$index['s_subline']  = "FAQ / ".implode(" / ",$index['s_categories']);
		$index['s_sort']  = mb_strtolower( $index['s_name'], 'UTF-8' );
		$index['s_label'] = "question";

		return $index;
	}

	/**
	 * @return int
	 */
	public function getPrio() {
		return $this->prio;
	}

	/**
	 * @param int $prio
	 */
	public function setPrio( $prio ) {
		$this->prio = $prio;
	}

}